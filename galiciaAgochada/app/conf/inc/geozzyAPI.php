<?php
global $GEOZZY_API_DOC_URLS;
$GEOZZY_API_DOC_URLS = array();

define( 'GEOZZY_API_ACTIVE', true);


geozzyAPI::addModuleAPI( 'admin' );
geozzyAPI::addModuleAPI( 'rextFavourite' );
geozzyAPI::addModuleAPI( 'rextTravelPlanner' );




// APIs que no requieren un usuario logueado
$GEOZZY_API_DOC_URLS =  array_merge(
  $GEOZZY_API_DOC_URLS,
  array(
    array(
      'path' => '/doc/bi.json',
      'description' => 'BI dashboard utils'
    ),
    array(
      'path' => '/doc/resourceTypes.json',
      'description' => 'resourceTypes'
    ),
    array(
      'path' => '/doc/resources.json',
      'description' => 'Core Resource'
    ),
    array(
      'path' => '/doc/resourceIndex.json',
      'description' => 'Resource index'
    ),
    array(
      'path' => '/doc/collections.json',
      'description' => 'Get collections'
    ),
    array(
      'path' => '/doc/starred.json',
      'description' => 'Starred terms with resources'
    ),
    array(
      'path' => '/doc/categoryList.json',
      'description' => 'Category List'
    ),
    array(
      'path' => '/doc/categoryTerms.json',
      'description' => 'CategoryTerms by category'
    ),
    array(
      'path' => '/doc/topicList.json',
      'description' => 'Topics'
    ),
    array(
      'path' => '/doc/userLogin.json',
      'description' => 'User Login'
    ),
    array(
      'path' => '/doc/userLogout.json',
      'description' => 'User Logout'
    ),
    array(
      'path' => '/doc/userUnknownPass.json',
      'description' => 'User new password'
    ),
    array(
      'path' => '/doc/cgml-session.json',
      'description' => 'Get Cogumelo session info'
    ),
    array(
      'path' => '/doc/userSession.json',
      'description' => 'User Session'
    ),
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
