<?php

if( file_exists( APP_BASE_PATH . '/conf/setup.final.php' ) ) {
  define( 'IS_DEVEL_ENV', false );
  require_once('setup.final.php');
}
else {
  define( 'IS_DEVEL_ENV', true );
  require_once('setup.dev.php');
}



// if( develEnviroment() ) {



/*
function develEnviroment() {
  $develEnv = false;

  if( isset( $_SERVER['REMOTE_ADDR'] ) && $_SERVER['REMOTE_ADDR'] != 'local_shell' ) {
    if( isPrivateIp( $_SERVER['REMOTE_ADDR'] ) ) {
      $develEnv = true;
    }
  }
  else {
    // ESTO HAI QUE REPASALO !!!!
    $ipLocal = gethostbyname( gethostname() );
    error_log( 'IP LOCAL: '. $ipLocal );
    if( isPrivateIp( $ipLocal ) ) {
      $develEnv = true;
    }
  }

  return $develEnv;
}


function isPrivateIp( $ip ) {
  return( strpos( $ip, '127.' ) === 0 || !filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) );
}
*/