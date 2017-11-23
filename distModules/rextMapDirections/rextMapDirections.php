<?php
Cogumelo::load( 'coreController/Module.php' );


class rextMapDirections extends Module {

  public $name = 'rextMapDirections';
  public $version = 2;


  public $models = array();

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtMapDirectionsController.php',
    'js/rExtMapDirectionsController.js',
    'js/rExtMapDirectionsInstance.js',
    'view/printMapDirectionsView'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^directions/print/(.*)$#', 'view:printMapDirectionsView::printMapDirections' );
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
