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
    'styles/admin.less',
    'styles/adminBase.less',
    'js/adminBase.js',
    'js/exampleMorrisData.js'
  );

  function __construct() {
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'$#', 'view:MasterView::main' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/login$#', 'view:AdminLoginView::main' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/senduserlogin$#', 'view:AdminLoginView::sendLoginForm' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/logout$#', 'view:MasterView::sendLogout' );

  }
}