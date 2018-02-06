<?php

Cogumelo::load("coreController/Module.php");
require_once APP_BASE_PATH.'/conf/inc/geozzyAdmin.php';

class admin extends Module {

  public $name = "admin";
  public $version = 1.7;

  public $dependences = array(
    array(
     "id" =>"underscore",
     "params" => array("underscore@1.8.3"),
     "installer" => "yarn",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone@1.1.2"),
     "installer" => "yarn",
     "includes" => ['backbone.js']
    ),
    array(
     "id" => "font-awesome",
     "params" => array("font-awesome"),
     "installer" => "yarn",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"html5shiv",
     "params" => array("html5shiv"),
     "installer" => "yarn",
     "includes" => array("dist/html5shiv.js")
    ),
    array(
     "id" =>"respond",
     "params" => array("respond.js"),
     "installer" => "yarn",
     "includes" => array("src/respond.js")
    ),
    array(
     "id" =>"metismenu",
     "params" => array("metismenu"),
     "installer" => "yarn",
     "includes" => array("dist/metisMenu.min.css", "dist/metisMenu.min.js")
    ),
    array(
     "id" =>"raphael",
     "params" => array("raphael"),
     "installer" => "yarn",
     "includes" => array("raphael.min.js")
    ),
    array(
      "id" =>"select2",
      "params" => array("select2@4"),
      "installer" => "yarn",
      "includes" => array("dist/js/select2.full.js", "dist/css/select2.css")
    ),
    array(
      "id" =>"placeholders",
      "params" => array("jquery-placeholder"),
      "installer" => "yarn",
      "includes" => array("dist/placeholders.jquery.min.js")
    ),
    array(
      "id" =>"nestable2",
      "params" => array("nestable2-old"),
      "installer" => "yarn",
      "includes" => array("jquery.nestable.js")
    ),
    array(
      "id" =>"multiList",
      "params" => array("multilist-innoto"),
      "installer" => "yarn",
      "includes" => [ 'multiList.min.js', 'multiList.css' ]
    ),
    array(
      "id" =>"switchery",
      "params" => array("switchery"),
      "installer" => "yarn",
      "includes" => array("dist/switchery.min.js", "dist/switchery.min.css")
    ),
    /*array(
      "id" =>"raleway",
      "params" => array("raleway"),
      "installer" => "bower",
      "includes" => array("raleway.css")
    ),*/
    array(
      "id" =>"raleway",
      "params" => array("raleway-webfont"),
      "installer" => "yarn",
      "includes" => array("raleway.css")
    ),
    array(
     'id' =>'moment',
     'params' => array( 'moment' ),
     'installer' => 'yarn',
     'includes' => array( 'min/moment-with-locales.min.js' )
    ),
    array(
     "id" =>"eonasdan-bootstrap-datetimepicker",
     "params" => array("eonasdan-bootstrap-datetimepicker@4.17.44"),
     "installer" => "yarn",
     "includes" => array("build/css/bootstrap-datetimepicker.min.css", "build/js/bootstrap-datetimepicker.min.js")
    ),
    array(
     "id" =>"moment-timezone",
     "params" => array("moment-timezone"),
     "installer" => "yarn",
     "includes" => array("builds/moment-timezone-with-data.min.js")
    ),
    array( // required by elFinder
      "id" =>"jquery-ui",
      "params" => array("jquery-ui@1.12.1"),
      "installer" => "yarn",
      "includes" => [ 'jquery-ui.min.js', '/themes/smoothness/jquery-ui.min.css' ]
    ),
    array(
     'id' => 'elfinder',
     'params' => array('studio-42/elfinder', '2.1.17'),
     'installer' => 'composer',
     'includes' => array('php/autoload.php', 'js/elfinder.min.js', 'css/elfinder.min.css', 'css/theme.css' )
    )
  );



  public $includesCommon = array(
    'styles/admin.less',
    'js/router/AdminRouter.js',
    'js/model/TaxonomygroupModel.js',
    'js/model/TaxonomytermModel.js',
    'js/model/TopicModel.js',
    'js/model/ResourceModel.js',
    'js/collection/CategoryCollection.js',
    'js/collection/CategorytermCollection.js',
    'js/collection/TopicCollection.js',
    'js/collection/StarredCollection.js',
    'js/collection/MenuCollection.js',
    'js/collection/ResourcesStarredCollection.js',
    'js/app.js',
    'js/adminFileUploader.js',
    'js/view/AdminView.js',
    'js/view/CategoryEditorView.js',
    'js/view/MenuEditorView.js',
    'js/view/ResourcesStarredListView.js'
  );

  public function __construct() {
    $this->addUrlPatterns( '#^admin$#', 'view:AdminViewMaster::commonAdminInterface' );
    $this->addUrlPatterns( '#^admin/home$#', 'view:AdminViewMaster::homePage' );
    $this->addUrlPatterns( '#^admin/charts$#', 'view:AdminViewBi::dashboard' );
    $this->addUrlPatterns( '#^admin/403#', 'view:AdminViewMaster::accessDenied' );
    $this->addUrlPatterns( '#^admin/multilist$#', 'view:AdminViewMultiList::main' );

    $this->addUrlPatterns( '#^admin/alltables$#', 'view:AdminViewStatic::allTables' );
    $this->addUrlPatterns( '#^admin/addcontent$#', 'view:AdminViewStatic::addContent' );

    $this->addUrlPatterns( '#^admin/logout$#', 'view:AdminViewMaster::sendLogout' );
    $this->addUrlPatterns( '#^admin/login$#', 'view:AdminViewLogin::main' );
    $this->addUrlPatterns( '#^admin/senduserlogin$#', 'view:AdminViewLogin::sendLoginForm' );
    $this->addUrlPatterns( '#^admin/user/edit/(.*)$#', 'view:AdminViewUser::editUser' );
    $this->addUrlPatterns( '#^admin/user/show$#', 'view:AdminViewUser::showUser' );
    $this->addUrlPatterns( '#^admin/user/list$#', 'view:AdminViewUser::listUsers' );
    $this->addUrlPatterns( '#^admin/user/table$#', 'view:AdminViewUser::listUsersTable' );
    $this->addUrlPatterns( '#^admin/user/create$#', 'view:AdminViewUser::createUser' );
    $this->addUrlPatterns( '#^admin/user/senduser$#', 'view:AdminViewUser::sendUserForm' );
    $this->addUrlPatterns( '#^admin/user/changepassword$#', 'view:AdminViewUser::changeUserPasswordForm' );
    $this->addUrlPatterns( '#^admin/user/assignroles$#', 'view:AdminViewUser::assignaUserRolesForm' );

    $this->addUrlPatterns( '#^admin/resource/list$#', 'view:AdminViewResource::listResources' );
    $this->addUrlPatterns( '#^admin/resource/table$#', 'view:AdminViewResource::listResourcesTable' );

    $this->addUrlPatterns( '#^admin/resourceintopic/list/(.*)$#', 'view:AdminViewResourceInTopic::listResourcesInTopic' );
    $this->addUrlPatterns( '#^admin/resourceintopic/table/(.*)$#', 'view:AdminViewResourceInTopic::listResourcesInTopicTable' );
    $this->addUrlPatterns( '#^admin/resourceouttopic/list/(.*)$#', 'view:AdminViewResourceOutTopic::listResourcesOutTopic' );
    $this->addUrlPatterns( '#^admin/resourceouttopic/table/(.*)$#', 'view:AdminViewResourceOutTopic::listResourcesOutTopicTable' );

    $this->addUrlPatterns( '#^admin/resourcepage/list$#', 'view:AdminViewPage::listResourcesPage' );
    $this->addUrlPatterns( '#^admin/resourcepage/table$#', 'view:AdminViewPage::listResourcesPageTable' );

    $this->addUrlPatterns( '#^admin/starred/(.*)/assign$#', 'view:AdminViewStarred::listAssignStarred' );
    $this->addUrlPatterns( '#^admin/starred/table/(\d+)$#', 'view:AdminViewStarred::listStarredTable' );

    $this->addUrlPatterns( '#^admin/resource/create/(.*)$#', 'view:AdminViewResource::resourceForm' );
    $this->addUrlPatterns( '#^admin/resource/edit/(.*)$#', 'view:AdminViewResource::resourceEditForm' );
    $this->addUrlPatterns( '#^admin/resource/sendresource$#', 'view:AdminViewResource::sendResourceForm' );

    $this->addUrlPatterns( '#^admin/resourcetypeurl/create$#', 'view:AdminViewResource::resourceTypeUrlForm' );
    $this->addUrlPatterns( '#^admin/resourcetypeurl/edit/(\d+)$#', 'view:AdminViewResource::resourceEditForm' );
    $this->addUrlPatterns( '#^admin/resourcetypeurl/sendresource$#', 'view:AdminViewResource::sendModalResourceForm' );

    $this->addUrlPatterns( '#^admin/resourcetypefile/create$#', 'view:AdminViewResource::resourceTypeFileForm' );
    $this->addUrlPatterns( '#^admin/resourcetypefile/edit/(\d+)$#', 'view:AdminViewResource::resourceEditForm' );
    $this->addUrlPatterns( '#^admin/resourcetypefile/sendresource$#', 'view:AdminViewResource::sendModalResourceForm' );

    $this->addUrlPatterns( '#^admin/resmultifile/create#', 'view:AdminViewResource::resourceTypeMultiFileForm' );
    $this->addUrlPatterns( '#^admin/resmultifile/sendresource$#', 'view:AdminViewResource::sendModalMultiFileForm' );

    $this->addUrlPatterns( '#^admin/collection/create/(.+)$#', 'view:AdminViewCollection::createForm' );
    $this->addUrlPatterns( '#^admin/collection/edit/(\d+)/(.+)$#', 'view:AdminViewCollection::editForm' );
    $this->addUrlPatterns( '#^admin/collection/sendcollection$#', 'view:AdminViewCollection::sendCollectionForm' );

    $this->addUrlPatterns( '#^admin/role/edit/(.*)$#', 'view:AdminViewRole::editRole' );
    $this->addUrlPatterns( '#^admin/role/create$#', 'view:AdminViewRole::createRole' );
    $this->addUrlPatterns( '#^admin/role/list$#', 'view:AdminViewRole::listRoles' );
    $this->addUrlPatterns( '#^admin/role/table$#', 'view:AdminViewRole::listRolesTable' );
    $this->addUrlPatterns( '#^admin/role/sendrole$#', 'view:AdminViewRole::sendRoleForm' );

    $this->addUrlPatterns( '#^admin/category/(\d+)/term/create$#', 'view:AdminViewTaxonomy::categoryForm' );
    $this->addUrlPatterns( '#^admin/category/(\d+)/term/edit/(\d+)$#', 'view:AdminViewTaxonomy::categoryForm' );
    $this->addUrlPatterns( '#^admin/category/term/sendcategoryterm$#', 'view:AdminViewTaxonomy::sendCategoryForm' );

    $this->addUrlPatterns( '#^admin/menu/term/create$#', 'view:AdminViewTaxonomy::menuForm' );
    $this->addUrlPatterns( '#^admin/menu/term/edit/(\d+)$#', 'view:AdminViewTaxonomy::menuForm' );
    $this->addUrlPatterns( '#^admin/menu/term/sendmenuterm$#', 'view:AdminViewTaxonomy::sendCategoryForm' );

    $this->addUrlPatterns( '#^admin/comment/list$#', 'view:AdminViewComment::listComments' );
    $this->addUrlPatterns( '#^admin/comment/table$#', 'view:AdminViewComment::listCommentsTable' );
    $this->addUrlPatterns( '#^admin/suggestion/list$#', 'view:AdminViewComment::listSuggestions' );
    $this->addUrlPatterns( '#^admin/suggestion/table$#', 'view:AdminViewComment::listSuggestionsTable' );

    $this->addUrlPatterns( '#^admin/topics$#', 'view:AdminViewTopic::topicsSync' );

    // elFinder (file manager)
    $this->addUrlPatterns( '#^admin/filemanagerfrontend#', 'view:AdminViewElfinder::fileManagerFrontend' );
    $this->addUrlPatterns( '#^admin/filemanagerbackend#', 'view:AdminViewElfinder::fileManagerBackend' );

    // translates (export)
    $this->addUrlPatterns( '#^admin/translates/export/resources$#', 'view:AdminViewTranslates::resourcesExportView' );
    $this->addUrlPatterns( '#^admin/translates/export/collections$#', 'view:AdminViewTranslates::collectionsExportView' );
    $this->addUrlPatterns( '#^admin/translates/export/resourcesexport(.*)$#', 'view:AdminViewTranslates::resourcesExport' );
    $this->addUrlPatterns( '#^admin/translates/export/collectionsexport(.*)$#', 'view:AdminViewTranslates::collectionsExport' );
    // translates (import)
    $this->addUrlPatterns( '#^admin/translates/import/files$#', 'view:AdminViewTranslates::filesImportView' );
    $this->addUrlPatterns( '#^admin/translates/import/filesimport(.*)$#', 'view:AdminViewTranslates::filesImport' );
  }


  function setGeozzyUrlPatternsAPI() {
    //user::autoIncludes();
    user::load('controller/UserAccessController.php');

    $useraccesscontrol = new UserAccessController();
    // APIs que requieren un usuario logueado con permisos admin:access o admin:full
    if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) )  {
      // data Admin API
      $this->addUrlPatterns( '#^api/admin/categoryterms(\?.*|\/.*)$#', 'view:AdminDataAPIView::categoryTerms' );
      $this->addUrlPatterns( '#^api/doc/admin/adminCategoryterms.json$#', 'view:AdminDataAPIView::categoryTermsJson' ); // Swagger
      $this->addUrlPatterns( '#^api/admin/categories$#', 'view:AdminDataAPIView::categories' );
      $this->addUrlPatterns( '#^api/doc/admin/adminCategories.json$#', 'view:AdminDataAPIView::categoriesJson' ); // Swagger
      $this->addUrlPatterns( '#^api/admin/menuterms(\?.*|/.*)?$#', 'view:AdminDataAPIView::menuTerms' );
      $this->addUrlPatterns( '#^api/doc/admin/adminMenuterms.json$#', 'view:AdminDataAPIView::menuTermsJson' ); // Swagger
      $this->addUrlPatterns( '#^api/admin/resourcesTerm/(.*)$#', 'view:AdminDataAPIView::resourcesTerm' );
      $this->addUrlPatterns( '#^api/doc/admin/adminResourcesTerm.json$#', 'view:AdminDataAPIView::resourcesTermJson' ); // Swagger
      $this->addUrlPatterns( '#^api/admin/starred$#', 'view:AdminDataAPIView::starred' );
      $this->addUrlPatterns( '#^api/doc/admin/adminStarred.json$#', 'view:AdminDataAPIView::starredJson' ); // Swagger
    }

  }

  function getGeozzyDocAPI() {
    $ret = [];

    //user::autoIncludes();
    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();


    if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) ) {
      $ret = array(
        array(
          'path' => '/doc/admin/adminCategories.json',
          'description' => 'Admin Categories'
        ),
        array(
          'path' => '/doc/admin/adminCategoryterms.json',
          'description' => 'Admin CategoryTerms'
        ),
        array(
          'path' => '/doc/admin/adminMenuterms.json',
          'description' => 'Admin MenuTerms'
        ),
        array(
          'path' => '/doc/admin/adminResourcesTerm.json',
          'description' => 'Admin ResourcesTerm'
        ),
        array(
          'path' => '/doc/admin/adminStarred.json',
          'description' => 'Admin StarredTerms'
        )
      );


    }



    return $ret;
  }


}
