<?php
Cogumelo::load( 'coreController/Module.php' );


class rextKML extends Module {

  public $name = 'rextKML';
  public $version = 1.0;


  public $models = array(
    'RExtKMLModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtKMLController.php',
    'model/RExtKMLModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
