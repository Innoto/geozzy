<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  PRJ_BASE_PATH - Project Path (normalmente contiene app/ httpdocs/ formFiles/) (en index.php)
  APP_BASE_PATH - App Path (en index.php)
*/

// IS_DEVEL_ENV  - Indica si estamos en el entorno de desarrollo (en setup.php)
define( 'IS_DEVEL_ENV', !file_exists( APP_BASE_PATH . '/conf/setup-000.paths.production.php' ) );

( IS_DEVEL_ENV ) ? require_once( 'setup-000.paths.development.php' ) : require_once( 'setup-000.paths.production.php' );


require_once( COGUMELO_LOCATION.'/coreClasses/coreController/SetupMethods.php' );
$conf = new SetupMethods();


// Configuracion inicial
require_once( 'setup-100.init.php' );


if( IS_DEVEL_ENV ) {
  require_once( 'setup-300.development.php' );
  if( file_exists('setup-400.development-personal.php') ) {
    require_once('setup-400.development-personal.php');
  }
}
else {
  require_once( 'setup-300.production.php' );
}


// Configuracion proyecto
require_once( 'setup-500.project.php' );


// Configuracion por defecto
require_once( 'setup-800.defaults.php' );


// Configuracion por defecto
require_once( 'setup-999.last.php' );







// Destruimos $conf como acceso a los metodos durante el setup.
// Cogumelo aporta los metodos despues del setup.
unset( $conf );
