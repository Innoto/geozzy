<?php

// Creo un bloque para evitar la visibilidad de las variables
if( true ) {

  $accessValid = false;

  $privateUrlPatterns = array(
    '#^sitemap.xml$#'
  );


  $user = 'gaUser';
  $pass = 'gz15005';

  $validIp = array(
    '213.60.18.106', // Innoto
    '176.83.204.135', '91.117.124.2', // ITG
    '91.116.191.224', // Zadia
    '127.0.0.1'
  );

  $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];

  $langsConf = cogumeloGetSetupValue( 'lang:available' );
  $patron = is_array( $langsConf ) ? implode( '|', array_keys( $langsConf ) ) : cogumeloGetSetupValue( 'lang:default' );
  $urlFull = ltrim( $_SERVER['REQUEST_URI'], '/' ); // $_SERVER["REDIRECT_URL"]
  $url = preg_replace( '/^'.$patron.'\/?/', '', $urlFull );

  if( $url !== preg_replace( $privateUrlPatterns, '', $url ) ) {
    // La url actual coincide con alguno de los patrones

    error_log( 'conf/accessCheck.php - Control de acceso para URL: '.$url );

    if( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 ) {
      // Se permite al acceso por IP
      $accessValid = true;
    }
    else {
      if(
        ( !isset( $_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] != $user ) &&
        ( !isset( $_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] != $pass ) )
      {
        error_log( 'BLOQUEO --- Acceso Denegado!!!' );
        header('WWW-Authenticate: Basic realm="Galicia Agochada"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acceso Denegado.';
        // exit;
      }
      else {
        // Se permite al acceso por USUARIO
        $accessValid = true;
      }
    }
  }
  else {
    // La url actual no coincide con ninguno de los patrones
    $accessValid = true;
  }


  if( !$accessValid ) {
    die;
  }

}