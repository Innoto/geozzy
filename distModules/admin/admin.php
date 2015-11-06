<?php

Cogumelo::load("coreController/Module.php");


class admin extends Module
{
  public $name = "admin";
  public $version = "";
  public $dependences = array(
    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    ),
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap"),
     "installer" => "bower",
     "includes" => array("dist/js/bootstrap.min.js")
    ),
    array(
     "id" => "font-awesome",
     "params" => array("Font-Awesome"),
     "installer" => "bower",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"html5shiv",
     "params" => array("html5shiv"),
     "installer" => "bower",
     "includes" => array("dist/html5shiv.js")
    ),
    array(
     "id" =>"respond",
     "params" => array("respond"),
     "installer" => "bower",
     "includes" => array("src/respond.js")
    ),
    array(
     "id" =>"metismenu",
     "params" => array("metisMenu"),
     "installer" => "bower",
     "includes" => array("dist/metisMenu.min.css", "dist/metisMenu.min.js")
    ),
    array(
     "id" =>"raphael",
     "params" => array("raphael"),
     "installer" => "bower",
     "includes" => array("raphael-min.js")
    ),
    array(
     "id" =>"select2",
     "params" => array("select2"),
     "installer" => "bower",
     "includes" => array("dist/js/select2.full.js", "dist/css/select2.css")
    ),
    array(
     "id" =>"placeholders",
     "params" => array("placeholders"),
     "installer" => "bower",
     "includes" => array("dist/placeholders.jquery.min.js")
    ),
    array(
      "id" =>"nestable2",
      "params" => array("nestable2-old"),
      "installer" => "bower",
      "includes" => array("jquery.nestable.js")
    ),
    array(
      "id" =>"multiList",
      "params" => array("multiList"),
      "installer" => "bower",
      "includes" => array("multiList.js", "multiList.css")
    ),
    array(
      "id" =>"switchery",
      "params" => array("switchery"),
      "installer" => "bower",
      "includes" => array("dist/switchery.min.js", "dist/switchery.min.css")
    ),
    array(
      "id" =>"raleway",
      "params" => array("raleway"),
      "installer" => "bower",
      "includes" => array("raleway.css")
    )
  );

  public $includesCommon = array(
    'styles/admin.less',
    'js/app.js',
    'js/views/AdminView.js',
    'js/views/CategoryEditorView.js',
    'js/views/ResourcesStarredListView.js',
    'js/routers/AdminRouter.js',
    'js/models/TaxonomygroupModel.js',
    'js/models/TaxonomytermModel.js',
    'js/models/TopicModel.js',
    'js/models/ResourceModel.js',
    'js/collections/CategoryCollection.js',
    'js/collections/CategorytermCollection.js',
    'js/collections/TopicCollection.js',
    'js/collections/StarredCollection.js',
    'js/collections/ResourcesStarredCollection.js'
  );

  public function __construct() {
    $this->addUrlPatterns( '#^admin$#', 'view:AdminViewMaster::commonAdminInterface' );
    $this->addUrlPatterns( '#^admin/charts$#', 'view:AdminViewBi::dashboard' );
    $this->addUrlPatterns( '#^admin/multilist$#', 'view:AdminViewStats::main' );

    $this->addUrlPatterns( '#^admin/alltables$#', 'view:AdminViewStatic::allTables' );
    $this->addUrlPatterns( '#^admin/addcontent$#', 'view:AdminViewStatic::addContent' );

    $this->addUrlPatterns( '#^admin/logout$#', 'view:AdminViewMaster::sendLogout' );
    $this->addUrlPatterns( '#^admin/login$#', 'view:AdminViewLogin::main' );
    $this->addUrlPatterns( '#^admin/senduserlogin$#', 'view:AdminViewLogin::sendLoginForm' );
    $this->addUrlPatterns( '#^admin/resource/list$#', 'view:AdminViewResource::listResources' );
    $this->addUrlPatterns( '#^admin/resource/table$#', 'view:AdminViewResource::listResourcesTable' );
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

    $this->addUrlPatterns( '#^admin/resourcepage/list/(.*)$#', 'view:AdminViewPage::listResourcesPage' );
    $this->addUrlPatterns( '#^admin/resourcepage/table/(.*)$#', 'view:AdminViewPage::listResourcesPageTable' );

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

    $this->addUrlPatterns( '#^admin/collection/create/(.+)$#', 'view:AdminViewCollection::createForm' );
    $this->addUrlPatterns( '#^admin/collection/edit/(\d+)/(.+)$#', 'view:AdminViewCollection::editForm' );
    $this->addUrlPatterns( '#^admin/collection/sendcollection$#', 'view:AdminViewCollection::sendCollectionForm' );

    $this->addUrlPatterns( '#^admin/multimedia/create$#', 'view:AdminViewCollection::createMultimediaForm' );
    $this->addUrlPatterns( '#^admin/multimedia/edit/(\d+)$#', 'view:AdminViewCollection::editMultimediaForm' );
    $this->addUrlPatterns( '#^admin/multimedia/sendmultimedia$#', 'view:AdminViewCollection::sendCollectionForm' );

    $this->addUrlPatterns( '#^admin/role/edit/(.*)$#', 'view:AdminViewRole::editRole' );
    $this->addUrlPatterns( '#^admin/role/create$#', 'view:AdminViewRole::createRole' );
    $this->addUrlPatterns( '#^admin/role/list$#', 'view:AdminViewRole::listRoles' );
    $this->addUrlPatterns( '#^admin/role/table$#', 'view:AdminViewRole::listRolesTable' );
    $this->addUrlPatterns( '#^admin/role/sendrole$#', 'view:AdminViewRole::sendRoleForm' );

    $this->addUrlPatterns( '#^admin/category/(\d+)/term/create$#', 'view:AdminViewTaxonomy::categoryForm' );
    $this->addUrlPatterns( '#^admin/category/(\d+)/term/edit/(\d+)$#', 'view:AdminViewTaxonomy::categoryForm' );
    $this->addUrlPatterns( '#^admin/category/term/sendcategoryterm$#', 'view:AdminViewTaxonomy::sendCategoryForm' );

    $this->addUrlPatterns( '#^admin/topics$#', 'view:AdminViewTopic::topicsSync' );


    // data Admin API
    $this->addUrlPatterns( '#^api/admin/categoryterms(\?.*|\/.*)$#', 'view:AdminDataAPIView::categoryTerms' );
    $this->addUrlPatterns( '#^api/admin/adminCategoryterms.json$#', 'view:AdminDataAPIView::categoryTermsJson' ); // Swagger
    $this->addUrlPatterns( '#^api/admin/categories$#', 'view:AdminDataAPIView::categories' );
    $this->addUrlPatterns( '#^api/admin/adminCategories.json$#', 'view:AdminDataAPIView::categoriesJson' ); // Swagger
    $this->addUrlPatterns( '#^api/admin/resourcesTerm/(.*)$#', 'view:AdminDataAPIView::resourcesTerm' );
    $this->addUrlPatterns( '#^api/admin/adminResourcesTerm.json$#', 'view:AdminDataAPIView::resourcesTermJson' ); // Swagger
    $this->addUrlPatterns( '#^api/admin/starred$#', 'view:AdminDataAPIView::starred' );
    $this->addUrlPatterns( '#^api/admin/adminStarred.json$#', 'view:AdminDataAPIView::starredJson' ); // Swagger



  }


}
