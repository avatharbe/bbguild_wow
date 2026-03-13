<?php
/**
 * WoW Guild News (Activity Feed) portal module.
 *
 * Displays the guild's recent activity feed including item loots
 * and achievement completions from the Battle.net API.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\portal\modules;

use avathar\bbguild\portal\modules\module_base;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;

class guild_news extends module_base
{
	protected int $columns = 21; // top + center + bottom
	protected string $name = 'BBGUILD_PORTAL_GUILD_NEWS';
	protected string $image_src = '';
	protected $language = array('vendor' => 'avathar/bbguild_wow', 'file' => 'wow');

	/** @var driver_interface */
	protected driver_interface $db;

	/** @var template */
	protected template $template;

	/** @var string */
	protected string $news_table;

	/** @var string */
	protected string $guild_wow_table;

	public function __construct(
		driver_interface $db,
		template $template,
		string $news_table,
		string $guild_wow_table
	)
	{
		$this->db = $db;
		$this->template = $template;
		$this->news_table = $news_table;
		$this->guild_wow_table = $guild_wow_table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		// Only render if this is a WoW guild
		$sql = 'SELECT guild_id FROM ' . $this->guild_wow_table .
			' WHERE guild_id = ' . (int) $this->guild_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			return null;
		}

		// Fetch recent guild news
		$sql = 'SELECT news_id, news_headline, news_message, news_date,
				bbcode_bitfield, bbcode_uid, bbcode_options
			FROM ' . $this->news_table . '
			WHERE guild_id = ' . (int) $this->guild_id . '
			ORDER BY news_date DESC';
		$result = $this->db->sql_query_limit($sql, 10);

		$has_news = false;
		while ($row = $this->db->sql_fetchrow($result))
		{
			$has_news = true;
			$message = generate_text_for_display(
				$row['news_message'],
				$row['bbcode_uid'],
				$row['bbcode_bitfield'],
				(int) $row['bbcode_options']
			);

			$this->template->assign_block_vars('guild_news', array(
				'HEADLINE' => $row['news_headline'],
				'MESSAGE'  => $message,
				'DATE'     => date('d/m/Y', (int) $row['news_date']),
			));
		}
		$this->db->sql_freeresult($result);

		if (!$has_news)
		{
			return null;
		}

		return '@avathar_bbguild_wow/portal/modules/guild_news_center.html';
	}
}
