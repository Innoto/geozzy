<?php
/**
 * Previamente ya se definen los siguientes valores:
 *
 * WEB_BASE_PATH - Apache DocumentRoot (declarado en index.php)
 * APP_BASE_PATH - App Path (declarado en index.php)
 * SITE_PATH - App Path (declarado en index.php)
 *
 * IS_DEVEL_ENV - Indica si estamos en el entorno de desarrollo (declarado en setup.php)
 *
 *
 * Normas de estilo:
 *
 * * Nombres:
 * - Inicia por mod:nombreModulo: para modulos
 * - Usar en variablePath para rutas
 *
 * * Valores:
 * - Las rutas NO finalizan en /
 * - Las URL NO finalizan en /
 */

// En setup.*
// cogumeloSetSetupValue( 'modName:level1:level2', $value );
// $value = cogumeloGetSetupValue( 'modName:level1:level2' );

// En codigo cogumelo
// Cogumelo::setSetupValue( 'modName:level1:level2', $value );
// $value = Cogumelo::getSetupValue( 'modName:level1:level2' );


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


//
//  DB
//
/*
define( 'DB_ENGINE', 'mysql' );
define( 'DB_HOSTNAME', 'localhost');
define( 'DB_PORT', '3306');
define( 'DB_USER', 'galiciaagochada');
define( 'DB_PASSWORD', 'q7w8e9r');
define( 'DB_NAME', 'galiciaagochada');

define( 'DB_MYSQL_GROUPCONCAT_MAX_LEN', 4294967295); //max 	4294967295 (in 32 bits) , 18446744073709547520 (in 64 bits)

// allow cache with memcached
define( 'DB_ALLOW_CACHE', true );
*/
/*
MOVIDO a setup.dev.php o setup.final.php

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
*/

require_once( APP_BASE_PATH.'/conf/memcached.setup.php' );  //memcached options

//
// Public Access User
//
define( 'GA_ACCESS_USER', 'gaUser' );
define( 'GA_ACCESS_PASSWORD', 'gz15005' );


//
//  Url settings
//
// TODO: Cuidado porque no se admite un puerto
/*
Agora en setup.dev.php ou setup.final.php
define( 'COGUMELO_ADMINSCRIPT_URL', 'http://galiciaagochada/cogumelo-server.php');
*/
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
define( 'COGUMELO_APP_URL_ALIAS_CONTROLLER', COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );


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
/*
cogumeloSetSetupValue( 'smtp', array(
  'host' => 'localhost',
  'port' => '25',
  'auth' => false,
  //'user' => 'mailuser',
  //'pass' => 'mailuserpass',
  //'secure' => 'tls',
  'fromName' => 'Cogumelo Sender',
  'fromEmail' => 'cogumelo@cogumelo.org'
));
*/


//
//  Templates
//
// Constante usada directamente por Smarty
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
  'geozzyUser',
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
  'rextAppLugar',
  'rextAppEspazoNatural',
  'rextAppZona',
  'rextSocialNetwork',
  'rextEvent',
  'rextEventCollection',
  'rextAppFesta'
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
  'rtypeEvent',
  //initial resources
  'initResources'
);

// Merge all modules
$C_ENABLED_MODULES = array_merge( $C_ENABLED_MODULES, $C_REXT_MODULES, $C_RTYPE_MODULES );


// before app/Cogumelo.php execution
global $C_INDEX_MODULES;
$C_INDEX_MODULES  = array(
  'i18nGetLang',
  'i18nServer',
  'mediaserver',
  'user',
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
  'geozzyUser',
  'devel'
); // DEVEL SIEMPRE DE ULTIMO!!!


//  Logs
//
cogumeloSetSetupValue( 'logs', array(
  'path' => APP_BASE_PATH.'/log/', //log files directory
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
  'path' => SITE_PATH.'conf/i18n/',
  'localePath' => SITE_PATH.'conf/i18n/locale/',
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
  'filePath' => APP_BASE_PATH.'/../formFiles',
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
  'minimifyFiles' => false // for js and css files ( only when MEDIASERVER_PRODUCTION_MODE is true)
));
*/
cogumeloSetSetupValue( 'publicConf',
  array(
    'globalVars' => array( 'C_LANG', 'C_SESSION_ID' ),
    'setupFields' => array( 'lang:available', 'lang:default', 'mod:geozzy:resource:directUrl' ),
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
      'base' => array()
    ),
    'rtypeAppHotel' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppRestaurant' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeEvent'),
      'base' => array('rtypeAppHotel', 'rtypeAppRestaurant')
    ),
    'rtypeAppEspazoNatural' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'base' => array()
    ),
    'rtypeAppLugar' => array(
      'multimedia' => array(),
      'eventos' => array(),
      'base' => array()
    ),
    'rtypeAppFesta' => array(
      'multimedia' => array(),
      'eventos' => array('rtypeAppFesta', 'rtypeEvent'),
      'base' => array()
    )
  )
);




























// A eliminar:
define( 'LOGDIR', cogumeloGetSetupValue( 'logs:path' ) ); //log files directory
define( 'LOG_RAW_SQL', cogumeloGetSetupValue( 'logs:rawSql' ) ); // Log RAW all SQL ¡WARNING! application passwords will dump into log files
define( 'DEBUG', cogumeloGetSetupValue( 'logs:debug' ) ); // Set Debug mode to log debug messages on log
define( 'ERRORS', cogumeloGetSetupValue( 'logs:error' ) ); // Display errors on screen. If you use devel module, you might disable it

// A eliminar:
define( 'BCK', cogumeloGetSetupValue( 'script:backupPath' ) );
//cogumelo/cogumeloScript:    BCK, I18N, I18N_LOCALE,TPL_TMP );
//cogumelo/coreModules/devel/classes/view/DevelView.php:    $this->template->assign("infoBck" , BCK);

// A eliminar:
global $LANG_AVAILABLE;
$LANG_AVAILABLE = cogumeloGetSetupValue( 'lang:available' );
define( 'LANG_DEFAULT', cogumeloGetSetupValue( 'lang:default' ) );

// A eliminar:
define( 'SMARTY_CONFIG', cogumeloGetSetupValue( 'smarty:configPath' ) );
define( 'SMARTY_COMPILE', cogumeloGetSetupValue( 'smarty:compilePath' ) );
define( 'SMARTY_CACHE', cogumeloGetSetupValue( 'smarty:cachePath' ) );
define( 'TPL_TMP', cogumeloGetSetupValue( 'smarty:tmpPath' ) );

// A eliminar:
define( 'DEPEN_COMPOSER_PATH', cogumeloGetSetupValue( 'dependences:composerPath' ) );
define( 'DEPEN_BOWER_PATH', cogumeloGetSetupValue( 'dependences:bowerPath' ) );
define( 'DEPEN_MANUAL_PATH', cogumeloGetSetupValue( 'dependences:manualPath' ) );
define( 'DEPEN_MANUAL_REPOSITORY', cogumeloGetSetupValue( 'dependences:manualRepositoryPath' ) );

// A eliminar:
define( 'MOD_DEVEL_ALLOW_ACCESS', cogumeloGetSetupValue( 'mod:devel:allowAccess' ) );
define( 'MOD_DEVEL_URL_DIR', cogumeloGetSetupValue( 'mod:devel:url' ) );
define( 'MOD_DEVEL_PASSWORD', cogumeloGetSetupValue( 'mod:devel:password' ) );

// A eliminar:
define( 'I18N', cogumeloGetSetupValue( 'i18n:path' ) );
define( 'I18N_LOCALE', cogumeloGetSetupValue( 'i18n:localePath' ) );
define( 'GETTEXT_UPDATE', cogumeloGetSetupValue( 'i18n:gettextUpdate' ) );

// A eliminar:
define( 'MOD_FORM_CSS_PRE', cogumeloGetSetupValue( 'mod:form:cssPrefix' ) );
define( 'MOD_FORM_FILES_TMP_PATH', cogumeloGetSetupValue( 'mod:form:tmpPath' ) );
// QUITAR MOD_FORM_FILES_APP_PATH
define( 'MOD_FORM_FILES_APP_PATH', APP_BASE_PATH.'/../formFiles' );

// A eliminar:
define( 'MOD_FILEDATA_APP_PATH', cogumeloGetSetupValue( 'mod:filedata:filePath' )  );
define( 'MOD_FILEDATA_CACHE_PATH', cogumeloGetSetupValue( 'mod:filedata:cachePath' ) );

// A eliminar:
/*
define( 'MEDIASERVER_PRODUCTION_MODE', cogumeloGetSetupValue( 'mod:mediaserver:productionMode' ) );
define( 'MEDIASERVER_NOT_CACHE_JS', cogumeloGetSetupValue( 'mod:mediaserver:notCacheJs' ) );
define( 'MEDIASERVER_HOST', cogumeloGetSetupValue( 'mod:mediaserver:host' ) );
define( 'MOD_MEDIASERVER_URL_DIR', cogumeloGetSetupValue( 'mod:mediaserver:path' ) );
define( 'MEDIASERVER_FINAL_CACHE_PATH', cogumeloGetSetupValue( 'mod:mediaserver:cachePath' ) );
define( 'MEDIASERVER_TMP_CACHE_PATH', cogumeloGetSetupValue( 'mod:mediaserver:tmpCachePath' ) );
define( 'MEDIASERVER_MINIMIFY_FILES', cogumeloGetSetupValue( 'mod:mediaserver:minimifyFiles' ) );
*/

// A eliminar:
global $MEDIASERVER_LESS_GLOBALS; // Se cargan con el prefijo GLOBAL_
$MEDIASERVER_LESS_GLOBALS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:less:globalVars' );
global $MEDIASERVER_LESS_CONSTANTS;
$MEDIASERVER_LESS_CONSTANTS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:less:vars' );
global $MEDIASERVER_JAVASCRIPT_GLOBALS; // Se cargan con el prefijo GLOBAL_
$MEDIASERVER_JAVASCRIPT_GLOBALS = array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' );
global $MEDIASERVER_JAVASCRIPT_CONSTANTS;
$MEDIASERVER_JAVASCRIPT_CONSTANTS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:javascript:vars' );
global $MEDIASERVER_SMARTY_GLOBALS; // Se cargan con el prefijo GLOBAL_
$MEDIASERVER_SMARTY_GLOBALS = array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' );
global $MEDIASERVER_SMARTY_CONSTANTS;
$MEDIASERVER_SMARTY_CONSTANTS = cogumeloGetSetupValue( 'mod:mediaserver:publicConf:smarty:vars' );
