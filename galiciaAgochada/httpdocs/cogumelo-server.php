<?php

// Project location
define( 'WEB_BASE_PATH', getcwd() ); // Apache DocumentRoot
define( 'PRJ_BASE_PATH', realpath( WEB_BASE_PATH.'/..' ) ); // Project Path (normalmente contiene app/ httpdocs/ formFiles/)
define( 'APP_BASE_PATH', PRJ_BASE_PATH.'/app' ); // App Path
define( 'APP_TMP_PATH', APP_BASE_PATH.'/tmp' ); // Ficheros temporales



// Include cogumelo core Location
set_include_path( '.:'.APP_BASE_PATH );

require_once( 'conf/setup.php' );


// We check that the conexion comes from localhost
if( $_SERVER['REMOTE_ADDR'] != 'local_shell' && isset( $_SERVER['REMOTE_ADDR'] ) &&  isPrivateIp( $_SERVER['REMOTE_ADDR'] ) ) {
  require_once( COGUMELO_LOCATION.'/coreClasses/CogumeloClass.php' );
  require_once( COGUMELO_LOCATION.'/coreClasses/coreController/DependencesController.php' );
  require_once( APP_BASE_PATH.'/Cogumelo.php' );

  $par = $_GET['q'];
  switch( $par ) {
    case 'rotate_logs':
      $dir = Cogumelo::getSetupValue( 'logs:path' );
      $handle = opendir( $dir );
      while( $file = readdir( $handle ) ) {
        $file = $dir.'/'.$file;
        if( is_file( $file ) ) {
          $pos = strpos( $file, 'gz' );
          if( $pos === false ){
            $gzfile = $file.'-'.date( 'Ymd-Hms' ).'.gz';
            $fp = gzopen( $gzfile, 'w9' );
            gzwrite ( $fp, file_get_contents( $file ) );
            gzclose( $fp );
          }
        }
      }
      break;
    case 'flush':
      $dir = APP_TMP_PATH.'/templates_c';
      $dirElements = scandir( $dir );
      if( is_array( $dirElements ) && count( $dirElements ) > 0 ) {
        foreach( $dirElements as $dirElement ) {
          if( $dirElement != '.' && $dirElement != '..' && is_file( $dir.'/'.$dirElement ) ) {
            unlink( $dir.'/'.$dirElement );
          }
        }
      }

      $dir = Cogumelo::getSetupValue( 'mod:filedata:cachePath' );
      $dirElements = scandir( $dir );
      if( is_array( $dirElements ) && count( $dirElements ) > 0 ) {
        foreach( $dirElements as $dirElement ) {
          if( $dirElement != '.' && $dirElement != '..' && is_dir( $dir.'/'.$dirElement ) ) {
            rmdirRec( $dir.'/'.$dirElement );
          }
        }
      }

      $dir = Cogumelo::getSetupValue( 'mod:mediaserver:tmpCachePath' );
      $dirElements = scandir( $dir );
      if( is_array( $dirElements ) && count( $dirElements ) > 0 ) {
        foreach( $dirElements as $dirElement ) {
          if( $dirElement != '.' && $dirElement != '..' && is_dir( $dir.'/'.$dirElement ) ) {
            rmdirRec( $dir.'/'.$dirElement );
          }
        }
      }

      /*
      $dirHandle = opendir( $dir );
      while( $file = readdir( $dirHandle ) ) {
        if( is_file( $dir.'/'.$file ) ) {
          unlink( $dir.'/'.$file );
        }
      }
      closedir( $dirHandle );
      */
      break;
    case 'client_caches':
      Cogumelo::load( 'coreController/ModuleController.php' );
      require_once( ModuleController::getRealFilePath( 'mediaserver.php',  'mediaserver' ) );
      mediaserver::autoIncludes();
      CacheUtilsController::generateAllCaches();
      break;
  } // switch
}
else {
  header( 'HTTP/1.0 403 Forbidden' );
  echo( "You are forbidden!\n\nUnusual access to cogumelo-server\n" );
}


function rmdirRec( $dir ) {
  error_log( "rmdirRec( $dir )" );
  if( is_dir( $dir ) ) {
    $dirElements = scandir( $dir );
    if( is_array( $dirElements ) && count( $dirElements ) > 0 ) {
      foreach( $dirElements as $object ) {
        if( $object != '.' && $object != '..' ) {
          if( is_dir( $dir.'/'.$object ) ) {
            rmdirRec( $dir.'/'.$object );
          }
          else {
            unlink( $dir.'/'.$object );
          }
        }
      }
    }
    reset( $dirElements );
    rmdir( $dir );
  }
}


function isPrivateIp( $ip ) {
  return( strpos( $ip, '127.' ) === 0 || !filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) );
}
