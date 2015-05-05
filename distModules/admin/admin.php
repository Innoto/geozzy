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
    array(
     "id" =>"morrisjs",
     "params" => array("morris.js-0.5.1"),
     "installer" => "manual",
     "includes" => array("morris.js", "morris.css")
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
    'js/exampleMorrisData.js'
  );

  public function __construct() {
    $this->addUrlPatterns( '#^admin$#', 'view:AdminViewMaster::commonAdminInterface' );
    $this->addUrlPatterns( '#^admin/charts$#', 'view:AdminViewStadistic::main' );

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

    $this->addUrlPatterns( '#^admin/role/edit/(.*)$#', 'view:AdminViewRole::editRole' );
    $this->addUrlPatterns( '#^admin/role/create$#', 'view:AdminViewRole::createRole' );
    $this->addUrlPatterns( '#^admin/role/list$#', 'view:AdminViewRole::listRoles' );
    $this->addUrlPatterns( '#^admin/role/table$#', 'view:AdminViewRole::listRolesTable' );
    $this->addUrlPatterns( '#^admin/role/sendrole$#', 'view:AdminViewRole::sendRoleForm' );

    $this->addUrlPatterns( '#^admin/categoryterms(.*)$#', 'view:AdminViewTaxonomy::categoryTermsSync' );
    $this->addUrlPatterns( '#^admin/categories$#', 'view:AdminViewTaxonomy::categoriesSync' );

    $this->addUrlPatterns( '#^admin/topics$#', 'view:AdminViewTopic::topicsSync' );

    $this->addUrlPatterns( '#^api/admin$#', 'view:AdminAPIView::main' );

  }


}
