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
     "includes" => array("dist/css/bootstrap.min.css", "dist/js/bootstrap.min.js")
    ),
    array(
     "id" => "font-awesome",
     "params" => array("font-awesome-4.2.0"),
     "installer" => "manual",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"metismenu",
     "params" => array("metisMenu"),
     "installer" => "bower",
     "includes" => array("dist/metisMenu.min.css", "dist/metisMenu.min.js")
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
     "id" =>"raphael",
     "params" => array("raphael"),
     "installer" => "bower",
     "includes" => array("raphael-min.js")
    ),
/*

    array(
     "id" =>"jquery-ui",
     "params" => array("jquery-ui"),
     "installer" => "bower",
     "includes" => array("jquery-ui.js", "jquery-ui.css")
    ),
*/
/*
    array(
     "id" =>"jquery-ui-sortable",
     "params" => array("jquery-ui-sortable"),
     "installer" => "bower",
     "includes" => array("jquery-ui-sortable.js")
    ),
*/
    array(
     "id" =>"select2",
     "params" => array("select2"),
     "installer" => "bower",
     "includes" => array("dist/js/select2.full.js", "dist/css/select2.css")
    ),

/*
    array(
     "id" =>"select2-2",
     "params" => array("select2#3.5.2"),
     "installer" => "bower",
     "includes" => array("select2.js", "select2.css", "select2.sortable.js")
    ),
*/

    array(
     "id" =>"jqueryui-touch-punch",
     "params" => array("jqueryui-touch-punch"),
     "installer" => "bower",
     "includes" => array("jquery.ui.touch-punch.js")
    ),

    array(
     "id" =>"nestable2",
     "params" => array("nestable2"),
     "installer" => "bower",
     "includes" => array("jquery.nestable.js")
    )


  );

  public $includesCommon = array(
    'styles/adminBase.less',
    'styles/admin.less',
    'styles/adminNestable.less',
    'styles/multiList.css',
    'js/multiList.js',
    'js/app.js',
    'js/views/AdminView.js',
    'js/views/CategoryEditorView.js',
    'js/routers/AdminRouter.js',
    'js/models/TaxonomygroupModel.js',
    'js/models/TaxonomytermModel.js',
    'js/models/TopicModel.js',
    'js/collections/CategoryCollection.js',
    'js/collections/CategorytermCollection.js',
    'js/collections/TopicCollection.js',
    'js/collections/StarredCollection.js'
  );

  public function __construct() {
    $this->addUrlPatterns( '#^admin$#', 'view:AdminViewMaster::commonAdminInterface' );
    $this->addUrlPatterns( '#^admin/charts$#', 'view:AdminViewStadistic::main' );

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
    $this->addUrlPatterns( '#^admin/resourceintopic/table/(\d+)$#', 'view:AdminViewResourceInTopic::listResourcesInTopicTable' );
    $this->addUrlPatterns( '#^admin/resourceouttopic/list/(.*)$#', 'view:AdminViewResourceOutTopic::listResourcesOutTopic' );
    $this->addUrlPatterns( '#^admin/resourceouttopic/table/(\d+)$#', 'view:AdminViewResourceOutTopic::listResourcesOutTopicTable' );
    $this->addUrlPatterns( '#^admin/resourceouttopic/assign/(\d+)/(.*)$#', 'view:AdminViewResourceOutTopic::addResourceTopic' );

    $this->addUrlPatterns( '#^admin/starred/(.*)/assign$#', 'view:AdminViewStarred::listAssignStarred' );
    $this->addUrlPatterns( '#^admin/starred/table/(\d+)$#', 'view:AdminViewStarred::listStarredTable' );

    $this->addUrlPatterns( '#^admin/resource/create/(\d+)/(\d+)$#', 'view:AdminViewResource::resourceForm' );
    $this->addUrlPatterns( '#^admin/resource/create$#', 'view:AdminViewResource::resourceForm' );
    $this->addUrlPatterns( '#^admin/resource/edit/(\d+)$#', 'view:AdminViewResource::resourceEditForm' );
    $this->addUrlPatterns( '#^admin/resource/sendresource$#', 'view:AdminViewResource::sendResourceForm' );


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
    $this->addUrlPatterns( '#^api/admin/adminCategoryterms.json$#', 'view:AdminDataAPIView::categoryTermsJson' ); // Swagger
    $this->addUrlPatterns( '#^api/admin/categoryterms(.*)$#', 'view:AdminDataAPIView::categoryTerms' );
    $this->addUrlPatterns( '#^api/admin/adminCategories.json$#', 'view:AdminDataAPIView::categoriesJson' ); // Swagger
    $this->addUrlPatterns( '#^api/admin/categories$#', 'view:AdminDataAPIView::categories' );
    $this->addUrlPatterns( '#^api/admin/starred$#', 'view:AdminDataAPIView::starred' );



  }


}
