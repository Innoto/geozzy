<?php

Cogumelo::load("coreController/Module.php");

define('MOD_ADMIN_URL_DIR', 'admin');

class admin extends Module
{
  public $name = "admin";
  public $version = "";
  public $dependences = array(
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
     "id" =>"underscore",
     "params" => array("underscore-1.8.3"),
     "installer" => "manual",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone-1.1.2"),
     "installer" => "manual",
     "includes" => array("backbone-min.js")
    )

  );

  public $includesCommon = array(
    'styles/adminBase.less',
    'styles/admin.less',
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
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'$#', 'view:AdminViewMaster::commonAdminInterface' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/charts$#', 'view:AdminViewStadistic::main' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/alltables$#', 'view:AdminViewStatic::allTables' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/addcontent$#', 'view:AdminViewStatic::addContent' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/logout$#', 'view:AdminViewMaster::sendLogout' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/login$#', 'view:AdminViewLogin::main' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/senduserlogin$#', 'view:AdminViewLogin::sendLoginForm' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/edit/(.*)$#', 'view:AdminViewUser::editUser' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/show$#', 'view:AdminViewUser::showUser' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/list$#', 'view:AdminViewUser::listUsers' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/table$#', 'view:AdminViewUser::listUsersTable' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/create$#', 'view:AdminViewUser::createUser' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/senduser$#', 'view:AdminViewUser::sendUserForm' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/changepassword$#', 'view:AdminViewUser::changeUserPasswordForm' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/assignroles$#', 'view:AdminViewUser::assignaUserRolesForm' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/role/edit/(.*)$#', 'view:AdminViewRole::editRole' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/role/create$#', 'view:AdminViewRole::createRole' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/role/list$#', 'view:AdminViewRole::listRoles' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/role/table$#', 'view:AdminViewRole::listRolesTable' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/role/sendrole$#', 'view:AdminViewRole::sendRoleForm' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/categoryterms(.*)$#', 'view:AdminViewTaxonomy::categoryTermsSync' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/categories$#', 'view:AdminViewTaxonomy::categoriesSync' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/topics$#', 'view:AdminViewTopic::topicsSync' );

  }


}
