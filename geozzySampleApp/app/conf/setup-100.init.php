<?php
/*
  Normas de estilo:

  Nombres:
  - Inicia por mod:nombreModulo: para configuración de modulos
  - Fuera de módulos, de forma general, usaremos tema:subtema:variable
  - Usar nombres finalizados en "Path" (variablePath) para rutas

  Valores:
  - Las rutas NO finalizan en /
  - Las URL NO finalizan en /


  Llamadas a metodos:

  En ficheros de setup:
  $conf->setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = $conf->getSetupValue( 'mod:nombreModulo:level1:level2' );

  En código cogumelo:
  Cogumelo::setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = Cogumelo::getSetupValue( 'mod:nombreModulo:level1:level2' );
*/



define( 'APP_TMP_PATH', APP_BASE_PATH.'/tmp' ); // Ficheros temporales

$conf->setSetupValue( 'setup:webBasePath', WEB_BASE_PATH ); // Apache DocumentRoot
$conf->setSetupValue( 'setup:prjBasePath', PRJ_BASE_PATH ); // Project Path (normalmente contiene app/ httpdocs/ formFiles/)
$conf->setSetupValue( 'setup:appBasePath', APP_BASE_PATH ); // App Path
$conf->setSetupValue( 'setup:appTmpPath', APP_TMP_PATH ); // (normalmente 'setup:appBasePath'/tmp)
$conf->setSetupValue( 'setup:isDevelEnv', IS_DEVEL_ENV );


//
//  memcached
//
require_once( $conf->getSetupValue( 'setup:appBasePath' ).'/conf/memcached.setup.php' );  //memcached options


//
//  Url settings
//
// TODO: Cuidado porque no se procesa el puerto
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
