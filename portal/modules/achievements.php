<?php
/**
 * WoW Achievements portal module.
 *
 * Displays guild achievement progress overview and recently earned
 * achievements, queried from the local achievement tracking tables.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\portal\modules;

use avathar\bbguild\portal\modules\module_base;
use phpbb\config\config;
use phpbb\db\driver\driver_interface;
use phpbb\template\template;

class achievements extends module_base
{
	protected int $columns = 21; // top + center + bottom
	protected string $name = 'BBGUILD_PORTAL_ACHIEVEMENTS';
	protected string $image_src = '';
	protected $language = array('vendor' => 'avathar/bbguild_wow', 'file' => 'wow');

	/** @var config */
	protected config $config;

	/** @var driver_interface */
	protected driver_interface $db;

	/** @var template */
	protected template $template;

	/** @var string */
	protected string $achievement_table;

	/** @var string */
	protected string $achievement_track_table;

	/** @var string */
	protected string $guild_wow_table;

	public function __construct(
		config $config,
		driver_interface $db,
		template $template,
		string $achievement_table,
		string $achievement_track_table,
		string $guild_wow_table
	)
	{
		$this->config = $config;
		$this->db = $db;
		$this->template = $template;
		$this->achievement_table = $achievement_table;
		$this->achievement_track_table = $achievement_track_table;
		$this->guild_wow_table = $guild_wow_table;
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_template_center(int $module_id)
	{
		// Respect ACP "Show Achievements" toggle
		if (empty($this->config['bbguild_show_achiev']))
		{
			return null;
		}

		// Check if this guild has achievement points
		$sql = 'SELECT achievementpoints FROM ' . $this->guild_wow_table .
			' WHERE guild_id = ' . (int) $this->guild_id;
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row || empty($row['achievementpoints']))
		{
			return null;
		}

		$this->template->assign_var('ACHIEV_POINTS', (int) $row['achievementpoints']);

		// Fetch category progress summary
		$this->load_category_progress();

		// Fetch recently earned achievements
		$this->load_recent_achievements();

		return '@avathar_bbguild_wow/portal/modules/achievements_center.html';
	}

	/**
	 * Load achievement category progress for this guild.
	 */
	protected function load_category_progress(): void
	{
		// Count total achievements and completed achievements per category
		// The bb_achievement table stores all known achievements,
		// bb_achievement_track stores which ones are completed for this guild.
		$sql = 'SELECT COUNT(a.id) AS total_count,
				COUNT(at.achievement_id) AS completed_count
			FROM ' . $this->achievement_table . ' a
			LEFT JOIN ' . $this->achievement_track_table . ' at
				ON a.id = at.achievement_id AND at.guild_id = ' . (int) $this->guild_id . '
			WHERE a.game_id = \'wow\'';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		$total = (int) ($row['total_count'] ?? 0);
		$completed = (int) ($row['completed_count'] ?? 0);
		$pct = $total > 0 ? round(($completed / $total) * 100) : 0;

		$this->template->assign_vars(array(
			'ACHIEV_TOTAL'     => $total,
			'ACHIEV_COMPLETED' => $completed,
			'ACHIEV_PCT'       => $pct,
		));
	}

	/**
	 * Load recently earned achievements (last 5).
	 */
	protected function load_recent_achievements(): void
	{
		$sql = 'SELECT a.title, a.description, a.points, a.icon,
				at.achievements_completed
			FROM ' . $this->achievement_track_table . ' at
			INNER JOIN ' . $this->achievement_table . ' a
				ON a.id = at.achievement_id
			WHERE at.guild_id = ' . (int) $this->guild_id . '
				AND at.achievements_completed > 0
			ORDER BY at.achievements_completed DESC';
		$result = $this->db->sql_query_limit($sql, 5);

		while ($row = $this->db->sql_fetchrow($result))
		{
			$timestamp = (int) $row['achievements_completed'];
			// Battle.net timestamps are in milliseconds
			if ($timestamp > 9999999999)
			{
				$timestamp = (int) ($timestamp / 1000);
			}

			$this->template->assign_block_vars('recent_achievements', array(
				'TITLE'       => $row['title'],
				'DESCRIPTION' => $row['description'],
				'POINTS'      => (int) $row['points'],
				'ICON'        => $row['icon'],
				'DATE'        => date('d/m/Y', $timestamp),
			));
		}
		$this->db->sql_freeresult($result);
	}
}
