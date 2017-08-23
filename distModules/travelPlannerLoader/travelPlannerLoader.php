<?php
Cogumelo::load("coreController/Module.php");

class travelPlannerLoader extends Module {

  public $name = "travelPlannerLoader";
  public $version = 1.0;

  public $models = array();
  public $dependences = array();
  public $taxonomies = array();

  public $autoIncludeAlways = true;

  public $includesCommon = array(
    'js/travelPlannerLoader.js'
  );


  public function __construct() {

  }

}
