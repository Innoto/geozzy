<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  PRJ_BASE_PATH - Project Path (normalmente contiene app/ httpdocs/ formFiles/) (en index.php)
  APP_BASE_PATH - App Path (en index.php)
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

