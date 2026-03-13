<?php
/**
 * bbguild_wow acp language file for achievement (IT)
 *
 * @package   phpBB Extension - bbguild_wow
 * @copyright 2009 bbguild
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    lucasari
 * @link      http://www.avathar.be/bbdkp
 * @version   2.0
 */

/**
 * @ignore
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

// Create the lang array if it does not already exist
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// Merge the following language entries into the lang array
$lang = array_merge(
	$lang, array(
	'ACP_ADDACHIEV'            => 'Imprese di gioco',
	'ACP_LISTACHIEV'           => 'Lista imprese',
	'ACP_MM_EDITACHI'          => 'Modifica impresa',

	'ACHIEV_ADDED'             => 'Impresa aggiunta con successo.',
	'ACHIEV_UPDATED'           => 'Impresa aggiornata con successo.',
	'ACHIEV_DELETED'           => 'Impresa/e eliminata/e con successo.',
	'ACHIEV_FOOTCOUNT'         => '%d imprese trovate.',
	'CONFIRM_DELETE_ACHIEVEMENT' => 'Sei sicuro di voler eliminare l\'impresa "%s"?',
	'WARNING_NOACHIEVEMENTS'   => 'Nessuna impresa trovata. Usa il pulsante API o aggiungi imprese manualmente.',

	'ADD_ACHIEVEMENT'          => 'Aggiungi impresa',
	'ADD_ACHIEVEMENT_MANUAL'   => 'Aggiungi manuale',
	'ADD_ACHIEVEMENT_API'      => 'Carica da API',
	'UPDATE_ACHIEVEMENT'       => 'Aggiorna impresa',
	'DELETE_ACHIEVEMENT'       => 'Elimina impresa',
	'ACHIEVEMENT_ADD_EXPLAIN'  => 'Aggiungi o modifica i dettagli dell\'impresa. Le imprese possono essere importate automaticamente tramite l\'API Battle.net.',
	'ACHI_ID'                  => 'ID impresa',
	'ACHI_TITLE'               => 'Titolo',
	'ACHI_DESC'                => 'Descrizione',
	'ACHI_POINTS'              => 'Punti',

	'FV_REQUIRED_TITLE'        => 'Inserisci un titolo.',
	'FV_REQUIRED_DESCRIPTION'  => 'Inserisci una descrizione.',
	'FV_REQUIRED_ID'           => 'Inserisci un ID impresa.',
	)
);
