<?php
global $GEOZZY_API_DOC_URLS;
define( 'GEOZZY_API_ACTIVE', true);
user::autoIncludes();

$useraccesscontrol = new UserAccessController();

if( $useraccesscontrol->isLogged()){
  $GEOZZY_API_DOC_URLS_ADMIN = array(
    array(
      'path' => '/admin/adminCategories.json',
      'description' => 'Admin Categories'
    ),
    array(
      'path' => '/admin/adminCategoryterms.json',
      'description' => 'Admin CategoryTerms'
    ),
    array(
      'path' => '/admin/adminResourcesTerm.json',
      'description' => 'Admin ResourcesTerm'
    ),
    array(
      'path' => '/admin/adminStarred.json',
      'description' => 'Admin StarredTerms'
    )
  );
}
else {
  $GEOZZY_API_DOC_URLS_ADMIN = array();
}



$GEOZZY_API_DOC_URLS =  array_merge(
  $GEOZZY_API_DOC_URLS_ADMIN,
  array(
    array(
      'path' => '/bi.json',
      'description' => 'BI dashboard utils'
    ),
    array(
      'path' => '/resourceTypes.json',
      'description' => 'resourceTypes'
    ),
    array(
      'path' => '/resources.json',
      'description' => 'Core Resource'
    ),
    array(
      'path' => '/resourceIndex.json',
      'description' => 'Resource index'
    ),
    array(
      'path' => '/starred.json',
      'description' => 'Starred terms with resources'
    ),
    array(
      'path' => '/categoryList.json',
      'description' => 'Category List'
    ),
    array(
      'path' => '/categoryTerms.json',
      'description' => 'CategoryTerms by category'
    ),
    array(
      'path' => '/topicList.json',
      'description' => 'Topics'
    ),
    array(
      'path' => '/userSession.json',
      'description' => 'User Session'
    ),
    /* array(
      'path' => '/uiEventList.json',
      'description' => 'UI Events'
    ),*/
    array(
      'path'=> '/explorer.json',
      'description' => 'Explorer API'
    ),
    array(
      'path'=> '/explorerList.json',
      'description' => 'Explorer List API'
    ),
    array(
      'path'=> '/comments.json',
      'description' => 'Comments API'
    ),
    array(
      'path'=> '/routes.json',
      'description' => 'Routes API'
    )
  )
);
