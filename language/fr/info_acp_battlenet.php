<?php
/**
 * bbguildwow ACP language file for Battle.net API (FR)
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
	'ACP_WOW_BATTLENET_EXPLAIN'    => 'Gérez la connexion API OAuth 2.0 Battle.net pour World of Warcraft. Les identifiants API sont configurés dans <em>Jeux &gt; Modifier World of Warcraft</em>.',

	'WOW_BNET_CREDENTIALS'         => 'Identifiants API',
	'WOW_BNET_NOT_SET'             => 'Non configuré',
	'WOW_BNET_EDIT_CREDENTIALS'    => 'Modifier les paramètres du jeu WoW',

	'WOW_BNET_TEST_CONNECTION'     => 'Tester la connexion',
	'WOW_BNET_TEST_EXPLAIN'        => 'Testez l\'échange de token OAuth 2.0 avec Battle.net pour vérifier que votre Client ID et Client Secret sont valides.',
	'WOW_BNET_TEST_REGION'         => 'Région',
	'WOW_BNET_TEST_NOW'            => 'Tester la connexion',
	'WOW_BNET_STATUS_OK'           => 'Connexion réussie',
	'WOW_BNET_STATUS_FAIL'         => 'Connexion échouée',
	'WOW_BNET_TEST_SUCCESS'        => 'Token OAuth obtenu avec succès pour la région %s. Le token expire dans %s heures.',
	'WOW_BNET_NO_CREDENTIALS'      => 'Le Client ID et le Client Secret doivent être configurés avant de tester.',
	'WOW_BNET_CURL_FAILED'         => 'Impossible d\'initialiser cURL.',
	'WOW_BNET_CURL_ERROR'          => 'Erreur cURL : %s',
	'WOW_BNET_AUTH_FAILED'         => 'Authentification échouée (HTTP %d) : %s',
	'WOW_BNET_NO_TOKEN'            => 'Battle.net a répondu mais n\'a pas fourni de jeton d\'accès.',

	'WOW_BNET_TOKEN_CACHE'         => 'Cache des tokens',
	'WOW_BNET_TOKEN_CACHE_EXPLAIN' => 'Les tokens OAuth sont mis en cache par région pour éviter les requêtes inutiles. Les tokens expirent généralement après 24 heures.',
	'WOW_BNET_REGION'              => 'Région',
	'WOW_BNET_TOKEN_STATUS'        => 'Statut',
	'WOW_BNET_TOKEN_PREVIEW'       => 'Token',
	'WOW_BNET_CACHED'              => 'En cache',
	'WOW_BNET_NOT_CACHED'          => 'Pas de token',
	'WOW_BNET_CLEAR_CACHE'         => 'Effacer tous les tokens en cache',
	'WOW_BNET_CACHE_CLEARED'       => 'Tous les tokens OAuth en cache ont été effacés.',
));
