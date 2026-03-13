<?php
/**
 * bbguild_wow acp language file for achievement (DE)
 *
 * @package   phpBB Extension - bbguild_wow
 * @copyright 2009 bbguild
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 * @author    sajaki
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
	'ACP_ADDACHIEV'            => 'Spielerfolge',
	'ACP_LISTACHIEV'           => 'Erfolgsliste',
	'ACP_MM_EDITACHI'          => 'Erfolg bearbeiten',

	'ACHIEV_ADDED'             => 'Erfolg erfolgreich hinzugefügt.',
	'ACHIEV_UPDATED'           => 'Erfolg erfolgreich aktualisiert.',
	'ACHIEV_DELETED'           => 'Erfolg(e) erfolgreich gelöscht.',
	'ACHIEV_FOOTCOUNT'         => '%d Erfolge gefunden.',
	'CONFIRM_DELETE_ACHIEVEMENT' => 'Bist du sicher, dass du den Erfolg "%s" löschen möchtest?',
	'WARNING_NOACHIEVEMENTS'   => 'Keine Erfolge gefunden. Verwende die API-Schaltfläche oder füge Erfolge manuell hinzu.',

	'ADD_ACHIEVEMENT'          => 'Erfolg hinzufügen',
	'ADD_ACHIEVEMENT_MANUAL'   => 'Manuell hinzufügen',
	'ADD_ACHIEVEMENT_API'      => 'Von API laden',
	'UPDATE_ACHIEVEMENT'       => 'Erfolg aktualisieren',
	'DELETE_ACHIEVEMENT'       => 'Erfolg löschen',
	'ACHIEVEMENT_ADD_EXPLAIN'  => 'Erfolgsdetails hinzufügen oder bearbeiten. Erfolge können auch automatisch über die Battle.net API befüllt werden.',
	'ACHI_ID'                  => 'Erfolgs-ID',
	'ACHI_TITLE'               => 'Titel',
	'ACHI_DESC'                => 'Beschreibung',
	'ACHI_POINTS'              => 'Punkte',

	'FV_REQUIRED_TITLE'        => 'Bitte einen Titel eingeben.',
	'FV_REQUIRED_DESCRIPTION'  => 'Bitte eine Beschreibung eingeben.',
	'FV_REQUIRED_ID'           => 'Bitte eine Erfolgs-ID eingeben.',
	)
);
