<?php

// Project location
define( 'WEB_BASE_PATH', getcwd() );
define( 'APP_BASE_PATH', getcwd().'/../app' );

define( 'SITE_PATH', APP_BASE_PATH.'/' );
// Include cogumelo core Location
set_include_path( '.:'.SITE_PATH );

require_once( 'conf/setup.php' );


// We check that the conexion comes from localhost
if( $_SERVER['REMOTE_ADDR'] != 'local_shell' && isset( $_SERVER['REMOTE_ADDR'] ) &&  isPrivateIp( $_SERVER['REMOTE_ADDR'] ) ) {
  require_once( COGUMELO_LOCATION.'/coreClasses/CogumeloClass.php' );
  require_once( COGUMELO_LOCATION.'/coreClasses/coreController/DependencesController.php' );
  require_once( SITE_PATH.'/Cogumelo.php' );

  $par = $_GET['q'];
  switch( $par ) {
    case 'rotate_logs':
      $dir = SITE_PATH.'log/';
      $handle = opendir( $dir );
      while( $file = readdir( $handle ) ) {
        if( is_file( $dir.$file ) ) {
          $file = $dir.$file;
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
      $dir = SITE_PATH.'tmp/templates_c';
      $dirElements = scandir( $dir );
      if( is_array( $dirElements ) && count( $dirElements ) > 0 ) {
        foreach( $dirElements as $dirElement ) {
          if( $dirElement != '.' && $dirElement != '..' && is_file( $dir.'/'.$dirElement ) ) {
            unlink( $dir.'/'.$dirElement );
          }
        }
      }

      $dir = MOD_FORM_FILES_CACHE_PATH;
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
