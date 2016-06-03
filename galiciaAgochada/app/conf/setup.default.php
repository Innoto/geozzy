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
require_once( cogumeloGetSetupValue( 'setup:appBasePath' ).'/conf/memcached.setup.php' );  //memcached options


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
cogumeloSetSetupValue( 'setup:webBaseUrl', array(
  'protocol' => 'SITE_PROTOCOL',
  'host' => 'SITE_HOST',
  'folder' => 'SITE_FOLDER',
  'url' => 'SITE_URL',
  'urlHttp' => 'SITE_URL_HTTP',
  'urlHttps' => 'SITE_URL_HTTPS',
  'urlCurrent' => 'SITE_URL_CURRENT'
));
if( isset( $_SERVER['SERVER_PORT'] ) ) {
  cogumeloSetSetupValue( 'setup:webBaseUrl:port' => $_SERVER['SERVER_PORT'] );
}


//
//  Templates
//
// Constante usada directamente por Smarty - No eliminar!!!
define( 'SMARTY_DIR', cogumeloGetSetupValue( 'setup:webBasePath' ).'/vendor/composer/smarty/smarty/libs/' );
cogumeloSetSetupValue( 'smarty', array(
  'configPath' => cogumeloGetSetupValue( 'setup:appBasePath' ).'/conf/smarty',
  'compilePath' => cogumeloGetSetupValue( 'setup:appTmpPath' ).'/templates_c',
  'cachePath' => cogumeloGetSetupValue( 'setup:appTmpPath' ).'/cache',
  'tmpPath' => cogumeloGetSetupValue( 'setup:appTmpPath' ).'/tpl'
));


//
// Backups
//
cogumeloSetSetupValue( 'script:backupPath', cogumeloGetSetupValue( 'setup:appBasePath' ).'/backups/' ); //backups directory


//
//  Logs
//
cogumeloSetSetupValue( 'logs', array(
  'path' => cogumeloGetSetupValue( 'setup:appBasePath' ).'/log', // log files directory
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
  'tmpPath' => cogumeloGetSetupValue( 'setup:appTmpPath' ).'/formFiles'
));


//
//  Filedata Mod
//
cogumeloSetSetupValue( 'mod:filedata', array(
  'filePath' => cogumeloGetSetupValue( 'setup:prjBasePath' ).'/formFiles',
  'cachePath' => cogumeloGetSetupValue( 'setup:webBasePath' ).'/cgmlImg'
));
include 'filedataImageProfiles.php';


//
//  SESSION
//
// Session lifetime
cogumeloSetSetupValue( 'session:lifetime', 4*60*60 ); // 4h.
ini_set( 'session.gc_maxlifetime',  cogumeloGetSetupValue( 'session:lifetime' ) );
ini_set( 'session.cookie_lifetime', 365*24*60*60 ); // 1 year
// Enable session garbage collection with a 1% chance of running on each session_start()
ini_set( 'session.gc_probability', 1 );
ini_set( 'session.gc_divisor', 100 );
// Our own session save path
cogumeloSetSetupValue( 'session:savePath', cogumeloGetSetupValue( 'setup:appBasePath' ).'/php-sessions' );
session_save_path( cogumeloGetSetupValue( 'session:savePath' ) );


//
// Url to load resource view from ID
//
cogumeloSetSetupValue( 'mod:geozzy:resource:directUrl', 'resource' );

