<?php
/**
 * bbguildwow ACP language file for Battle.net API (PL)
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
	'ACP_WOW_BATTLENET'            => 'API Battle.net',
	'ACP_WOW_BATTLENET_EXPLAIN'    => 'Zarządzaj połączeniem API OAuth 2.0 Battle.net dla World of Warcraft. Dane dostępowe API są konfigurowane w <em>Gry &gt; Edytuj World of Warcraft</em>.',

	'WOW_BNET_CREDENTIALS'         => 'Dane dostępowe API',
	'WOW_BNET_NOT_SET'             => 'Nie skonfigurowano',
	'WOW_BNET_EDIT_CREDENTIALS'    => 'Edytuj ustawienia gry WoW',

	'WOW_BNET_TEST_CONNECTION'     => 'Test połączenia',
	'WOW_BNET_TEST_EXPLAIN'        => 'Przetestuj wymianę tokenów OAuth 2.0 z Battle.net, aby zweryfikować poprawność Client ID i Client Secret.',
	'WOW_BNET_TEST_REGION'         => 'Region',
	'WOW_BNET_TEST_NOW'            => 'Testuj połączenie',
	'WOW_BNET_STATUS_OK'           => 'Połączenie udane',
	'WOW_BNET_STATUS_FAIL'         => 'Połączenie nieudane',
	'WOW_BNET_TEST_SUCCESS'        => 'Pomyślnie uzyskano token OAuth dla regionu %s. Token wygasa za %s godzin.',
	'WOW_BNET_NO_CREDENTIALS'      => 'Client ID i Client Secret muszą być skonfigurowane przed testowaniem.',
	'WOW_BNET_CURL_FAILED'         => 'Nie można zainicjować cURL.',
	'WOW_BNET_CURL_ERROR'          => 'Błąd cURL: %s',
	'WOW_BNET_AUTH_FAILED'         => 'Uwierzytelnianie nieudane (HTTP %d): %s',
	'WOW_BNET_NO_TOKEN'            => 'Battle.net odpowiedział, ale nie dostarczył tokenu dostępu.',

	'WOW_BNET_TOKEN_CACHE'         => 'Pamięć podręczna tokenów',
	'WOW_BNET_TOKEN_CACHE_EXPLAIN' => 'Tokeny OAuth są przechowywane w pamięci podręcznej dla każdego regionu, aby uniknąć niepotrzebnych żądań. Tokeny wygasają zazwyczaj po 24 godzinach.',
	'WOW_BNET_REGION'              => 'Region',
	'WOW_BNET_TOKEN_STATUS'        => 'Status',
	'WOW_BNET_TOKEN_PREVIEW'       => 'Token',
	'WOW_BNET_CACHED'              => 'W pamięci podręcznej',
	'WOW_BNET_NOT_CACHED'          => 'Brak tokenu',
	'WOW_BNET_CLEAR_CACHE'         => 'Wyczyść wszystkie zapisane tokeny',
	'WOW_BNET_CACHE_CLEARED'       => 'Wszystkie zapisane tokeny OAuth zostały wyczyszczone.',
));
