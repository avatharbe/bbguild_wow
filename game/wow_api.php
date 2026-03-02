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
use avathar\bbguild\model\player\player;
use avathar\bbguild\model\player\ranks;
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

	/**
	 * @param \phpbb\cache\service $cache
	 */
	public function __construct(\phpbb\cache\service $cache)
	{
		$this->cache = $cache;
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

		// Emblem data
		$result['emblem'] = isset($raw_data['emblem']) ? $raw_data['emblem'] : '';

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
		global $db, $user;

		if (empty($member_data))
		{
			return;
		}

		// Update ranks table
		$rank = new ranks($guild_id);
		$rank->WoWRankFix($member_data, $guild_id);

		// Update player table
		$mb = new player();
		$mb->WoWArmoryUpdate($member_data, $guild_id, $region, $min_level);
	}

	/**
	 * @inheritdoc
	 */
	public function requires_api_key(): bool
	{
		return true;
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
