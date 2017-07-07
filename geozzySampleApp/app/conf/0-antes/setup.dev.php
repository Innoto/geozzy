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
// Framework Path
//
define( 'COGUMELO_LOCATION', '/home/proxectos/cogumelo' );
define( 'COGUMELO_DIST_LOCATION', '/home/proxectos/geozzy');


//
// Public Access User
//
//cogumeloSetSetupValue( 'globalAccessControl', array(
//  'user' => 'proyecta',
//  'password', 'py2User'
//));


//
// cogumeloScript Url settings
//
cogumeloSetSetupValue( 'script:cogumeloServerUrl', 'http://profesores/cogumelo-server.php' );



cogumeloSetSetupValue( 'logs:rawSql', true ); // Log RAW all SQL ¡WARNING! application passwords will dump into log files



//
// DB
//
cogumeloSetSetupValue( 'db', array(
  'engine' => 'mysql',
  'hostname' => 'localhost',
  'port' => '3306',
  'user' => 'profesores',
  'password' => 'gB.73jderaTQnH-9',
  'name' => 'profesores',
  'mysqlGroupconcatMaxLen' => 4294967295,
  'allowCache' => true
));


//
// Media server
//
cogumeloSetSetupValue( 'mod:mediaserver', array(
  'productionMode' => false, // If true, you must compile less manually with ./cogumelo generateClientCaches
  'notCacheJs' => true,
  'host' => '/', // Ej: '/' o 'http://media.webgeozzy:84/'
  'path' => 'media',
  'cachePath' => 'mediaCache',
  'tmpCachePath' => cogumeloGetSetupValue( 'setup:appTmpPath' ).'/mediaCache',
  'minimifyFiles' => true, // for js, less and css files ( only when "productionMode" is true )
  'minimifyPage' => true, // for the php generated Page
));
