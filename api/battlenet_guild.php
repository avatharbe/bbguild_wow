<?php
/**
 * Battle.net WoW Guild API
 *
 * @package   bbguild_wow v2.0
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    Andreas Vandenberghe <sajaki@avathar.be>
 * @author    Chris Saylor
 * @author    Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright Copyright (c) 2011, 2015 Chris Saylor, Daniel Cannon, Andreas Vandenberghe
 * @link      https://develop.battle.net/
 */

namespace avathar\bbguild_wow\api;

/**
 * Guild resource.
 *
 * @package avathar\bbguild_wow\api
 */
class battlenet_guild extends battlenet_resource
{
	/** @var array */
	protected $methods_allowed = array('*');

	/** @var array */
	private $extrafields = array('members', 'achievements', 'news');

	/** @var string */
	protected $endpoint = 'guild';

	/**
	 * @return array
	 */
	public function getFields()
	{
		return $this->extrafields;
	}

	/**
	 * Fetch guild results
	 *
	 * @param  string $name
	 * @param  string $realm
	 * @param  array  $fields
	 * @return mixed
	 */
	public function getGuild($name = '', $realm = '', $fields = array())
	{
		global $user;

		if ($name == '')
		{
			trigger_error($user->lang['WOWAPI_NO_GUILD']);
		}

		if ($realm == '')
		{
			trigger_error($user->lang['WOWAPI_NO_REALMS']);
		}

		$realm = str_replace(' ', '%20', $realm);
		$name = str_replace(' ', '%20', $name);

		if (is_array($fields) && count($fields) > 0)
		{
			$field_str = 'fields=' . implode(',', $fields);
			$keys = $this->getFields();
			if (count(array_intersect($fields, $keys)) == 0)
			{
				trigger_error(sprintf($user->lang['WOWAPI_INVALID_FIELD'], $field_str));
			}

			$data = $this->consume(
				$realm . '/' . $name, array(
					'data' => $field_str,
				)
			);
		}
		else
		{
			$data = $this->consume($realm . '/' . $name, $fields);
		}

		return $data;
	}
}
