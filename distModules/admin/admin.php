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
    )

  );

  public $includesCommon = array(
    'styles/adminBase.less',
    'styles/admin.less',
    'js/adminBase.js'
  );

  public function __construct() {
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'$#', 'view:AdminViewStadistic::main' );
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
  }


}
