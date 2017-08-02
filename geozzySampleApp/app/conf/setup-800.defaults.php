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



//
// DateTimeZone
//
$conf->createSetupValue( 'date:timezone:system', 'UTC' );
$conf->createSetupValue( 'date:timezone:database', 'UTC' );
$conf->createSetupValue( 'date:timezone:project', 'Europe/Madrid' );


//
//  Templates
//
// Constante usada directamente por Smarty - No eliminar!!!
define( 'SMARTY_DIR', $conf->getSetupValue( 'setup:webBasePath' ).'/vendor/composer/smarty/smarty/libs/' );
$conf->createSetupValue( 'smarty:configPath', $conf->getSetupValue('setup:appBasePath').'/conf/smarty' );
$conf->createSetupValue( 'smarty:compilePath', $conf->getSetupValue('setup:appTmpPath').'/templates_c' );
$conf->createSetupValue( 'smarty:cachePath', $conf->getSetupValue('setup:appTmpPath').'/cache' );
$conf->createSetupValue( 'smarty:tmpPath', $conf->getSetupValue('setup:appTmpPath').'/tpl' );


//
// Backups
//
$conf->createSetupValue( 'script:backupPath', $conf->getSetupValue( 'setup:appBasePath' ).'/backups/' ); //backups directory


//
//  Logs
//
$conf->createSetupValue( 'logs:path', $conf->getSetupValue( 'setup:appBasePath' ).'/log' ); // log files directory
$conf->createSetupValue( 'logs:rawSql', false ); // Log RAW all SQL ¡WARNING! application passwords will dump into log files
$conf->createSetupValue( 'logs:debug', true ); // Set Debug mode to log debug messages on log
$conf->createSetupValue( 'logs:error', false ); // Display errors on screen. If you use devel module, you might disable it



//
//  Mail sender
//
$conf->createSetupValue( 'mail', array(
  'type' => 'local',
  'host' => 'localhost',
  'port' => '25',
  'fromName' => 'Cogumelo Sender',
  'fromEmail' => 'cogumelo@cogumelo.local'
));


//
//  Form Mod
//
$conf->createSetupValue( 'mod:form:cssPrefix', 'cgmMForm' );
$conf->createSetupValue( 'mod:form:tmpPath', $conf->getSetupValue( 'setup:appTmpPath' ).'/formFiles' );


//
//  Filedata Mod
//
$conf->createSetupValue( 'mod:filedata:filePath', $conf->getSetupValue( 'setup:prjBasePath' ).'/formFiles' );
$conf->createSetupValue( 'mod:filedata:cachePath', $conf->getSetupValue( 'setup:webBasePath' ).'/cgmlImg' );
$conf->createSetupValue( 'mod:filedata:filePathPublic', $conf->getSetupValue( 'setup:prjBasePath' ).'/formFiles/public' );
include 'setup-800.defaults.filedataImageProfiles.php';


//
//  Client localStorage
//
$conf->createSetupValue( 'clientLocalStorage:lifetime', 60 ); // 1h.


//
//  SESSION
//
// Session lifetime
$conf->createSetupValue( 'session:lifetime', 4*60*60 ); // 4h.
ini_set( 'session.gc_maxlifetime',  $conf->getSetupValue( 'session:lifetime' ) );
ini_set( 'session.cookie_lifetime', 365*24*60*60 ); // 1 year
// Enable session garbage collection with a 1% chance of running on each session_start()
ini_set( 'session.gc_probability', 1 );
ini_set( 'session.gc_divisor', 100 );
// Our own session save path
$conf->createSetupValue( 'session:savePath', $conf->getSetupValue( 'setup:appBasePath' ).'/php-sessions' );
session_save_path( $conf->getSetupValue( 'session:savePath' ) );


//
// Url to load resource view from ID
//
$conf->createSetupValue( 'mod:geozzy:resource:directUrl', 'resource' );
