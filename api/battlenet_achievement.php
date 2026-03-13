<?php
/**
 * Battle.net WoW Achievement API
 *
 * Uses the Game Data API endpoint:
 * - Achievement detail: GET /data/wow/achievement/{achievementId}
 *
 * @package   bbguild_wow v2.0
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    Andreas Vandenberghe <sajaki@avathar.be>
 * @link      https://develop.battle.net/
 */

namespace avathar\bbguild_wow\api;

/**
 * Achievement resource (Game Data API, static namespace).
 *
 * @package avathar\bbguild_wow\api
 */
class battlenet_achievement extends battlenet_resource
{
	/** @var array */
	protected $methods_allowed = array('*');

	/** @var string */
	protected $endpoint = 'data/wow/achievement';

	/**
	 * Fetch achievement detail by ID.
	 *
	 * @param int $id Achievement ID
	 * @return array
	 */
	public function getAchievementDetail(int $id): array
	{
		return $this->consume((string) $id, array());
	}
}
