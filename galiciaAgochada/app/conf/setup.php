<?php
define( 'IS_DEVEL_ENV', !file_exists( APP_BASE_PATH . '/conf/setup.final.php' ) );
require_once( 'setup.api.php' );

// Configuracion inicial
require_once( 'setup.default.php' );

if( IS_DEVEL_ENV ) {
  require_once( 'setup.dev.php' );
}
else {
  require_once( 'setup.final.php' );
}
