<?php

// Project locations
define( 'WEB_BASE_PATH', getcwd() ); // Apache DocumentRoot
define( 'PRJ_BASE_PATH', realpath( WEB_BASE_PATH.'/..' ) ); // Project Path (normalmente contiene app/ httpdocs/ formFiles/)
define( 'APP_BASE_PATH', PRJ_BASE_PATH.'/app' ); // App Path

set_include_path( '.:'.APP_BASE_PATH ); // Include cogumelo core Location

require_once( 'conf/setup.php' );


// Una vez establecido el entorno web, pasamos a carga el script principal
require_once( COGUMELO_LOCATION.'/cogumelo-server.php' );

