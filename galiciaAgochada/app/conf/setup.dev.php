<?php

// Framework Path
//
define( 'COGUMELO_LOCATION', '/home/proxectos/cogumelo' );
define( 'COGUMELO_DIST_LOCATION', '/home/proxectos/geozzy');

//  APP
//
define( 'APP_TMP_PATH', APP_BASE_PATH.'/tmp' );

// Url settings
// TODO: Cuidado porque no se admite un puerto
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
// A eliminar:
define( 'MEDIASERVER_PRODUCTION_MODE', cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) );
define( 'MEDIASERVER_NOT_CACHE_JS', cogumeloGetSetupValue( 'mod:mediaserver:notCacheJs' ) );
define( 'MEDIASERVER_HOST', cogumeloGetSetupValue( 'mod:mediaserver:host' ) );
define( 'MOD_MEDIASERVER_URL_DIR', cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
define( 'MEDIASERVER_FINAL_CACHE_PATH', cogumeloGetSetupValue( 'mod:mediaserver:cachePath' ) );
define( 'MEDIASERVER_TMP_CACHE_PATH', cogumeloGetSetupValue( 'mod:mediaserver:tmpCachePath' ) );
define( 'MEDIASERVER_MINIMIFY_FILES', cogumeloGetSetupValue( 'mod:mediaserver:minimifyFiles' ) );

