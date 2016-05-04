<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  PRJ_BASE_PATH - Project Path (normalmente contiene app/ httpdocs/ formFiles/) (en index.php)
  APP_BASE_PATH - App Path (en index.php)
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


// Lang
//
cogumeloSetSetupValue( 'lang', array(
  'available' => array(
    'es' => array(
      'i18n' => 'es_ES',
      'name' => 'castellano' ),
    'gl' => array(
      'i18n' => 'gl_ES',
      'name' => 'galego' ),
    'en' => array(
      'i18n' => 'en_US',
      'name' => 'english' ),
  ),
  'default' => 'es'
));


// Dates
//
cogumeloSetSetupValue( 'date:timezone', 'Europe/Madrid');


//
//  memcached
//
require_once( APP_BASE_PATH.'/conf/memcached.setup.php' );  //memcached options

//
// Public Access User
//
define( 'GA_ACCESS_USER', 'gaUser' );
define( 'GA_ACCESS_PASSWORD', 'gz15005' );


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

//
// URL alias controller: Fichero que contiene una clase UrlAliasController con un metodo getAlternative
//
cogumeloSetSetupValue( 'urlAliasController:classFile', COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );

//
//  Mail sender
//
cogumeloSetSetupValue( 'mail', array(
  'type' => 'smtp',
  'host' => 'localhost',
  'port' => '25',
  'auth' => false,
  //'user' => 'mailuser',
  //'pass' => 'mailuserpass',
  //'secure' => 'tls',
  'fromName' => 'Cogumelo Sender',
  'fromEmail' => 'cogumelo@cogumelo.org'
));
/*
cogumeloSetSetupValue( 'mail', array(
  'type' => 'gmail',
  'host' => 'localhost',
  'port' => '25',
  'auth' => false,
  'user' => 'mailuser',
  'pass' => 'mailuserpass',
  'fromName' => 'Cogumelo Sender',
  'fromEmail' => 'cogumelo@cogumelo.org'
));
*/


//
//  Templates
//
// Constante usada directamente por Smarty - No eliminar!!!
define( 'SMARTY_DIR', WEB_BASE_PATH.'/vendor/composer/smarty/smarty/libs/' );
cogumeloSetSetupValue( 'smarty', array(
  'configPath' => APP_BASE_PATH.'/conf/smarty',
  'compilePath' => APP_TMP_PATH.'/templates_c',
  'cachePath' => APP_TMP_PATH.'/cache',
  'tmpPath' => APP_TMP_PATH.'/tpl'
));


//
// Dependences PATH
//
cogumeloSetSetupValue( 'dependences', array(
  'composerPath' => WEB_BASE_PATH.'/vendor/composer',
  'bowerPath' => WEB_BASE_PATH.'/vendor/bower',
  'manualPath' => WEB_BASE_PATH.'/vendor/manual',
  'manualRepositoryPath' => COGUMELO_LOCATION.'/packages/vendorPackages'
));


//
//  Module load
//
global $C_ENABLED_MODULES;
$C_ENABLED_MODULES = array(
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'common',
  'devel',
  'user',
  'geozzyAPI',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'bi',
  'biMetrics',
  'admin',
  'form',
  'Blocks',
  'table',
  'explorer',
  'appExplorer',
  // testing module
  'testData',
);

// resource Extenssions
global $C_REXT_MODULES;
$C_REXT_MODULES = array(
  'rextAccommodation',
  'rextEatAndDrink',
  'rextContact',
  'rextMapDirections',
  'rextUrl',
  'rextView',
  'rextFile',
  'rextUserProfile',
  'rextAppLugar',
  'rextAppEspazoNatural',
  'rextAppZona',
  'rextSocialNetwork',
  'rextEvent',
  'rextEventCollection',
  'rextAppFesta',
  'rextPoi',
  'rextPoiCollection',
  'rextComment',
  'rextRoutes'
);

// resource Types
global $C_RTYPE_MODULES;
$C_RTYPE_MODULES = array(
  'rtypeAppHotel',
  'rtypeAppRestaurant',
  'rtypeUrl',
  'rtypePage',
  'rtypeFile',
  'rtypeAppRuta',
  'rtypeAppLugar',
  'rtypeAppEspazoNatural',
  'rtypeAppFesta',
  'rtypeAppUser',
  'rtypePoi',
  'rtypeEvent'
);


// Ultimate modules
global $C_ULTIMATE_MODULES;
$C_ULTIMATE_MODULES = array(
  'initResources',
  'geozzyUser'
);

// Merge all modules
$C_ENABLED_MODULES = array_merge( $C_ENABLED_MODULES, $C_REXT_MODULES, $C_RTYPE_MODULES, $C_ULTIMATE_MODULES );


// before app/Cogumelo.php execution
// Needed for modules with their own urls
global $C_INDEX_MODULES;
$C_INDEX_MODULES  = array(
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'user',
  'geozzyUser',
  'filedata',
  'geozzy',
  'appResourceBridge',
  'form',
  'admin',
  'Blocks',
  'geozzyAPI',
  'testData',
  'initResources',
  'explorer',
  'rextRoutes',
  'rextComment',
  'rtypeEvent',
  'rtypePoi',
  'devel'
); // DEVEL SIEMPRE DE ULTIMO!!!

// User config
cogumeloSetSetupValue( 'mod:geozzyUser', array(
  'profile' => 'rtypeAppUser'
));


//  Logs
//
cogumeloSetSetupValue( 'logs', array(
  'path' => APP_BASE_PATH.'/log', // log files directory
  'rawSql' => false, // Log RAW all SQL ¡WARNING! application passwords will dump into log files
  'debug' => true, // Set Debug mode to log debug messages on log
  'error' => true // Display errors on screen. If you use devel module, you might disable it
));


// Backups
//
cogumeloSetSetupValue( 'script:backupPath', APP_BASE_PATH.'/backups/' ); //backups directory


//  Devel Mod
//
cogumeloSetSetupValue( 'mod:devel', array(
  'allowAccess' => true,
  'url' => 'devel',
  'password' => 'develpassword'
));


//  i18n
//
cogumeloSetSetupValue( 'i18n', array(
  'path' => APP_BASE_PATH.'/conf/i18n',
  'localePath' => APP_BASE_PATH.'/conf/i18n/locale',
  'gettextUpdate' => true // update gettext files when working in localhost
));

//  Form Mod
//
cogumeloSetSetupValue( 'mod:form', array(
  'cssPrefix' => 'cgmMForm',
  'tmpPath' => APP_TMP_PATH.'/formFiles'
));

//  Filedata Mod
//
cogumeloSetSetupValue( 'mod:filedata', array(
  'filePath' => PRJ_BASE_PATH.'/formFiles',
  'cachePath' => WEB_BASE_PATH.'/cgmlImg'
));
include 'filedataImageProfiles.php';


//	Media server
//
/*
cogumeloSetSetupValue( 'mod:mediaserver', array(
  'productionMode' => false, // If true, you must compile less manually with ./cogumelo generateClientCaches
  'notCacheJs' => true,
  'host' => '/', // Ej: '/' o 'http://media.galiciaagochada/'
  'path' => 'media',
  'cachePath' => 'mediaCache',
  'tmpCachePath' => APP_TMP_PATH.'/mediaCache',
  'minimifyFiles' => false // for js and css files ( only when mod:mediaserver:productionMode is true)
));
*/
cogumeloSetSetupValue( 'publicConf',
  array(
    'globalVars' => array( 'C_LANG', 'C_SESSION_ID' ),
    'setupFields' => array( 'lang:available', 'lang:default', 'mod:geozzy:resource:directUrl', 'date:timezone' ),
    'vars' => array(
      'langDefault' => cogumeloGetSetupValue( 'lang:default' ),
      'langAvailableIds' => array_keys( cogumeloGetSetupValue( 'lang:available' ) ),
      'mediaJs' => ( cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) === true &&
        cogumeloGetSetupValue( 'mod:mediaserver:notCacheJs' ) !== true )
        ? cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:cachePath' )
        : cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:path' ),
      'media' => ( cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) === true )
        ? cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:cachePath' )
        : cogumeloGetSetupValue( 'mod:mediaserver:host' ).cogumeloGetSetupValue( 'mod:mediaserver:path' ),
      'mediaHost' => cogumeloGetSetupValue( 'mod:mediaserver:host' ),
      'site_host' => SITE_HOST
    )
  )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:javascript',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:less',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:smarty',
  cogumeloGetSetupValue( 'publicConf' )
);
cogumeloSetSetupValue( 'mod:mediaserver:publicConf:smarty:setupFields',
  array_merge( cogumeloGetSetupValue( 'publicConf:setupFields' ), array('user:session') )
);

// A eliminar:
// global $MEDIASERVER_LESS_GLOBALS; // Se cargan con el prefijo GLOBAL_
// $MEDIASERVER_LESS_GLOBALS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:less:globalVars' );
// global $MEDIASERVER_LESS_CONSTANTS;
// $MEDIASERVER_LESS_CONSTANTS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:less:vars' );
// global $MEDIASERVER_JAVASCRIPT_GLOBALS; // Se cargan con el prefijo GLOBAL_
// $MEDIASERVER_JAVASCRIPT_GLOBALS = array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' );
// global $MEDIASERVER_JAVASCRIPT_CONSTANTS;
// $MEDIASERVER_JAVASCRIPT_CONSTANTS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:javascript:vars' );
// global $MEDIASERVER_SMARTY_GLOBALS; // Se cargan con el prefijo GLOBAL_
// $MEDIASERVER_SMARTY_GLOBALS = array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' );
// global $MEDIASERVER_SMARTY_CONSTANTS;
// $MEDIASERVER_SMARTY_CONSTANTS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:smarty:vars' );









cogumeloSetSetupValue( 'mod:geozzy:resource:urlAliasPatterns',
  array(
    'default' => '/',
    'rtypeAppHotel' => array(
      'default' => '/alojamientos/',
      'gl' => '/aloxamentos/',
      'en' => '/accommodation/'
    ),
    'rtypeAppRestaurant' => array(
      'default' => '/comidas/',
      'en' => '/food/'
    ),
    'rtypeAppEspazoNatural' => array(
      'default' => '/naturaleza/',
      'gl' => '/natureza/',
      'en' => '/nature/'
    ),
    'rtypeAppLugar' => array(
      'default' => '/rincones/',
      'gl' => '/recunchos/',
      'en' => '/places/'
    )
  )
);

cogumeloSetSetupValue( 'mod:geozzy:resource:collectionTypeRules',
  array(
    'default' => array(
      'multimedia' => array('rtypeUrl', 'rtypeFile'),
      'eventos' => array('rtypeEvent'),
      'poi' => array('rtypePoi'),
      'base' => array()
    ),
    'rtypeAppHotel' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'poi' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppRestaurant' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'poi' => array(),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppEspazoNatural' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array('rtypePoi'),
      'base' => array()
    ),
    'rtypeAppLugar' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'poi' => array(),
      'base' => array()
    ),
    'rtypeAppFesta' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeAppFesta', 'rtypeEvent'),
      'poi' => array(),
      'base' => array()
    )
  )
);

cogumeloSetSetupValue( 'mod:geozzy:resource:systemRTypes',
  array(
    'rtypeUrl',
    'rtypePage',
    'rtypeFile',
    'rtypeEvent'
  )
);


cogumeloSetSetupValue( 'mod:geozzy:resource:commentRules',
  array(
    'default' => array(
      'moderation' => 'none', // none|verified|all
      'ctype' => array() // 'comment','suggest'
    ),
    'rtypeAppHotel' => array(
      'moderation' => 'none', // none|verified|all
      'ctype' => array('comment','suggest') // 'comment','suggest'
    ),
    'rtypeAppRestaurant' => array(
      'moderation' => 'verified', // none|verified|all
      'ctype' => array('suggest') // 'comment','suggest'
    )
  )
);
