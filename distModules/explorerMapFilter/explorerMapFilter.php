<?php

Cogumelo::load("coreController/Module.php");

class explorerMapFilter extends Module {
  public $name = "explorerMapFilter";
  public $version = 1;

  public $dependences = array(
    array(
     "id" =>"raphael",
     "params" => array("raphael"),
     "installer" => "yarn",
     "includes" => array("raphael.min.js")
    ),
    array(
      'id' =>'jquery-mapael-2.2.0',
      'params' => array( 'jquery-mapael-2.2.0' ),
      'installer' => 'manual',
      'includes' => array( "js/jquery.mapael.min.js")
    )
  );

  public $includesCommon = array(
    'js/ExplorerFilterMinimapView.js'
  );


  public function __construct() {
  }

}
