<?php
/*
  Valores:
  - Las rutas NO finalizan en /
  - Las URL NO finalizan en /
*/



define( 'APP_TMP_PATH', APP_BASE_PATH.'/tmp' ); // Ficheros temporales

$conf->setSetupValue( 'setup:cogumeloPath', COGUMELO_LOCATION );
if( defined('COGUMELO_DIST_LOCATION') && COGUMELO_DIST_LOCATION !== false ) {
  $conf->setSetupValue( 'setup:cogumeloDistPath', COGUMELO_DIST_LOCATION );
}

$conf->setSetupValue( 'setup:webBasePath', WEB_BASE_PATH ); // Apache DocumentRoot
$conf->setSetupValue( 'setup:prjBasePath', PRJ_BASE_PATH ); // Project Path (normalmente contiene app/ httpdocs/ formFiles/)
$conf->setSetupValue( 'setup:appBasePath', APP_BASE_PATH ); // App Path
$conf->setSetupValue( 'setup:appTmpPath', APP_TMP_PATH ); // (normalmente 'setup:appBasePath'/tmp)
$conf->setSetupValue( 'setup:isDevelEnv', IS_DEVEL_ENV );



//
//  Url settings
//
// TODO: Cuidado - No se procesa el puerto
define( 'SITE_PROTOCOL', isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' );
define( 'SITE_HOST', SITE_PROTOCOL.'://'.$_SERVER['HTTP_HOST']);  // solo HOST sin ('/')
define( 'SITE_FOLDER', '/' );  // SITE_FOLDER STARTS AND ENDS WITH SLASH ('/')
define( 'SITE_URL', SITE_HOST . SITE_FOLDER );
define( 'SITE_URL_HTTP', 'http://'.$_SERVER['HTTP_HOST'] . SITE_FOLDER );
define( 'SITE_URL_HTTPS', 'https://'.$_SERVER['HTTP_HOST'] . SITE_FOLDER );
define( 'SITE_URL_CURRENT', SITE_PROTOCOL == 'http' ? SITE_URL_HTTP : SITE_URL_HTTPS );
$conf->setSetupValue( 'setup:webBaseUrl', array(
  'protocol' => SITE_PROTOCOL,
  'host' => SITE_HOST,
  'folder' => SITE_FOLDER,
  'url' => SITE_URL,
  'urlHttp' => SITE_URL_HTTP,
  'urlHttps' => SITE_URL_HTTPS,
  'urlCurrent' => SITE_URL_CURRENT
));
if( isset( $_SERVER['SERVER_PORT'] ) ) {
  $conf->setSetupValue( 'setup:webBaseUrl:port', $_SERVER['SERVER_PORT'] );
}
