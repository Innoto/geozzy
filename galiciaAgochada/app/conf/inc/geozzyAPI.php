<?php
global $GEOZZY_API_DOC_URLS;
$GEOZZY_API_DOC_URLS = array();

define( 'GEOZZY_API_ACTIVE', true);

user::autoIncludes();
$useraccesscontrol = new UserAccessController();


// APIs que requieren un usuario logueado con permisos admin:access o admin:full
if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) ){
  $GEOZZY_API_DOC_URLS_ADMIN = array(
    array(
      'path' => '/doc/admin/adminCategories.json',
      'description' => 'Admin Categories'
    ),
    array(
      'path' => '/doc/admin/adminCategoryterms.json',
      'description' => 'Admin CategoryTerms'
    ),
    array(
      'path' => '/doc/admin/adminResourcesTerm.json',
      'description' => 'Admin ResourcesTerm'
    ),
    array(
      'path' => '/doc/admin/adminStarred.json',
      'description' => 'Admin StarredTerms'
    ),
    array(
      'path' => '/doc/admin/adminCommentSuggestion.json',
      'description' => 'Admin Comments and Suggestions'
    ),
    array(
      'path' => '/admin/adminStories.json',
      'description' => 'Admin Stories'
    ),
    array(
      'path' => '/admin/adminStorySteps.json',
      'description' => 'Admin Story Steps'
    )
  );
}
else {
  $GEOZZY_API_DOC_URLS_ADMIN = array();
}
$GEOZZY_API_DOC_URLS = array_merge( $GEOZZY_API_DOC_URLS, $GEOZZY_API_DOC_URLS_ADMIN );



// APIs que requieren un usuario logueado
if( $useraccesscontrol->isLogged() ) {
  $GEOZZY_API_DOC_URLS = array_merge(
    $GEOZZY_API_DOC_URLS,
    array(
      array(
        'path'=> '/doc/favourites.json',
        'description' => 'Favourites API'
      ),
      array(
        'path'=> '/doc/community.json',
        'description' => 'Community API'
      ),
      array(
        'path'=> '/doc/travelplanner.json',
        'description' => 'TravelPlanner API'
      )
    )
  );
}



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
