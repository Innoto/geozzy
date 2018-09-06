<?php

// Project location
define( 'WEB_BASE_PATH', getcwd() ); // Apache DocumentRoot
define( 'PRJ_BASE_PATH', realpath( WEB_BASE_PATH.'/..' ) ); // Project Path (normalmente contiene app/ httpdocs/ formFiles/)
define( 'APP_BASE_PATH', PRJ_BASE_PATH.'/app' ); // App Path



// Include cogumelo core Location
set_include_path( '.:'.APP_BASE_PATH );

require_once( 'conf/setup.php' );


require_once( COGUMELO_LOCATION.'/coreClasses/CogumeloClass.php' );
require_once( APP_BASE_PATH.'/Cogumelo.php' );

// resolving vendor includes
$dependencesControl = new DependencesController();
$dependencesControl->loadCogumeloIncludes();

// error & warning handlers
set_error_handler( 'Cogumelo::warningHandler' );
register_shutdown_function( 'Cogumelo::errorHandler' );
if( !Cogumelo::getSetupValue( 'logs:error' ) ) {
  ini_set( 'display_errors', 0 );
}


global $_C;
$_C = Cogumelo::get();

// Cogumelo first access check
if( file_exists( APP_BASE_PATH . '/conf/accessCheckIndex.php' ) ) {
  require_once( APP_BASE_PATH . '/conf/accessCheckIndex.php' );
}

$_C->exec();

