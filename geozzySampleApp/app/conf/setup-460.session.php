<?php

//
//  SESSION
//



//
// Session lifetime (Common)
//
$conf->createSetupValue( 'session:lifetime', 60*60 ); // 1h.
ini_set( 'session.gc_maxlifetime',  $conf->getSetupValue( 'session:lifetime' ) );
ini_set( 'session.cookie_lifetime', 365*24*60*60 ); // 1 year
ini_set( 'session.gc_probability', 1 ); // A 1% chance of running on each session_start()
ini_set( 'session.gc_divisor', 100 );



//
// Session store: FILE (default)
//
$sessionSavePath = $conf->getSetupValue( 'setup:appBasePath' ).'/php-sessions'; // Our own session save path
$conf->createSetupValue( 'session:savePath', $sessionSavePath );
session_save_path( $sessionSavePath );
unset( $sessionSavePath );
//
// Session store: FILE (END)
//



//
// Session store: REDIS
//
// ini_set( 'session.save_handler', 'redis' );

// $cacheSetup = $conf->getSetupValue('cogumelo:cache:redis');
// if( empty( $cacheSetup['host'] ) ) {
//   $cacheSetup = [
//     'host' => 'localhost',
//     'port' => '6379',
//     'database' => false,
//     'auth' => false,
//     //'subPrefix' => 'projetcIdName'
//   ];
// }

// $cacheSetup['keyPrefix'] = 'CGMLPHPSESSION';

// if( !empty( $cacheSetup['subPrefix'] ) ) {
//   $cacheSetup['keyPrefix'] .= '_'.$cacheSetup['subPrefix'];
// }
// elseif( $subPrefix=$conf->getSetupValue('project:idName') ) {
//   $cacheSetup['keyPrefix'] .= '_'.$subPrefix;
// }
// elseif( $subPrefix=$conf->getSetupValue('db:name') ) {
//   $cacheSetup['keyPrefix'] .= '_'.$subPrefix;
// }
// $cacheSetup['keyPrefix'] .= ':';

// $cacheSetup['url'] = 'tcp://'.$cacheSetup['host'];
// if( !empty( $cacheSetup['port'] ) ) {
//   $cacheSetup['url'] .= ':'.$cacheSetup['port'];
// }
// $cacheSetup['url'] .= '?prefix='.$cacheSetup['keyPrefix'];
// if( !empty( $cacheSetup['database'] ) ) {
//   $cacheSetup['url'] .= '&database='.$cacheSetup['database'];
// }
// if( !empty( $cacheSetup['auth'] ) ) {
//   $cacheSetup['url'] .= '&auth='.$cacheSetup['auth'];
// }

// $conf->createSetupValue( 'session:redis', $cacheSetup );
// session_save_path( $cacheSetup['url'] );
// unset( $cacheSetup, $subPrefix );
//
// Session store: REDIS (FIN)
//



