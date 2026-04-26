<?php
/**
 * bbGuild WoW Extension — seed specializations for existing installs (#331)
 *
 * For installs that enabled the WoW game before bbguildwow shipped the
 * specialization catalog, this migration backfills bb_specializations
 * with the WoW spec rows from wow_provider::spec_catalog(). Idempotent:
 * skips inserts when rows already exist for game_id='wow'.
 *
 * @package   avathar\bbguildwow
 * @copyright 2026 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguildwow\migrations\v200b3;

use avathar\bbguildwow\game\wow_provider;

class seed_specializations extends \phpbb\db\migration\migration
{
	public static function depends_on()
	{
		return [
			'\avathar\bbguildwow\migrations\v200b3\add_player_render_url',
			'\avathar\bbguild\migrations\v200b4\release_2_0_0_b4',
		];
	}

	public function effectively_installed()
	{
		// If any rows already exist for wow, treat the seed as done.
		$specs_table = $this->table_prefix . 'bb_specializations';
		$sql = 'SELECT 1 FROM ' . $specs_table . " WHERE game_id = 'wow' LIMIT 1";
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		return (bool) $row;
	}

	public function update_data()
	{
		return [
			['custom', [[$this, 'seed_wow_specs']]],
		];
	}

	public function revert_data()
	{
		return [
			['custom', [[$this, 'remove_wow_specs']]],
		];
	}

	public function seed_wow_specs()
	{
		// Skip seeding if the WoW game isn't installed in bb_games.
		// (Plugin can be enabled without the game being installed yet —
		// in that case, install_specs() will run when the game is added.)
		$games_table = $this->table_prefix . 'bb_games';
		$sql = 'SELECT 1 FROM ' . $games_table . " WHERE game_id = 'wow' LIMIT 1";
		$result = $this->db->sql_query($sql);
		$exists = (bool) $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		if (!$exists)
		{
			return;
		}

		$specs_table = $this->table_prefix . 'bb_specializations';
		$rows = [];
		foreach (wow_provider::spec_catalog() as $class_id => $specs)
		{
			foreach ($specs as $spec)
			{
				$rows[] = [
					'game_id'    => 'wow',
					'class_id'   => (int) $class_id,
					'role_id'    => (int) $spec['role_id'],
					'spec_name'  => (string) $spec['spec_name'],
					'spec_icon'  => (string) $spec['spec_icon'],
					'spec_order' => (int) $spec['spec_order'],
				];
			}
		}
		if ($rows)
		{
			$this->db->sql_multi_insert($specs_table, $rows);
		}
	}

	public function remove_wow_specs()
	{
		$specs_table = $this->table_prefix . 'bb_specializations';
		$this->db->sql_query('DELETE FROM ' . $specs_table . " WHERE game_id = 'wow'");
	}
}
