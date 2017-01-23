<?php
global $GEOZZY_API_DOC_URLS;
$GEOZZY_API_DOC_URLS = array();

define( 'GEOZZY_API_ACTIVE', true);

user::autoIncludes();
$useraccesscontrol = new UserAccessController();


//$GEOZZY_API_DOC_URLS = array_merge( $GEOZZY_API_DOC_URLS, admin::getGeozzyDocAPI() );
geozzyAPI::addModuleAPI( 'admin' );
geozzyAPI::addModuleAPI( 'rextCommunity' );
geozzyAPI::addModuleAPI( 'rextFavourite' );
geozzyAPI::addModuleAPI( 'rextTravelPlanner' );
//geozzyAPI::addModuleAPI( 'geozzy' );

// APIs que requieren un usuario logueado
if( $useraccesscontrol->isLogged() ) {
  $GEOZZY_API_DOC_URLS = array_merge(
    $GEOZZY_API_DOC_URLS,
    array(
      /*array(
        'path'=> '/doc/favourites.json',
        'description' => 'Favourites API'
      ),*/
      /*array(
        'path'=> '/doc/community.json',
        'description' => 'Community API'
      ),*/
      /*array(
        'path'=> '/doc/travelplanner.json',
        'description' => 'TravelPlanner API'
      )*/
    )
  );
}



// APIs que no requieren un usuario logueado
$GEOZZY_API_DOC_URLS =  array_merge(
  $GEOZZY_API_DOC_URLS,
  array(

    /* array(
      'path' => '/doc/uiEventList.json',
      'description' => 'UI Events'
    ),*/
    array(
      'path'=> '/doc/explorer.json',
      'description' => 'Explorer API'
    ),
    array(
      'path'=> '/doc/explorerList.json',
      'description' => 'Explorer List API'
    ),
    array(
      'path'=> '/doc/story.json',
      'description' => 'Stories API'
    ),
    array(
      'path'=> '/doc/storyList.json',
      'description' => 'Stories List API'
    ),
    array(
      'path'=> '/doc/comments.json',
      'description' => 'Comments API'
    ),
    array(
      'path'=> '/doc/commentList.json',
      'description' => 'Comment List API'
    ),
    array(
      'path'=> '/doc/routes.json',
      'description' => 'Routes API'
    )
  )
);
