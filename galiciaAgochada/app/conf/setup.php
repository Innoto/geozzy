<?php
/*
  Previamente se definen las siguientes constantes:

  WEB_BASE_PATH - Apache DocumentRoot (en index.php)
  PRJ_BASE_PATH - Project Path (normalmente contiene app/ httpdocs/ formFiles/) (en index.php)
  APP_BASE_PATH - App Path (en index.php)
  APP_TMP_PATH  - Ficheros temporales (en index.php)
  IS_DEVEL_ENV  - Indica si estamos en el entorno de desarrollo (en setup.php)
*/


require_once( 'setup.utils.php' );

define( 'IS_DEVEL_ENV', !file_exists( APP_BASE_PATH . '/conf/setup.final.php' ) );
cogumeloSetSetupValue( 'setup:isDevelEnv', IS_DEVEL_ENV );

// Configuracion inicial
require_once( 'setup.default.php' );


if( IS_DEVEL_ENV ) {
  require_once( 'setup.dev.php' );
}
else {
  require_once( 'setup.final.php' );
}


// Configuracion proyecto
require_once( 'setup.project.php' );

