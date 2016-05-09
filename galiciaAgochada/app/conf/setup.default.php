<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  PRJ_BASE_PATH - Project Path (normalmente contiene app/ httpdocs/ formFiles/) (en index.php)
  APP_BASE_PATH - App Path (en index.php)
  APP_TMP_PATH  - Ficheros temporales (en index.php)
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


//
// Dates
//
cogumeloSetSetupValue( 'date:timezone', 'Europe/Madrid');


//
//  memcached
//
require_once( APP_BASE_PATH.'/conf/memcached.setup.php' );  //memcached options


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
// Backups
//
cogumeloSetSetupValue( 'script:backupPath', APP_BASE_PATH.'/backups/' ); //backups directory


//
//  Logs
//
cogumeloSetSetupValue( 'logs', array(
  'path' => APP_BASE_PATH.'/log', // log files directory
  'rawSql' => false, // Log RAW all SQL ¡WARNING! application passwords will dump into log files
  'debug' => true, // Set Debug mode to log debug messages on log
  'error' => true // Display errors on screen. If you use devel module, you might disable it
));


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
//  Form Mod
//
cogumeloSetSetupValue( 'mod:form', array(
  'cssPrefix' => 'cgmMForm',
  'tmpPath' => APP_TMP_PATH.'/formFiles'
));


//
//  Filedata Mod
//
cogumeloSetSetupValue( 'mod:filedata', array(
  'filePath' => PRJ_BASE_PATH.'/formFiles',
  'cachePath' => WEB_BASE_PATH.'/cgmlImg'
));
include 'filedataImageProfiles.php';


//
//  SESSION
//
// Session lifetime
cogumeloSetSetupValue( 'session:lifetime', 4*60*60 ); // 4h.
ini_set( 'session.gc_maxlifetime',  cogumeloGetSetupValue( 'session:lifetime' ) );
ini_set( 'session.cookie_lifetime', cogumeloGetSetupValue( 'session:lifetime' ) );
// Enable session garbage collection with a 1% chance of running on each session_start()
ini_set( 'session.gc_probability', 1 );
ini_set( 'session.gc_divisor', 100 );
// Our own session save path
cogumeloSetSetupValue( 'session:savePath', APP_BASE_PATH . '/php-sessions' );
session_save_path( cogumeloGetSetupValue( 'session:savePath' ) );


//
// Url to load resource view from ID
//
cogumeloSetSetupValue( 'mod:geozzy:resource:directUrl', 'resource' );

