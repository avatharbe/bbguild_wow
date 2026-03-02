<?php
/**
 * bbGuild WoW plugin - Guild extension schema migration
 *
 * Creates bb_guild_wow to store WoW-specific guild fields that were
 * previously in the core bb_guild table.
 *
 * @package   avathar\bbguild_wow
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow\migrations\basics;

class guild_schema extends \phpbb\db\migration\migration
{
	protected $guild_wow_table;

	public static function depends_on()
	{
		return ['\phpbb\db\migration\data\v320\v320'];
	}

	public function effectively_installed()
	{
		return $this->db_tools->sql_table_exists($this->table_prefix . 'bb_guild_wow');
	}

	public function update_schema()
	{
		$this->guild_wow_table = $this->table_prefix . 'bb_guild_wow';

		return [
			'add_tables' => [
				$this->guild_wow_table => [
					'COLUMNS' => [
						'guild_id'           => ['USINT', 0],
						'battlegroup'        => ['VCHAR:255', ''],
						'level'              => ['UINT', 0],
						'achievementpoints'  => ['UINT', 0],
						'guildarmoryurl'     => ['VCHAR:255', ''],
					],
					'PRIMARY_KEY' => ['guild_id'],
				],
			],
		];
	}

	public function revert_schema()
	{
		$this->guild_wow_table = $this->table_prefix . 'bb_guild_wow';

		return [
			'drop_tables' => [
				$this->guild_wow_table,
			],
		];
	}
}
