<?php


Cogumelo::load("coreController/Module.php");


class comments extends Module
{
  public $name = "comments";
  public $version = 1.0;


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
    )
  );

  public $includesCommon = array( );

  function __construct() {
    //$this->addUrlPatterns( '#^geozzyuser/logout$#', 'view:GeozzyUserView::sendLogout' );
  }

  public function moduleRc() {
    

  }
}
