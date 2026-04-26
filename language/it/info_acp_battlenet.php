<?php
/**
 * bbguildwow ACP language file for Battle.net API (IT)
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
	'ACP_WOW_BATTLENET_EXPLAIN'    => 'Gestisci la connessione API OAuth 2.0 di Battle.net per World of Warcraft. Le credenziali API sono configurate in <em>Giochi &gt; Modifica World of Warcraft</em>.',

	'WOW_BNET_CREDENTIALS'         => 'Credenziali API',
	'WOW_BNET_NOT_SET'             => 'Non configurato',
	'WOW_BNET_EDIT_CREDENTIALS'    => 'Modifica impostazioni gioco WoW',

	'WOW_BNET_TEST_CONNECTION'     => 'Test connessione',
	'WOW_BNET_TEST_EXPLAIN'        => 'Testa lo scambio di token OAuth 2.0 con Battle.net per verificare che il tuo Client ID e Client Secret siano validi.',
	'WOW_BNET_TEST_REGION'         => 'Regione',
	'WOW_BNET_TEST_NOW'            => 'Test connessione',
	'WOW_BNET_STATUS_OK'           => 'Connessione riuscita',
	'WOW_BNET_STATUS_FAIL'         => 'Connessione fallita',
	'WOW_BNET_TEST_SUCCESS'        => 'Token OAuth ottenuto con successo per la regione %s. Il token scade tra %s ore.',
	'WOW_BNET_NO_CREDENTIALS'      => 'Client ID e Client Secret devono essere configurati prima del test.',
	'WOW_BNET_CURL_FAILED'         => 'Impossibile inizializzare cURL.',
	'WOW_BNET_CURL_ERROR'          => 'Errore cURL: %s',
	'WOW_BNET_AUTH_FAILED'         => 'Autenticazione fallita (HTTP %d): %s',
	'WOW_BNET_NO_TOKEN'            => 'Battle.net ha risposto ma non ha fornito un token di accesso.',

	'WOW_BNET_TOKEN_CACHE'         => 'Cache dei token',
	'WOW_BNET_TOKEN_CACHE_EXPLAIN' => 'I token OAuth sono memorizzati nella cache per regione per evitare richieste non necessarie. I token scadono generalmente dopo 24 ore.',
	'WOW_BNET_REGION'              => 'Regione',
	'WOW_BNET_TOKEN_STATUS'        => 'Stato',
	'WOW_BNET_TOKEN_PREVIEW'       => 'Token',
	'WOW_BNET_CACHED'              => 'In cache',
	'WOW_BNET_NOT_CACHED'          => 'Nessun token',
	'WOW_BNET_CLEAR_CACHE'         => 'Cancella tutti i token in cache',
	'WOW_BNET_CACHE_CLEARED'       => 'Tutti i token OAuth in cache sono stati cancellati.',
));
