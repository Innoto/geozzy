<?php
Cogumelo::load( 'coreController/Module.php' );


class rextMapDirections extends Module {

  public $name = 'rextMapDirections';
  public $version = 1.0;


  public $models = array();

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtMapDirectionsController.php',
    'js/rExtMapDirectionsController.js'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
