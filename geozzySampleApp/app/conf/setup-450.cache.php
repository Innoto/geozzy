<?php

//
//  CACHE (Service)
//


// $conf->setSetupValue( 'cogumelo:cache:redis', [
//   'host' => 'localhost',
//   'port' => '6379',
//   'database' => false,
//   'auth' => false,
//   'expirationTime' => 15,
//   // 'subPrefix' => 'projetcIdName'
// ]);



$conf->setSetupValue( 'cogumelo:cache:memcached', [
  'hostArray' => [
    'localhost' => [
      'host' => 'localhost',
      'port' => '11211',
    ],
  ],
  'expirationTime' => 15,
  // 'subPrefix' => 'projetcIdName'
]);



//
// Activando cacheos y sus tiempos. 1h: 3600, 15m: 900, true: 15
//
// $cacheNormal = 900;
// $conf->setSetupValue( 'cache:ResourceController:default', true );
// $conf->setSetupValue( 'cache:ResourceController:getViewBlockInfo', $cacheNormal );
// $conf->setSetupValue( 'cache:ExplorerController:default', $cacheNormal );
// $conf->setSetupValue( 'cache:UrlAliasController', $cacheNormal );
//
// $conf->setSetupValue( 'cache:PageHome', $cacheNormal );
//
// $conf->setSetupValue( 'cache:Filedata', 300 );
// $conf->setSetupValue( 'cache:geozzyAPIView', 600 );
// $conf->setSetupValue( 'cache:RExtFavouriteController', 60 ); // Tambien sobreescribe el valor heredado del ResourceController


