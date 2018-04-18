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
// Public Access User
//
//$conf->setSetupValue( 'globalAccessControl', array(
//  'user' => 'geozzySampleApp',
//  'password', 'geozzySampleApp'
//));


//
//  Devel Mod
//
// $conf->setSetupValue( 'mod:devel', [
//   'allowAccess' => true,
//   'url' => 'devel',
//   'password' => 'contraseña'
// ]);



//
// Google keys DEVELOPMENT
//
// $conf->setSetupValue( 'google:maps:key', 'A..................................Q' );
// $conf->setSetupValue( 'google:recaptcha:key:site', '6..................................T' );
// $conf->setSetupValue( 'google:recaptcha:key:secret', '6..................................d' );



//
// cogumeloScript Url settings
//
$conf->setSetupValue( 'script:cogumeloServerUrl', 'http://geozzySampleApp/cogumelo-server.php' );


//
//  Logs
//
// Importante para syslog: Configurar 'project:idName' o 'logs:idName'
$conf->setSetupValue( 'logs:type', 'file' ); // file, syslog, disable
$conf->setSetupValue( 'logs:error', true ); // Display errors on screen. If you use devel module, you might disable it
// $conf->setSetupValue( 'logs:rawSql', true ); // Log RAW all SQL ¡WARNING! Private information could be sent to the log
$conf->setSetupValue( 'logs:disableLabels', ['AccessibilityMode', 'ACI', 'cache'] ); // Disable logs ...


//
// DB
//
$conf->setSetupValue( 'db', array(
  'engine' => 'mysql',
  'hostname' => 'localhost',
  'port' => '3306',
  'user' => 'geozzySampleApp',
  'password' => 'geozzySampleApp',
  'name' => 'geozzySampleApp',
  'mysqlGroupconcatMaxLen' => 4294967295,
  'allowCache' => true
));


//
// Media server
//
$conf->setSetupValue( 'mod:mediaserver', array(
  'productionMode' => false, // If true, you must compile less manually with ./cogumelo generateClientCaches
  'notCacheJs' => true,
  'host' => '/', // Ej: '/' o 'http://media.webgeozzy:84/'
  'path' => 'media',
  'cachePath' => 'mediaCache',
  'tmpCachePath' => $conf->getSetupValue( 'setup:appTmpPath' ).'/mediaCache',
  'minimifyFiles' => true, // for js and css files ( only when "productionMode" is true )
  'minimifyPage' => false, // for the php generated Page
));
