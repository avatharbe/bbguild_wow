<?php
/**
 * Battle.net WoW Achievement API
 *
 * @package   bbguild_wow v2.0
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    Andreas Vandenberghe <sajaki@avathar.be>
 * @link      https://develop.battle.net/
 */

namespace avathar\bbguild_wow\api;

/**
 * Achievement resource.
 *
 * @package avathar\bbguild_wow\api
 */
class battlenet_achievement extends battlenet_resource
{
	/** @var array */
	protected $methods_allowed = array('*');

	/** @var string */
	protected $endpoint = 'achievement';

	/**
	 * @param int $id
	 * @return array
	 */
	public function getAchievementDetail($id)
	{
		$data = $this->consume(
			$id, array('*')
		);
		return $data;
	}
}
