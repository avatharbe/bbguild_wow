<?php
/**
 * bbguild_wow ACP language file for Battle.net API (DE)
 *
 * @package   phpBB Extension - bbguild_wow
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
	'ACP_WOW_BATTLENET_EXPLAIN'    => 'Verwalte die Battle.net OAuth 2.0 API-Verbindung für World of Warcraft. API-Zugangsdaten werden unter <em>Spiele &gt; World of Warcraft bearbeiten</em> konfiguriert.',

	'WOW_BNET_CREDENTIALS'         => 'API-Zugangsdaten',
	'WOW_BNET_NOT_SET'             => 'Nicht konfiguriert',
	'WOW_BNET_EDIT_CREDENTIALS'    => 'WoW-Spieleinstellungen bearbeiten',

	'WOW_BNET_TEST_CONNECTION'     => 'Verbindung testen',
	'WOW_BNET_TEST_EXPLAIN'        => 'Teste den OAuth 2.0 Token-Austausch mit Battle.net, um zu überprüfen ob deine Client-ID und dein Client-Secret gültig sind.',
	'WOW_BNET_TEST_REGION'         => 'Region',
	'WOW_BNET_TEST_NOW'            => 'Verbindung testen',
	'WOW_BNET_STATUS_OK'           => 'Verbindung erfolgreich',
	'WOW_BNET_STATUS_FAIL'         => 'Verbindung fehlgeschlagen',
	'WOW_BNET_TEST_SUCCESS'        => 'OAuth-Token für Region %s erfolgreich erhalten. Token läuft in %s Stunden ab.',
	'WOW_BNET_NO_CREDENTIALS'      => 'Client-ID und Client-Secret müssen konfiguriert sein, bevor getestet werden kann.',
	'WOW_BNET_CURL_FAILED'         => 'cURL konnte nicht initialisiert werden.',
	'WOW_BNET_CURL_ERROR'          => 'cURL-Fehler: %s',
	'WOW_BNET_AUTH_FAILED'         => 'Authentifizierung fehlgeschlagen (HTTP %d): %s',
	'WOW_BNET_NO_TOKEN'            => 'Battle.net hat geantwortet, aber kein Zugriffstoken bereitgestellt.',

	'WOW_BNET_TOKEN_CACHE'         => 'Token-Cache',
	'WOW_BNET_TOKEN_CACHE_EXPLAIN' => 'OAuth-Tokens werden pro Region zwischengespeichert, um unnötige Token-Anfragen zu vermeiden. Tokens laufen typischerweise nach 24 Stunden ab.',
	'WOW_BNET_REGION'              => 'Region',
	'WOW_BNET_TOKEN_STATUS'        => 'Status',
	'WOW_BNET_TOKEN_PREVIEW'       => 'Token',
	'WOW_BNET_CACHED'              => 'Zwischengespeichert',
	'WOW_BNET_NOT_CACHED'          => 'Kein Token',
	'WOW_BNET_CLEAR_CACHE'         => 'Alle zwischengespeicherten Tokens löschen',
	'WOW_BNET_CACHE_CLEARED'       => 'Alle zwischengespeicherten OAuth-Tokens wurden gelöscht.',
));
