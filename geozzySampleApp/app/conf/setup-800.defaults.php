<?php


//
// DateTimeZone
//
$conf->createSetupValue( 'date:timezone:system', 'UTC' );
$conf->createSetupValue( 'date:timezone:database', 'UTC' );
$conf->createSetupValue( 'date:timezone:project', 'Europe/Madrid' );


//
//  Templates Smarty
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
// Importante para syslog: Configurar 'project:idName' o 'logs:idName'
$conf->createSetupValue( 'logs:type', 'file' ); // file, syslog, disable
$conf->createSetupValue( 'logs:path', $conf->getSetupValue( 'setup:appBasePath' ).'/log' ); // log files directory
$conf->createSetupValue( 'logs:debug', true ); // Set Debug mode to log debug messages on log
$conf->createSetupValue( 'logs:rawSql', false ); // Log RAW all SQL ¡WARNING! application passwords will dump into log files
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
// Url to load resource view from ID
//
$conf->createSetupValue( 'mod:geozzy:resource:directUrl', 'resource' );
