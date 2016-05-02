<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  APP_BASE_PATH - App Path (en index.php)
  SITE_PATH     - App Path (en index.php)
  IS_DEVEL_ENV  - Indica si estamos en el entorno de desarrollo (en setup.php)


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
  cogumeloSetSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = cogumeloGetSetupValue( 'mod:nombreModulo:level1:level2' );

  En código cogumelo:
  Cogumelo::setSetupValue( 'mod:nombreModulo:level1:level2', $value );
  $value = Cogumelo::getSetupValue( 'mod:nombreModulo:level1:level2' );
*/


// Framework Path
//
define( 'COGUMELO_LOCATION', '/home/proxectos/cogumelo' );
define( 'COGUMELO_DIST_LOCATION', '/home/proxectos/geozzy');

//  APP
//
define( 'APP_TMP_PATH', APP_BASE_PATH.'/tmp' );

// DB
//
cogumeloSetSetupValue( 'db', array(
  'engine' => 'mysql',
  'hostname' => 'localhost',
  'port' => '3306',
  'user' => 'galiciaagochada',
  'password' => 'q7w8e9r',
  'name' => 'galiciaagochada',
  'mysqlGroupconcatMaxLen' => 4294967295,
  'allowCache' => true
));

// Url settings
// TODO: No se admite un puerto?
define( 'COGUMELO_ADMINSCRIPT_URL', 'http://galiciaagochada/cogumelo-server.php');

// Media server
//
cogumeloSetSetupValue( 'mod:mediaserver', array(
  'productionMode' => false, // If true, you must compile less manually with ./cogumelo generateClientCaches
  'notCacheJs' => true,
  'host' => '/', // Ej: '/' o 'http://media.galiciaagochada:84/'
  'path' => 'media',
  'cachePath' => 'mediaCache',
  'tmpCachePath' => APP_TMP_PATH.'/mediaCache',
  'minimifyFiles' => false // for js and css files ( only when MEDIASERVER_PRODUCTION_MODE is true)
));

//PORTO
/*
if( cogumeloGetSetupValue('mod:mediaserver:productionMode') ) {
  cogumeloSetSetupValue( 'mod:mediaserver:notCacheJs', false );
  cogumeloSetSetupValue( 'mod:mediaserver:host', 'http://media.galiciaagochada/' );
}
else {
  cogumeloSetSetupValue( 'mod:mediaserver:notCacheJs', true );
  cogumeloSetSetupValue( 'mod:mediaserver:host', '/' );
}
*/

// A eliminar:
define( 'MEDIASERVER_PRODUCTION_MODE', cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) );
define( 'MEDIASERVER_NOT_CACHE_JS', cogumeloGetSetupValue( 'mod:mediaserver:notCacheJs' ) );
define( 'MEDIASERVER_HOST', cogumeloGetSetupValue( 'mod:mediaserver:host' ) );
define( 'MOD_MEDIASERVER_URL_DIR', cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
define( 'MEDIASERVER_FINAL_CACHE_PATH', cogumeloGetSetupValue( 'mod:mediaserver:cachePath' ) );
define( 'MEDIASERVER_TMP_CACHE_PATH', cogumeloGetSetupValue( 'mod:mediaserver:tmpCachePath' ) );
define( 'MEDIASERVER_MINIMIFY_FILES', cogumeloGetSetupValue( 'mod:mediaserver:minimifyFiles' ) );
