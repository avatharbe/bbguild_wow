<?php
/**
 * bbguild_wow ACP language file for Battle.net API (ES)
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
	'ACP_WOW_BATTLENET'            => 'API Battle.net',
	'ACP_WOW_BATTLENET_EXPLAIN'    => 'Gestiona la conexión API OAuth 2.0 de Battle.net para World of Warcraft. Las credenciales API se configuran en <em>Juegos &gt; Editar World of Warcraft</em>.',

	'WOW_BNET_CREDENTIALS'         => 'Credenciales API',
	'WOW_BNET_NOT_SET'             => 'No configurado',
	'WOW_BNET_EDIT_CREDENTIALS'    => 'Editar configuración del juego WoW',

	'WOW_BNET_TEST_CONNECTION'     => 'Probar conexión',
	'WOW_BNET_TEST_EXPLAIN'        => 'Prueba el intercambio de tokens OAuth 2.0 con Battle.net para verificar que tu Client ID y Client Secret son válidos.',
	'WOW_BNET_TEST_REGION'         => 'Región',
	'WOW_BNET_TEST_NOW'            => 'Probar conexión',
	'WOW_BNET_STATUS_OK'           => 'Conexión exitosa',
	'WOW_BNET_STATUS_FAIL'         => 'Conexión fallida',
	'WOW_BNET_TEST_SUCCESS'        => 'Token OAuth obtenido exitosamente para la región %s. El token expira en %s horas.',
	'WOW_BNET_NO_CREDENTIALS'      => 'El Client ID y el Client Secret deben estar configurados antes de probar.',
	'WOW_BNET_CURL_FAILED'         => 'No se pudo inicializar cURL.',
	'WOW_BNET_CURL_ERROR'          => 'Error de cURL: %s',
	'WOW_BNET_AUTH_FAILED'         => 'Autenticación fallida (HTTP %d): %s',
	'WOW_BNET_NO_TOKEN'            => 'Battle.net respondió pero no proporcionó un token de acceso.',

	'WOW_BNET_TOKEN_CACHE'         => 'Caché de tokens',
	'WOW_BNET_TOKEN_CACHE_EXPLAIN' => 'Los tokens OAuth se almacenan en caché por región para evitar solicitudes innecesarias. Los tokens expiran generalmente después de 24 horas.',
	'WOW_BNET_REGION'              => 'Región',
	'WOW_BNET_TOKEN_STATUS'        => 'Estado',
	'WOW_BNET_TOKEN_PREVIEW'       => 'Token',
	'WOW_BNET_CACHED'              => 'En caché',
	'WOW_BNET_NOT_CACHED'          => 'Sin token',
	'WOW_BNET_CLEAR_CACHE'         => 'Borrar todos los tokens en caché',
	'WOW_BNET_CACHE_CLEARED'       => 'Todos los tokens OAuth en caché han sido borrados.',
));
