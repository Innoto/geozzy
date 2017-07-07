<?php

// Importante: Cogumelo ya se ha cargado

// Creo un bloque para evitar la visibilidad de las variables
if( true ) {

  $accessValid = false;

  $accessUser = Cogumelo::getSetupValue( 'globalAccessControl:user' );
  if( $accessUser && $accessUser !== '' ) {
    $accessPass = Cogumelo::getSetupValue( 'globalAccessControl:password' );

    $validIp = array(
      '213.60.18.106', // Innoto
      '127.0.0.1'
    );

    $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
    if( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 ) {
      $accessValid = true;
    }
    else {
      if(
        ( !isset( $_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $accessUser ) &&
        ( !isset( $_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] !== $accessPass ) )
      {
        error_log( 'BLOQUEO --- Acceso Denegado en '.$_SERVER['REQUEST_URI'] );
        header('WWW-Authenticate: Basic realm="Acceso limitado"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acceso Denegado.';
        exit;
      }
      else {
        $accessValid = true;
      }
    }
  }
  else {
    $accessValid = true;
  }

  if( !$accessValid ) {
    header('HTTP/1.0 403 Forbidden');
    error_log( 'accessCheckIndex: die 403 ('.$_SERVER['REQUEST_URI'].')' );
    die('Invalid User');
  }
}


