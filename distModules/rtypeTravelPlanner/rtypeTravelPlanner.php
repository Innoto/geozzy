<?php

Cogumelo::load("coreController/Module.php");


class rtypeTravelPlanner extends Module {

  public $name = "rtypeTravelPlanner";
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
   ),
   array(
     "id" =>"nestable2",
     "params" => array("nestable2-old"),
     "installer" => "bower",
     "includes" => array("jquery.nestable.js")
   ),
   array(
     'id' => 'jquery-validation',
     'params' => array( 'jquery-validate#1.14' ),
     'installer' => 'bower',
     'includes' => array( 'dist/jquery.validate.js', 'dist/additional-methods.js' )
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

}
