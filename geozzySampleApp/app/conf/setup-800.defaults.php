<?php

/**
 * Este fichero no debe editarse. Usa setup-300 o setup-500 para casos particulares
 */


// Optimizando peticiones multiples
$webBasePath = $conf->getSetupValue('setup:webBasePath');
$appBasePath = $conf->getSetupValue('setup:appBasePath');
$appTmpPath = $conf->getSetupValue('setup:appTmpPath');


//
// DateTimeZone
//
$conf->createSetupValue( 'date:timezone:system', 'UTC' );
$conf->createSetupValue( 'date:timezone:database', 'UTC' );
$conf->createSetupValue( 'date:timezone:project', 'Europe/Madrid' );


//
//  Templates Smarty
//
// SMARTY_DIR: Constante usada internamente por Smarty - No eliminar!!!
define( 'SMARTY_DIR', $webBasePath.'/vendor/composer/smarty/smarty/libs/' );
$conf->createSetupValue( 'smarty:configPath', $appTmpPath.'/smarty/conf' );
$conf->createSetupValue( 'smarty:compilePath', $appTmpPath.'/smarty/compile' );
$conf->createSetupValue( 'smarty:cachePath', $appTmpPath.'/smarty/cache' );
$conf->createSetupValue( 'smarty:tmpPath', $appTmpPath.'/smarty/tmp' );


//
// Backups
//
$conf->createSetupValue( 'script:backupPath', $appBasePath.'/backups/' ); //backups directory


//
//  Logs
//
// Importante para syslog: Configurar 'project:idName' o 'logs:idName'
$conf->createSetupValue( 'logs:type', 'file' ); // file, syslog, disable
$conf->createSetupValue( 'logs:path', $appBasePath.'/log' ); // log files directory
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
$conf->createSetupValue( 'mod:form:tmpPath', $appTmpPath.'/formFiles' );


//
//  Filedata Mod
//
$conf->createSetupValue( 'mod:filedata:filePath', $conf->getSetupValue( 'setup:prjBasePath' ).'/formFiles' );
$conf->createSetupValue( 'mod:filedata:cachePath', $webBasePath.'/cgmlImg' );
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


//
// URL alias controller: Fichero que contiene una clase UrlAliasController con un metodo getAlternative
//
if( defined('COGUMELO_DIST_LOCATION') && COGUMELO_DIST_LOCATION ) {
  $conf->createSetupValue( 'urlAliasController:classFile',
    COGUMELO_DIST_LOCATION.'/distModules/geozzy/classes/controller/UrlAliasController.php' );
}


//
// Errores HTTP 403 y 404
//   View = View que se muestra como aviso del error
//
$conf->createSetupValue( 'urlError403:view', 'PageErrorView::page403' );
$conf->createSetupValue( 'urlError404:view', 'PageErrorView::page404' );


//
// Dependences PATH
//
$conf->createSetupValue( 'dependences', [
  'composerPath' => $webBasePath.'/vendor/composer',
  'bowerPath' => $webBasePath.'/vendor/bower',
  'yarnPath' => $webBasePath.'/vendor/yarn',
  'manualPath' => $webBasePath.'/vendor/manual',
  'manualRepositoryPath' => COGUMELO_LOCATION.'/packages/vendorPackages'
]);


//
//  i18n
//
$conf->createSetupValue( 'i18n', [
  'path' => $appBasePath.'/conf/i18n',
  'localePath' => $appBasePath.'/conf/i18n/locale',
  'gettextUpdate' => true // update gettext files when working in localhost
]);


unset( $webBasePath, $appBasePath, $appTmpPath );
