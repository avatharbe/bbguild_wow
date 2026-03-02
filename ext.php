<?php
/**
 * bbGuild WoW Extension
 *
 * @package   bbguild_wow v2.0
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

namespace avathar\bbguild_wow;

use phpbb\extension\base;

/**
 * Class ext
 *
 * @package avathar\bbguild_wow
 */
class ext extends base
{
	/**
	 * Check whether or not the extension can be enabled.
	 * Requires bbGuild core extension to be enabled.
	 *
	 * @return bool
	 */
	public function is_enableable()
	{
		$ext_manager = $this->container->get('ext.manager');
		return $ext_manager->is_enabled('avathar/bbguild');
	}
}
