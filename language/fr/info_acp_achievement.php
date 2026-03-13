<?php
/**
 * bbguild_wow acp language file for achievement (FR)
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
	'ACP_ADDACHIEV'            => 'Hauts faits',
	'ACP_LISTACHIEV'           => 'Liste des hauts faits',
	'ACP_MM_EDITACHI'          => 'Modifier un haut fait',

	'ACHIEV_ADDED'             => 'Haut fait ajouté avec succès.',
	'ACHIEV_UPDATED'           => 'Haut fait mis à jour avec succès.',
	'ACHIEV_DELETED'           => 'Haut(s) fait(s) supprimé(s) avec succès.',
	'ACHIEV_FOOTCOUNT'         => '%d hauts faits trouvés.',
	'CONFIRM_DELETE_ACHIEVEMENT' => 'Êtes-vous sûr de vouloir supprimer le haut fait "%s" ?',
	'WARNING_NOACHIEVEMENTS'   => 'Aucun haut fait trouvé. Utilisez le bouton API ou ajoutez des hauts faits manuellement.',

	'ADD_ACHIEVEMENT'          => 'Ajouter un haut fait',
	'ADD_ACHIEVEMENT_MANUAL'   => 'Ajout manuel',
	'ADD_ACHIEVEMENT_API'      => 'Charger depuis l\'API',
	'UPDATE_ACHIEVEMENT'       => 'Mettre à jour le haut fait',
	'DELETE_ACHIEVEMENT'       => 'Supprimer le haut fait',
	'ACHIEVEMENT_ADD_EXPLAIN'  => 'Ajouter ou modifier les détails d\'un haut fait. Les hauts faits peuvent être importés automatiquement via l\'API Battle.net.',
	'ACHI_ID'                  => 'ID du haut fait',
	'ACHI_TITLE'               => 'Titre',
	'ACHI_DESC'                => 'Description',
	'ACHI_POINTS'              => 'Points',

	'FV_REQUIRED_TITLE'        => 'Veuillez entrer un titre.',
	'FV_REQUIRED_DESCRIPTION'  => 'Veuillez entrer une description.',
	'FV_REQUIRED_ID'           => 'Veuillez entrer un ID de haut fait.',
	)
);
