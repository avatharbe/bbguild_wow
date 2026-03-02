<?php
/**
 * Battle.net WoW API PHP SDK
 *
 * @package   bbguild_wow v2.0
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    Andreas Vandenberghe <sajaki@avathar.be>
 * @author    Chris Saylor
 * @author    Daniel Cannon <daniel@danielcannon.co.uk>
 * @copyright Copyright (c) 2011, 2015 Chris Saylor, Daniel Cannon, Andreas Vandenberghe
 * @link      https://dev.battle.net/
 */

namespace avathar\bbguild_wow\api;

use avathar\bbguild\model\admin\curl;

/**
 * Resource skeleton
 *
 * @package avathar\bbguild_wow\api
 */
abstract class battlenet_resource extends curl
{
	/**
	 * List of region urls
	 *
	 * @var array
	 */
	protected $api_url = array(
		'eu'  => 'https://eu.api.battle.net/wow/',
		'us'  => 'https://us.api.battle.net/wow/',
		'kr'  => 'https://kr.api.battle.net/wow/',
		'tw'  => 'https://tw.api.battle.net/wow/',
		'sea' => 'https://us.api.battle.net/wow/',
	);

	/**
	 * List of possible locales
	 *
	 * @var array
	 */
	protected $locales_allowed = array(
		'eu'  => array('en_GB', 'de_DE', 'es_ES', 'fr_FR', 'it_IT', 'pl_PL', 'pt_PT', 'ru_RU'),
		'us'  => array('en_US', 'es_MX', 'pt_BR'),
		'kr'  => array('ko_KR'),
		'tw'  => array('zh_TW'),
		'sea' => array('en_US'),
	);

	/** @var string */
	public $region;

	/** @var string */
	public $locale;

	/** @var string */
	public $apikey;

	/** @var string */
	public $privkey;

	/** @var array */
	protected $methods_allowed;

	/** @var int */
	private $cacheTtl;

	/** @var \phpbb\cache\service */
	public $cache;

	/** @var string */
	protected $endpoint;

	/**
	 * @param \phpbb\cache\service $cache
	 * @param string               $region
	 * @param int                  $cacheTtl
	 */
	public function __construct(\phpbb\cache\service $cache, $region = 'us', $cacheTtl = 3600)
	{
		global $user;

		parent::__construct();

		if (empty($this->methods_allowed))
		{
			trigger_error($user->lang['NO_METHODS']);
		}
		$this->region = $region;
		$this->cache = $cache;
		$this->cacheTtl = $cacheTtl;
	}

	/**
	 * Consumes the resource by method and returns the results of the request.
	 *
	 * @param  string $method Request method
	 * @param  array  $params Parameters
	 * @return array Request data
	 */
	public function consume($method, array $params)
	{
		global $user;

		if ($this->apikey == '')
		{
			trigger_error($user->lang['WOWAPI_KEY_MISSING']);
		}

		if (!isset($this->locales_allowed[$this->region]) || !in_array($this->locale, $this->locales_allowed[$this->region]))
		{
			if ($this->region != '' && isset($this->locales_allowed[$this->region]))
			{
				$this->locale = $this->locales_allowed[$this->region][0];
			}
			else
			{
				trigger_error(sprintf($user->lang['WOWAPI_LOCALE_NOTALLOWED'], (string) $this->locale));
			}
		}

		if (!in_array($method, $this->methods_allowed) && !in_array('*', $this->methods_allowed))
		{
			trigger_error($user->lang['WOWAPI_METH_NOTALLOWED']);
		}

		if (!isset($this->api_url[$this->region]))
		{
			trigger_error(sprintf($user->lang['WOWAPI_LOCALE_NOTALLOWED'], (string) $this->region));
		}
		$requestUri = $this->api_url[$this->region];
		$requestUri .= $this->endpoint . '/' . $method;

		$requestUri .= '?locale=' . $this->locale;

		if (isset($params['data']) && !empty($params['data']))
		{
			if (is_array($params['data']))
			{
				$requestUri .= http_build_query($params['data']);
			}
			else
			{
				$requestUri .= '&' . $params['data'];
			}
		}

		$requestUri .= '&apikey=' . $this->apikey;
		$cachesignature = base64_encode($requestUri);

		if (!$data = $this->_getCachedResult($cachesignature))
		{
			$date = date('D, d M Y G:i:s T', time());
			$string_to_sign = "GET\n" . $date . "\n" . $requestUri . "\n";
			$signature = base64_encode(hash_hmac('sha1', $string_to_sign, $this->privkey, true));
			$header = array('Host: ' . $this->region, 'Date: ' . $date, 'Authorization: BNET ' . $this->apikey . ':' . $signature);
			$data = $this->curl($requestUri, $header, false, true);
			$this->cache->put($cachesignature, $data, $this->cacheTtl);
		}

		return $data;
	}

	/**
	 * @param string $cachesignature
	 * @return bool|mixed
	 */
	protected function _getCachedResult($cachesignature)
	{
		if (!$this->cache->get($cachesignature))
		{
			return false;
		}
		return $this->cache->get($cachesignature);
	}
}
