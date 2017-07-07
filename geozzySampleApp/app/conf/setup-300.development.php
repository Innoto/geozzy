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
// cogumeloScript Url settings
//
$conf->setSetupValue( 'script:cogumeloServerUrl', 'http://geozzySampleApp/cogumelo-server.php' );


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
