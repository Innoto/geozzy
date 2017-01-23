<?php
global $GEOZZY_API_DOC_URLS;
define( 'GEOZZY_API_ACTIVE', true);

geozzyAPI::addModuleAPI( 'admin' );
geozzyAPI::addModuleAPI( 'rextCommunity' );
geozzyAPI::addModuleAPI( 'rextFavourite' );
geozzyAPI::addModuleAPI( 'rextTravelPlanner' );
geozzyAPI::addModuleAPI( 'geozzy' );
geozzyAPI::addModuleAPI( 'explorer' );
geozzyAPI::addModuleAPI( 'rextComment' );




// APIs que no requieren un usuario logueado
$GEOZZY_API_DOC_URLS =  array_merge(
  $GEOZZY_API_DOC_URLS,
  array(

    array(
      'path'=> '/doc/routes.json',
      'description' => 'Routes API'
    )

  )
);
