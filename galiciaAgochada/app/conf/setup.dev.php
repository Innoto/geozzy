<?php
/**
 * Previamente ya se definen los siguientes valores:
 *
 * WEB_BASE_PATH - Apache DocumentRoot (declarado en index.php)
 *
 * APP_BASE_PATH - App Path (declarado en index.php)
 * SITE_PATH - App Path (declarado en index.php)
 *
 * IS_DEVEL_ENV - Indica si estamos en el entorno de desarrollo (declarado en setup.php)
 *
 *
 * Normas de estilo:
 *
 * * Nombres:
 * - Inicia por MOD_NOMBREMODULO_ para modulos
 * - Finalizan en _PATH para rutas
 *
 * * Valores:
 * - Las rutas no finalizan en /
 * - Las URL no finalizan en /
 */



//
//  SESSION
//
ini_set( 'session.cookie_lifetime', 86400 );
ini_set( 'session.gc_maxlifetime', 86400 );



//
//  APP
//
define( 'APP_TMP_PATH', APP_BASE_PATH.'/tmp' );


//
// Framework Path
//
define( 'COGUMELO_LOCATION', '/home/proxectos/cogumelo' );
define( 'COGUMELO_DIST_LOCATION', '/home/proxectos/geozzy');


//
//  DB
//
define( 'DB_ENGINE', 'mysql' );
define( 'DB_HOSTNAME', 'localhost');
define( 'DB_PORT', '3306');
define( 'DB_USER', 'galiciaagochada');
define( 'DB_PASSWORD', 'q7w8e9r');
define( 'DB_NAME', 'galiciaagochada');

define( 'DB_MYSQL_GROUPCONCAT_MAX_LEN', 4294967295); //max 	4294967295 (in 32 bits) , 18446744073709547520 (in 64 bits)

// allow cache with memcached
define( 'DB_ALLOW_CACHE', true );
require_once( APP_BASE_PATH.'/conf/memcached.setup.php' );  //memcached options

// Public Access User
define( 'GA_ACCESS_USER', 'gaUser' );
define( 'GA_ACCESS_PASSWORD', 'gz15005' );

//
//  Url settings
//
// TODO: Cuidado porque no se admite un puerto
define( 'COGUMELO_ADMINSCRIPT_URL', 'http://galiciaagochada/cogumelo-server.php');
define( 'SITE_PROTOCOL', isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' );
define( 'SITE_HOST', SITE_PROTOCOL.'://'.$_SERVER['HTTP_HOST']);  // solo HOST sin ('/')
define( 'SITE_FOLDER', '/' );  // SITE_FOLDER STARTS AND ENDS WITH SLASH ('/')
define( 'SITE_URL', SITE_HOST . SITE_FOLDER );
define( 'SITE_URL_HTTP', 'http://'.$_SERVER['HTTP_HOST'] . SITE_FOLDER );
define( 'SITE_URL_HTTPS', 'https://'.$_SERVER['HTTP_HOST'] . SITE_FOLDER );
define( 'SITE_URL_CURRENT', SITE_PROTOCOL == 'http' ? SITE_URL_HTTP : SITE_URL_HTTPS );

// Fichero que contiene una clase UrlAliasController con un metodo getAlternative
define( 'COGUMELO_APP_URL_ALIAS_CONTROLLER', COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );


//
//  Sendmail
//
define( 'SMTP_HOST', 'localhost' );
define( 'SMTP_PORT', '25' );
define( 'SMTP_AUTH', false );
define( 'SMTP_USER', '' );
define( 'SMTP_PASS', '' );

define( 'SYS_MAIL_FROM_NAME', 'Cogumelo Sender' );
define( 'SYS_MAIL_FROM_EMAIL', 'cogumelo@cogumelo.org' );


//
//  Templates
//
define( 'SMARTY_DIR', WEB_BASE_PATH.'/vendor/composer/smarty/smarty/libs/');
define( 'SMARTY_CONFIG', APP_BASE_PATH.'/conf/smarty' );
define( 'SMARTY_COMPILE', APP_TMP_PATH.'/templates_c' );
define( 'SMARTY_CACHE', APP_TMP_PATH.'/cache' );
define( 'TPL_TMP', APP_TMP_PATH.'/tpl' );


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
  // testing module
  'testData'
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
  'rextSocialNetwork'
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
  'rtypeAppFestaPopular'
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
  'explorer',
  'devel'

); // DEVEL SIEMPRE DE ULTIMO!!!


//
//  Logs
//
define( 'LOGDIR', APP_BASE_PATH.'/log/' ); //log files directory
define( 'LOG_RAW_SQL', false ); // Log RAW all SQL ¡WARNING! application passwords will dump into log files
define( 'DEBUG', true ); // Set Debug mode to log debug messages on log
define( 'ERRORS', true ); // Display errors on screen. If you use devel module, you might disable it


//
// Backups
//
define( 'BCK', APP_BASE_PATH.'/backups/' ); //backups directory


//
//  Devel Mod
//
define( 'MOD_DEVEL_ALLOW_ACCESS', true );
define( 'MOD_DEVEL_URL_DIR', 'devel' );
define( 'MOD_DEVEL_PASSWORD', 'develpassword' );


//
//  i18n
//
define ('I18N', SITE_PATH.'conf/i18n/');
define ('I18N_LOCALE', SITE_PATH.'conf/i18n/locale/');
define( 'GETTEXT_UPDATE', true ); // update gettext files when working in localhost

global $LANG_AVAILABLE;
$LANG_AVAILABLE = array(
  'es' => array(
    'i18n' => 'es_ES',
    'name' => 'castellano' ),
  'gl' => array(
    'i18n' => 'gl_ES',
    'name' => 'galego' ),
  'en' => array(
    'i18n' => 'en_US',
    'name' => 'english' ),
);
define( 'LANG_DEFAULT', 'es' );

//
//  Form Mod
//
define( 'MOD_FORM_CSS_PRE', 'cgmMForm' );
define( 'MOD_FORM_FILES_TMP_PATH', APP_TMP_PATH.'/formFiles' );
define( 'MOD_FORM_FILES_APP_PATH', APP_BASE_PATH.'/../formFiles' );

//
//  Filedata Mod
//
define( 'MOD_FILEDATA_APP_PATH', MOD_FORM_FILES_APP_PATH  );
define( 'MOD_FILEDATA_CACHE_PATH', WEB_BASE_PATH.'/cgmlImg' );
include 'filedataImageProfiles.php';


//
// Dependences PATH
//
define( 'DEPEN_COMPOSER_PATH', WEB_BASE_PATH.'/vendor/composer' );
define( 'DEPEN_BOWER_PATH', WEB_BASE_PATH.'/vendor/bower' );
define( 'DEPEN_MANUAL_PATH', WEB_BASE_PATH.'/vendor/manual' );
define( 'DEPEN_MANUAL_REPOSITORY', COGUMELO_LOCATION.'/packages/vendorPackages' );


//
//	Media server
//
define( 'MEDIASERVER_PRODUCTION_MODE', false ); // If true, you must compile less manually with ./cogumelo generateClientCaches
define( 'MEDIASERVER_NOT_CACHE_JS', true );
define( 'MEDIASERVER_HOST', '/' ); // Ej: '/' o 'http://media.galiciaagochada/'

define( 'MOD_MEDIASERVER_URL_DIR', 'media');
define( 'MEDIASERVER_FINAL_CACHE_PATH', 'mediaCache' );
define( 'MEDIASERVER_TMP_CACHE_PATH', APP_TMP_PATH.'/mediaCache' );
define( 'MEDIASERVER_MINIMIFY_FILES', false ); // for js and css files ( only when MEDIASERVER_PRODUCTION_MODE is true)

/*
setCogumeloSetupConf( 'mediaserver',
  array(
    'productionMode' => false, // If true, you must compile less manually with ./cogumelo generateClientCaches
    'notCacheJs' => true,
    'mediaHost' => 'http://media.galiciaagochada/', // Ej: '/' o 'http://media.galiciaagochada/'
    'mediaPath' => 'media',
    'cachePath' => 'mediaCache',
    'tmpCachePath' => APP_TMP_PATH.'/mediaCache',
    'minimifyFiles' => false // for js and css files ( only when MEDIASERVER_PRODUCTION_MODE is true)
  )
);

define( 'MEDIASERVER_PRODUCTION_MODE', getCogumeloSetupConf( 'mediaserver:productionMode' ) ); // If true, you must compile less manually with ./cogumelo generateClientCaches
define( 'MEDIASERVER_NOT_CACHE_JS', getCogumeloSetupConf( 'mediaserver:notCacheJs' ) );
define( 'MEDIASERVER_HOST', getCogumeloSetupConf( 'mediaserver:mediaHost' ) ); // Ej: '/' o 'http://media.galiciaagochada/'
define( 'MOD_MEDIASERVER_URL_DIR', getCogumeloSetupConf( 'mediaserver:mediaPath' ) );
define( 'MEDIASERVER_FINAL_CACHE_PATH', getCogumeloSetupConf( 'mediaserver:cachePath' ) );
define( 'MEDIASERVER_TMP_CACHE_PATH', getCogumeloSetupConf( 'mediaserver:tmpCachePath' ) );
define( 'MEDIASERVER_MINIMIFY_FILES', getCogumeloSetupConf( 'mediaserver:minimifyFiles' ) ); // for js and css files ( only when MEDIASERVER_PRODUCTION_MODE is true)
*/


global $MEDIASERVER_LESS_GLOBALS; // Se cargan con el prefijo GLOBAL_
$MEDIASERVER_LESS_GLOBALS = array( 'C_LANG' );
global $MEDIASERVER_LESS_CONSTANTS;
$MEDIASERVER_LESS_CONSTANTS = array(
  'langDefault' => LANG_DEFAULT,
  'langAvailableIds' => array_keys( $LANG_AVAILABLE )
);

global $MEDIASERVER_JAVASCRIPT_GLOBALS; // Se cargan con el prefijo GLOBAL_
$MEDIASERVER_JAVASCRIPT_GLOBALS = array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' );
global $MEDIASERVER_JAVASCRIPT_CONSTANTS;
$MEDIASERVER_JAVASCRIPT_CONSTANTS = array(
  'langDefault' => LANG_DEFAULT,
  'langAvailableIds' => array_keys( $LANG_AVAILABLE ),
  'mediaJs' => ( MEDIASERVER_PRODUCTION_MODE == true && MEDIASERVER_NOT_CACHE_JS != true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
  'media' => ( MEDIASERVER_PRODUCTION_MODE == true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
  'site_host' => SITE_HOST
);

global $MEDIASERVER_SMARTY_GLOBALS; // Se cargan con el prefijo GLOBAL_
$MEDIASERVER_SMARTY_GLOBALS = array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' );
global $MEDIASERVER_SMARTY_CONSTANTS;
$MEDIASERVER_SMARTY_CONSTANTS = array(
  'langDefault' => LANG_DEFAULT,
  'langAvailableIds' => array_keys( $LANG_AVAILABLE ),
  'mediaJs' => ( MEDIASERVER_PRODUCTION_MODE == true && MEDIASERVER_NOT_CACHE_JS != true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
  'media' => ( MEDIASERVER_PRODUCTION_MODE == true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
  'site_host' => SITE_HOST
);

setCogumeloSetupConf( 'mediaserver:publicConf:less',
  array(
    'globals' => array( 'C_LANG' ),
    'setupFields' => array( 'geozzy:resource:directUrl' ),
    'vars' => array(
      'langDefault' => LANG_DEFAULT,
      'langAvailableIds' => array_keys( $LANG_AVAILABLE )
    )
  )
);

setCogumeloSetupConf( 'mediaserver:publicConf:javascript',
  array(
    'globalVars' => array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' ),
    'setupFields' => array( 'geozzy:resource:directUrl' ),
    'vars' => array(
      'langDefault' => LANG_DEFAULT,
      'langAvailableIds' => array_keys( $LANG_AVAILABLE ),
      'mediaJs' => ( MEDIASERVER_PRODUCTION_MODE == true && MEDIASERVER_NOT_CACHE_JS != true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
      'media' => ( MEDIASERVER_PRODUCTION_MODE == true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
      'site_host' => SITE_HOST
    )
  )
);

setCogumeloSetupConf( 'mediaserver:publicConf:smarty',
  array(
    'globals' => array( 'LANG_AVAILABLE', 'C_LANG', 'C_SESSION_ID' ),
    'setupFields' => array( 'geozzy:resource:directUrl' ),
    'vars' => array(
      'langDefault' => LANG_DEFAULT,
      'langAvailableIds' => array_keys( $LANG_AVAILABLE ),
      'mediaJs' => ( MEDIASERVER_PRODUCTION_MODE == true && MEDIASERVER_NOT_CACHE_JS != true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
      'media' => ( MEDIASERVER_PRODUCTION_MODE == true )? MEDIASERVER_HOST.MEDIASERVER_FINAL_CACHE_PATH : MEDIASERVER_HOST.MOD_MEDIASERVER_URL_DIR,
      'site_host' => SITE_HOST
    )
  )
);
