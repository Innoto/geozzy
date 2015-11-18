<?php

// Configuracion inicial
require_once('setup.default.php');


if( file_exists( APP_BASE_PATH . '/conf/setup.final.php' ) ) {
  define( 'IS_DEVEL_ENV', false );
  require_once('setup.final.php');
}
else {
  define( 'IS_DEVEL_ENV', true );
  require_once('setup.dev.php');
}

