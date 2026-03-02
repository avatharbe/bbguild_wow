<?php
/**
 * WoW API adapter
 *
 * Implements game_api_interface by wrapping the Battle.net API classes.
 *
 * @package   bbguild_wow v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\game;

use avathar\bbguild\model\games\game_api_interface;
use avathar\bbguild_wow\api\battlenet;

/**
 * Class wow_api
 *
 * Adapts the Battle.net SDK to the bbGuild game_api_interface.
 *
 * @package avathar\bbguild_wow\game
 */
class wow_api implements game_api_interface
{
	/** @var \phpbb\cache\service */
	private $cache;

	/** @var \phpbb\db\driver\driver_interface */
	private $db;

	/** @var string */
	private $guild_wow_table;

	/** @var string */
	private $bb_players_table;

	/** @var string */
	private $bb_ranks_table;

	/**
	 * @param \phpbb\cache\service              $cache
	 * @param \phpbb\db\driver\driver_interface $db
	 * @param string                            $guild_wow_table
	 * @param string                            $bb_players_table
	 * @param string                            $bb_ranks_table
	 */
	public function __construct(\phpbb\cache\service $cache, \phpbb\db\driver\driver_interface $db, $guild_wow_table, $bb_players_table, $bb_ranks_table)
	{
		$this->cache = $cache;
		$this->db = $db;
		$this->guild_wow_table = $guild_wow_table;
		$this->bb_players_table = $bb_players_table;
		$this->bb_ranks_table = $bb_ranks_table;
	}

	/**
	 * @inheritdoc
	 */
	public function fetch_guild_data(string $guild_name, string $realm, string $region, array $params)
	{
		global $phpbb_container;

		$game = $this->get_game_from_db($phpbb_container);
		if (!$game || trim($game->getApikey()) == '')
		{
			return false;
		}

		$ext_path = $this->get_ext_path($phpbb_container);
		$api = new battlenet('guild', $region, $game->getApikey(), $game->get_apilocale(), $game->get_privkey(), $ext_path, $this->cache);
		$data = $api->guild->getGuild($guild_name, $realm, $params);
		unset($api);

		if (isset($data['response']))
		{
			return $data['response'];
		}

		return $data;
	}

	/**
	 * @inheritdoc
	 */
	public function process_guild_data(array $raw_data, array $params): array
	{
		$result = array();

		$result['achievementpoints'] = isset($raw_data['achievementPoints']) ? $raw_data['achievementPoints'] : 0;
		$result['level'] = isset($raw_data['level']) ? $raw_data['level'] : 0;
		$result['battlegroup'] = isset($raw_data['battlegroup']) ? $raw_data['battlegroup'] : '';

		// Faction mapping: Battle.net side 0 = Alliance (bbGuild faction 1), non-0 = Horde (bbGuild faction 2)
		$result['faction'] = (isset($raw_data['side']) && $raw_data['side'] == 0) ? 1 : 2;
		$result['faction_name'] = ($result['faction'] == 1) ? 'Alliance' : 'Horde';

		// Guild armory URL
		$result['guildarmoryurl'] = '';
		if (isset($raw_data['name']))
		{
			$result['guildarmoryurl'] = sprintf('http://%s.battle.net/wow/en/', $raw_data['_region'] ?? '') . 'guild/' . ($raw_data['_realm'] ?? '') . '/' . $raw_data['name'] . '/';
		}

		// Emblem data — generate emblem image if data available
		$result['emblem'] = isset($raw_data['emblem']) ? $raw_data['emblem'] : '';
		$result['emblempath'] = '';
		if (!empty($result['emblem']))
		{
			$guild_name = isset($raw_data['name']) ? $raw_data['name'] : '';
			$realm = $raw_data['_realm'] ?? '';
			$region = $raw_data['_region'] ?? '';
			$result['emblempath'] = $this->create_emblem($result['emblem'], $result['faction'], $guild_name, $realm, $region);
		}

		// Member data
		$result['members'] = isset($raw_data['members']) ? $raw_data['members'] : array();
		$result['playercount'] = count($result['members']);

		return $result;
	}

	/**
	 * @inheritdoc
	 */
	public function fetch_character_data(string $name, string $realm, string $region)
	{
		global $phpbb_container;

		$game = $this->get_game_from_db($phpbb_container);
		if (!$game || trim($game->getApikey()) == '')
		{
			return false;
		}

		$ext_path = $this->get_ext_path($phpbb_container);
		$api = new battlenet('character', $region, $game->getApikey(), $game->get_apilocale(), $game->get_privkey(), $ext_path, $this->cache);
		$params = array('guild', 'titles', 'talents');

		$data = $api->character->getCharacter($name, $realm, $params);
		unset($api);

		if (isset($data['response']))
		{
			return $data['response'];
		}

		return $data;
	}

	/**
	 * @inheritdoc
	 */
	public function get_player_armory_url(string $name, string $realm, string $region): string
	{
		return sprintf('http://%s.battle.net/wow/en/', $region) . 'character/' . rawurlencode($realm) . '/' . rawurlencode($name) . '/simple';
	}

	/**
	 * @inheritdoc
	 */
	public function get_player_portrait_url(array $player_data): string
	{
		if (isset($player_data['thumbnail']) && isset($player_data['region']))
		{
			return sprintf('http://%s.battle.net/static-render/%s/', $player_data['region'], $player_data['region']) . $player_data['thumbnail'];
		}

		return '';
	}

	/**
	 * @inheritdoc
	 */
	public function sync_guild_members(array $member_data, int $guild_id, string $region, int $min_level): void
	{
		if (empty($member_data))
		{
			return;
		}

		$this->sync_wow_ranks($member_data, $guild_id);
		$this->update_wow_roster($member_data, $guild_id, $region, $min_level);
	}

	/**
	 * Synchronise WoW guild ranks from Battle.net API data.
	 *
	 * Creates any ranks that exist in the API data but not yet in the database.
	 *
	 * @param array $member_data Raw member array from Battle.net API
	 * @param int   $guild_id
	 */
	private function sync_wow_ranks(array $member_data, int $guild_id): void
	{
		$newranks = array();
		foreach ($member_data as $new)
		{
			$newranks[$new['rank']] = 0;
		}
		foreach ($member_data as $new)
		{
			$newranks[$new['rank']] += 1;
		}
		ksort($newranks);

		// Get existing ranks
		$sql = 'SELECT rank_id FROM ' . $this->bb_ranks_table . '
				WHERE guild_id = ' . (int) $guild_id . ' AND rank_id < 90
				ORDER BY rank_id ASC';
		$result = $this->db->sql_query($sql);
		$oldranks = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$oldranks[(int) $row['rank_id']] = 0;
		}
		$this->db->sql_freeresult($result);

		// Insert ranks that don't exist yet
		$diff = array_diff_key($newranks, $oldranks);
		foreach ($diff as $rank_id => $count)
		{
			// Delete + insert to avoid duplicates
			$sql = 'DELETE FROM ' . $this->bb_ranks_table . '
					WHERE rank_id = ' . (int) $rank_id . '
					AND guild_id = ' . (int) $guild_id;
			$this->db->sql_query($sql);

			$query = $this->db->sql_build_array('INSERT', array(
				'rank_id'     => (int) $rank_id,
				'rank_name'   => 'Rank' . $rank_id,
				'rank_hide'   => 0,
				'rank_prefix' => '',
				'rank_suffix' => '',
				'guild_id'    => (int) $guild_id,
			));
			$this->db->sql_query('INSERT INTO ' . $this->bb_ranks_table . $query);
		}
	}

	/**
	 * Update the WoW guild roster from Battle.net API data.
	 *
	 * Inserts new players and updates existing ones.
	 *
	 * @param array  $member_data Raw member array from Battle.net API
	 * @param int    $guild_id
	 * @param string $region
	 * @param int    $min_level   Minimum level to import
	 */
	private function update_wow_roster(array $member_data, int $guild_id, string $region, int $min_level): void
	{
		global $user;

		$player_ids = array();
		$oldplayers = array();
		$newplayers = array();

		// Get existing players
		$sql = 'SELECT player_name, player_id, player_realm FROM ' . $this->bb_players_table . '
				WHERE player_guild_id = ' . (int) $guild_id . "
				AND game_id = 'wow'
				ORDER BY player_name ASC";
		$result = $this->db->sql_query($sql);
		while ($row = $this->db->sql_fetchrow($result))
		{
			$oldplayers[] = $row['player_name'] . '-' . $row['player_realm'];
			$player_ids[bin2hex($row['player_name'] . '-' . $row['player_realm'])] = $row['player_id'];
		}
		$this->db->sql_freeresult($result);

		foreach ($member_data as $mb)
		{
			$newplayers[] = $mb['character']['name'] . '-' . $mb['character']['realm'];
		}

		$to_add = array_diff($newplayers, $oldplayers);

		$this->db->sql_transaction('begin');

		// Insert new players
		foreach ($member_data as $mb)
		{
			if (in_array($mb['character']['name'] . '-' . $mb['character']['realm'], $to_add) && $mb['character']['level'] >= $min_level)
			{
				$realm = isset($mb['character']['realm']) ? $mb['character']['realm'] : 'unknown';
				$portrait_url = sprintf('http://%s.battle.net/static-render/%s/', $region, $region) . $mb['character']['thumbnail'];
				$armory_url = sprintf('http://%s.battle.net/wow/en/', $region) . 'character/' . $realm . '/' . $mb['character']['name'] . '/simple';
				$title = '';
				if (isset($mb['titles']))
				{
					foreach ($mb['titles'] as $t)
					{
						if (isset($t['selected']))
						{
							$title = $t['name'];
						}
					}
				}

				$query = $this->db->sql_build_array('INSERT', array(
					'player_name'         => ucwords($mb['character']['name']),
					'player_status'       => 1,
					'player_level'        => (int) $mb['character']['level'],
					'player_race_id'      => (int) $mb['character']['race'],
					'player_class_id'     => (int) $mb['character']['class'],
					'player_rank_id'      => isset($mb['rank']) ? (int) $mb['rank'] : 1,
					'player_role'         => 'NA',
					'player_realm'        => $realm,
					'player_region'       => $region,
					'player_comment'      => sprintf($user->lang['ADMIN_ADD_PLAYER_SUCCESS'], $mb['character']['name'], date('F j, Y, g:i a')),
					'player_joindate'     => time(),
					'player_outdate'      => mktime(0, 0, 0, 12, 31, 2030),
					'player_guild_id'     => (int) $guild_id,
					'player_gender_id'    => (int) $mb['character']['gender'],
					'player_achiev'       => (int) $mb['character']['achievementPoints'],
					'player_armory_url'   => $armory_url,
					'phpbb_user_id'       => 0,
					'game_id'             => 'wow',
					'player_portrait_url' => (string) $portrait_url,
					'player_title'        => $title,
					'last_update'         => time(),
				));
				$this->db->sql_query('INSERT INTO ' . $this->bb_players_table . $query);
			}
		}

		// Update existing players
		$to_update = array_intersect($newplayers, $oldplayers);
		foreach ($member_data as $mb)
		{
			if (in_array($mb['character']['name'] . '-' . $mb['character']['realm'], $to_update))
			{
				$realm = isset($mb['character']['realm']) ? $mb['character']['realm'] : 'unknown';
				$player_id = (int) $player_ids[bin2hex($mb['character']['name'] . '-' . $mb['character']['realm'])];

				$sql_ary = array(
					'player_name'         => ucwords($mb['character']['name']),
					'player_level'        => (int) $mb['character']['level'],
					'player_race_id'      => (int) $mb['character']['race'],
					'player_realm'        => $mb['character']['realm'],
					'player_region'       => $region,
					'player_class_id'     => (int) $mb['character']['class'],
					'player_rank_id'      => (int) $mb['rank'],
					'player_guild_id'     => (int) $guild_id,
					'player_gender_id'    => (int) $mb['character']['gender'],
					'player_achiev'       => (int) $mb['character']['achievementPoints'],
					'player_armory_url'   => sprintf('http://%s.battle.net/wow/en/', $region) . 'character/' . $realm . '/' . $mb['character']['name'] . '/simple',
					'player_portrait_url' => sprintf('http://%s.battle.net/static-render/%s/', $region, $region) . $mb['character']['thumbnail'],
				);

				$sql = 'UPDATE ' . $this->bb_players_table . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE player_id = ' . $player_id;
				$this->db->sql_query($sql);
			}
		}

		$this->db->sql_transaction('commit');
	}

	/**
	 * @inheritdoc
	 */
	public function requires_api_key(): bool
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function save_guild_extension(int $guild_id, array $processed): void
	{
		$row = array(
			'guild_id'           => $guild_id,
			'battlegroup'        => isset($processed['battlegroup']) ? $processed['battlegroup'] : '',
			'level'              => isset($processed['level']) ? $processed['level'] : 0,
			'achievementpoints'  => isset($processed['achievementpoints']) ? $processed['achievementpoints'] : 0,
			'guildarmoryurl'     => isset($processed['guildarmoryurl']) ? $processed['guildarmoryurl'] : '',
		);

		// Check if row exists
		$sql = 'SELECT guild_id FROM ' . $this->guild_wow_table . ' WHERE guild_id = ' . (int) $guild_id;
		$result = $this->db->sql_query($sql);
		$exists = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if ($exists)
		{
			$update = $row;
			unset($update['guild_id']);
			$query = $this->db->sql_build_array('UPDATE', $update);
			$this->db->sql_query('UPDATE ' . $this->guild_wow_table . ' SET ' . $query . ' WHERE guild_id = ' . (int) $guild_id);
		}
		else
		{
			$query = $this->db->sql_build_array('INSERT', $row);
			$this->db->sql_query('INSERT INTO ' . $this->guild_wow_table . $query);
		}
	}

	/**
	 * Create a WoW Guild emblem image from Battle.net emblem data.
	 *
	 * Adapted for phpBB from http://us.battle.net/wow/en/forum/topic/3082248497#8
	 *
	 * @author    Thomas Andersen <acoon@acoon.dk>
	 * @copyright Copyright (c) 2011, Thomas Andersen, http://sourceforge.net/projects/wowarmoryapi
	 * @param array  $emblem_data Emblem data array from Battle.net API
	 * @param int    $faction     Guild faction (1=Alliance, 2=Horde)
	 * @param string $guild_name  Guild name
	 * @param string $realm       Realm name
	 * @param string $region      Region code
	 * @param int    $width       Output image width
	 * @return string Path to generated emblem image
	 */
	private function create_emblem($emblem_data, $faction, $guild_name, $realm, $region, $width = 175)
	{
		global $phpbb_container;
		$ext_path = $this->get_ext_path($phpbb_container);

		$safe_name = $this->mb_str_replace(' ', '_', $guild_name);
		$imgfile = $ext_path . 'images/guildemblem/' . $region . '_' . $realm . '_' . $safe_name . '.png';
		$outputpath = $imgfile;

		if (file_exists($imgfile) and $width == imagesx(imagecreatefrompng($imgfile)) and (filemtime($imgfile) + 86000) > time())
		{
			$finalimg = imagecreatefrompng($imgfile);
			imagesavealpha($finalimg, true);
			imagealphablending($finalimg, true);
		}
		else
		{
			if ($width > 1 and $width < 215)
			{
				$height = ($width / 215) * 230;
				$finalimg = imagecreatetruecolor($width, $height);
				$trans_colour = imagecolorallocatealpha($finalimg, 0, 0, 0, 127);
				imagefill($finalimg, 0, 0, $trans_colour);
				imagesavealpha($finalimg, true);
				imagealphablending($finalimg, true);
			}

			$ring_name = ($faction == 1) ? 'alliance' : 'horde';

			$imgOut = imagecreatetruecolor(215, 230);

			$emblemURL = $ext_path . 'images/wowapi/emblems/emblem_' . sprintf('%02s', $emblem_data['icon']) . '.png';
			$borderURL = $ext_path . 'images/wowapi/borders/border_' . sprintf('%02s', $emblem_data['border']) . '.png';
			$ringURL = $ext_path . 'images/wowapi/static/ring-' . $ring_name . '.png';
			$shadowURL = $ext_path . 'images/wowapi/static/shadow_00.png';
			$bgURL = $ext_path . 'images/wowapi/static/bg_00.png';
			$overlayURL = $ext_path . 'images/wowapi/static/overlay_00.png';
			$hooksURL = $ext_path . 'images/wowapi/static/hooks.png';

			imagesavealpha($imgOut, true);
			imagealphablending($imgOut, true);
			$trans_colour = imagecolorallocatealpha($imgOut, 0, 0, 0, 127);
			imagefill($imgOut, 0, 0, $trans_colour);

			$ring = imagecreatefrompng($ringURL);
			$ring_size = getimagesize($ringURL);

			$emblem = imagecreatefrompng($emblemURL);
			$emblem_size = getimagesize($emblemURL);
			imagelayereffect($emblem, IMG_EFFECT_OVERLAY);
			$emblemcolor = preg_replace('/^ff/i', '', $emblem_data['iconColor']);
			$color_r = hexdec(substr($emblemcolor, 0, 2));
			$color_g = hexdec(substr($emblemcolor, 2, 2));
			$color_b = hexdec(substr($emblemcolor, 4, 2));
			imagefilledrectangle($emblem, 0, 0, $emblem_size[0], $emblem_size[1], imagecolorallocatealpha($emblem, $color_r, $color_g, $color_b, 0));

			$border = imagecreatefrompng($borderURL);
			$border_size = getimagesize($borderURL);
			imagelayereffect($border, IMG_EFFECT_OVERLAY);
			$bordercolor = preg_replace('/^ff/i', '', $emblem_data['borderColor']);
			$color_r = hexdec(substr($bordercolor, 0, 2));
			$color_g = hexdec(substr($bordercolor, 2, 2));
			$color_b = hexdec(substr($bordercolor, 4, 2));
			imagefilledrectangle($border, 0, 0, $border_size[0] + 100, $border_size[0] + 100, imagecolorallocatealpha($border, $color_r, $color_g, $color_b, 0));

			$shadow = imagecreatefrompng($shadowURL);

			$bg = imagecreatefrompng($bgURL);
			$bg_size = getimagesize($bgURL);
			imagelayereffect($bg, IMG_EFFECT_OVERLAY);
			$bgcolor = preg_replace('/^ff/i', '', $emblem_data['backgroundColor']);
			$color_r = hexdec(substr($bgcolor, 0, 2));
			$color_g = hexdec(substr($bgcolor, 2, 2));
			$color_b = hexdec(substr($bgcolor, 4, 2));
			imagefilledrectangle($bg, 0, 0, $bg_size[0] + 100, $bg_size[0] + 100, imagecolorallocatealpha($bg, $color_r, $color_g, $color_b, 0));

			$overlay = imagecreatefrompng($overlayURL);
			$hooks = imagecreatefrompng($hooksURL);

			$x = 20;
			$y = 23;

			imagecopy($imgOut, $ring, 0, 0, 0, 0, $ring_size[0], $ring_size[1]);

			$size = getimagesize($shadowURL);
			imagecopy($imgOut, $shadow, $x, $y, 0, 0, $size[0], $size[1]);
			imagecopy($imgOut, $bg, $x, $y, 0, 0, $bg_size[0], $bg_size[1]);
			imagecopy($imgOut, $emblem, $x + 17, $y + 30, 0, 0, $emblem_size[0], $emblem_size[1]);
			imagecopy($imgOut, $border, $x + 13, $y + 15, 0, 0, $border_size[0], $border_size[1]);
			$size = getimagesize($overlayURL);
			imagecopy($imgOut, $overlay, $x, $y + 2, 0, 0, $size[0], $size[1]);
			$size = getimagesize($hooksURL);
			imagecopy($imgOut, $hooks, $x - 2, $y, 0, 0, $size[0], $size[1]);

			if ($width > 1 and $width < 215)
			{
				imagecopyresampled($finalimg, $imgOut, 0, 0, 0, 0, $width, $height, 215, 230);
			}
			else
			{
				$finalimg = $imgOut;
			}

			imagepng($finalimg, $imgfile);
		}

		return $outputpath;
	}

	/**
	 * Replace string in UTF-8 string.
	 *
	 * @param string $needle
	 * @param string $replacement
	 * @param string $haystack
	 * @return string
	 */
	private function mb_str_replace($needle, $replacement, $haystack)
	{
		$needle_len = mb_strlen($needle);
		$pos = mb_strpos($haystack, $needle);
		while (!($pos === false))
		{
			$front = mb_substr($haystack, 0, $pos);
			$back = mb_substr($haystack, $pos + $needle_len);
			$haystack = $front . $replacement . $back;
			$pos = mb_strpos($haystack, $needle);
		}
		return $haystack;
	}

	/**
	 * Load WoW game record from the database to get API credentials.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
	 * @return \avathar\bbguild\model\games\game|null
	 */
	private function get_game_from_db($container)
	{
		try
		{
			$game = new \avathar\bbguild\model\games\game(
				$container->getParameter('avathar.bbguild.tables.bb_classes'),
				$container->getParameter('avathar.bbguild.tables.bb_races'),
				$container->getParameter('avathar.bbguild.tables.bb_language'),
				$container->getParameter('avathar.bbguild.tables.bb_factions'),
				$container->getParameter('avathar.bbguild.tables.bb_games')
			);
			$game->game_id = 'wow';
			$game->get_game();
			return $game;
		}
		catch (\Exception $e)
		{
			return null;
		}
	}

	/**
	 * Get the bbGuild core extension path.
	 *
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
	 * @return string
	 */
	private function get_ext_path($container)
	{
		$ext_manager = $container->get('ext.manager');
		return $ext_manager->get_extension_path('avathar/bbguild', true);
	}
}
