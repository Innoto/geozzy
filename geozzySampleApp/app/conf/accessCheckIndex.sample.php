<?php

// Creo un bloque para evitar la visibilidad de las variables
if( true ) {

  $accessValid = false;

  $user = 'accessUser';
  $pass = 'accessPassword';

  // $privateUrlPatterns = array(
  //   '#^sitemap.xml$#',
  // );

  $publicUrlPatterns = array(
    '#^/?cgmlImg/#',
    '#^/?cgmlformfilewd/\d+/.+\.kml$#',
  );

  $validIp = array(
    '213.60.18.106', // Innoto
    '127.0.0.1',
  );


  $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
  // Control por IP
  if( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 ) {
    // Damos acceso por IP
    Cogumelo::log( 'Acceso OK: IP OK '.$_SERVER['REQUEST_URI'], 'ACI' );
    $accessValid = true;
  }


  $url = 'No definida';
  // Control por URL
  if( !$accessValid ) {
    $langsConf = Cogumelo::getSetupValue( 'lang:available' );
    $patron = is_array( $langsConf ) ? implode( '|', array_keys( $langsConf ) ) : Cogumelo::getSetupValue( 'lang:default' );
    $urlFull = ltrim( $_SERVER['REQUEST_URI'], '/' ); // $_SERVER["REDIRECT_URL"]
    $url = preg_replace( '/^'.$patron.'\/?/', '', $urlFull );

    if( !empty( $privateUrlPatterns ) && $url === preg_replace( $privateUrlPatterns, '', $url ) ) {
      // Damos acceso a las URL que no entran en el patron de privada
      Cogumelo::log( 'Acceso OK: URL No Privada '.$url, 'ACI' );
      $accessValid = true;
    }

    if( !$accessValid && !empty( $publicUrlPatterns ) && $url !== preg_replace( $publicUrlPatterns, '', $url ) ) {
      // Damos acceso a las URL que entran en el patron de publica
      Cogumelo::log( 'Acceso OK: URL Publica '.$url, 'ACI' );
      $accessValid = true;
    }
  }


  // Control por Usuario
  if( !$accessValid ) {
    // Cogumelo::log( 'Control de Usuario para URL: '.$url, 'ACI' );
    if( !empty( $_SERVER['PHP_AUTH_USER'] ) && !empty( $_SERVER['PHP_AUTH_PW'] ) &&
      $_SERVER['PHP_AUTH_USER'] === $user && $_SERVER['PHP_AUTH_PW'] === $pass )
    {
      // Damos acceso por USUARIO
      Cogumelo::log( 'Acceso OK: User OK '.$url, 'ACI' );
      $accessValid = true;
    }
  }


  if( !$accessValid ) {
    Cogumelo::log( 'BLOQUEO - Acceso Denegado!!! '.$url, 'ACI' );
    error_log( 'BLOQUEO accessCheckIndex.php --- Acceso Denegado!!! '.$url );
    header('WWW-Authenticate: Basic realm="Zona con control de acceso"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Acceso Denegado (ACI)';
    die;
  }
  // Cogumelo::log( 'Acceso OK '.$url, 'ACI' );

}
