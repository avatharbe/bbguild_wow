<?php
/**
 * bbguildwow ACP language file for Battle.net API (NL)
 *
 * @package   phpBB Extension - bbguildwow
 * @copyright 2018 avathar.be
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge(
	$lang, array(
	'ACP_WOW_BATTLENET'            => 'Battle.net API',
	'ACP_WOW_BATTLENET_EXPLAIN'    => 'Beheer de Battle.net OAuth 2.0 API-verbinding voor World of Warcraft. API-referenties worden geconfigureerd in <em>Spellen &gt; World of Warcraft bewerken</em>.',

	'WOW_BNET_CREDENTIALS'         => 'API-referenties',
	'WOW_BNET_NOT_SET'             => 'Niet geconfigureerd',
	'WOW_BNET_EDIT_CREDENTIALS'    => 'WoW-spelinstellingen bewerken',

	'WOW_BNET_TEST_CONNECTION'     => 'Verbinding testen',
	'WOW_BNET_TEST_EXPLAIN'        => 'Test de OAuth 2.0 token-uitwisseling met Battle.net om te controleren of je Client-ID en Client-secret geldig zijn.',
	'WOW_BNET_TEST_REGION'         => 'Regio',
	'WOW_BNET_TEST_NOW'            => 'Verbinding testen',
	'WOW_BNET_STATUS_OK'           => 'Verbinding geslaagd',
	'WOW_BNET_STATUS_FAIL'         => 'Verbinding mislukt',
	'WOW_BNET_TEST_SUCCESS'        => 'OAuth-token succesvol verkregen voor regio %s. Token verloopt over %s uur.',
	'WOW_BNET_NO_CREDENTIALS'      => 'Client-ID en Client-secret moeten geconfigureerd zijn voordat er getest kan worden.',
	'WOW_BNET_CURL_FAILED'         => 'Kan cURL niet initialiseren.',
	'WOW_BNET_CURL_ERROR'          => 'cURL-fout: %s',
	'WOW_BNET_AUTH_FAILED'         => 'Authenticatie mislukt (HTTP %d): %s',
	'WOW_BNET_NO_TOKEN'            => 'Battle.net heeft geantwoord maar geen toegangstoken verstrekt.',

	'WOW_BNET_TOKEN_CACHE'         => 'Token-cache',
	'WOW_BNET_TOKEN_CACHE_EXPLAIN' => 'OAuth-tokens worden per regio gecachet om onnodige tokenverzoeken te voorkomen. Tokens verlopen doorgaans na 24 uur.',
	'WOW_BNET_REGION'              => 'Regio',
	'WOW_BNET_TOKEN_STATUS'        => 'Status',
	'WOW_BNET_TOKEN_PREVIEW'       => 'Token',
	'WOW_BNET_CACHED'              => 'Gecachet',
	'WOW_BNET_NOT_CACHED'          => 'Geen token',
	'WOW_BNET_CLEAR_CACHE'         => 'Alle gecachte tokens wissen',
	'WOW_BNET_CACHE_CLEARED'       => 'Alle gecachte OAuth-tokens zijn gewist.',
));
