<?php

Cogumelo::load("coreController/Module.php");


class rtypeTravelPlanner extends Module {

  public $name = "rtypeTravelPlanner";
  public $version = 1.0;
  public $rext = array( 'rextTravelPlanner' );

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
      "id" =>"nestable2",
      "params" => array("nestable2-old"),
      "installer" => "yarn",
      "includes" => array("jquery.nestable.js")
    ),
    array(
      'id' => 'jquery-validation',
      'params' => array( 'jquery-validation@1.14.0' ),
      'installer' => 'yarn',
      'includes' => array( 'dist/jquery.validate.js', 'dist/additional-methods.js' )
    ),
    array(
     "id" =>"tiny_map_utilities",
     "params" => array("tiny_map_utilities"),
     "installer" => "yarn",
     "includes" => array("smart_infowindow/smart_infowindow.js", "smart_infowindow/vendor/jQueryRotate.js")
    )
  );

  public $includesCommon = array(
    'controller/RTypeTravelPlannerController.php',
    'view/RTypeTravelPlannerView.php',
  );

  public $nameLocations = array(
    'es' => 'Planificador de viajes',
    'en' => 'Travel planner',
    'gl' => 'Planificador de viaxes'
  );


  function __construct() {

  }

  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }


  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleDeploy();
  }

}
