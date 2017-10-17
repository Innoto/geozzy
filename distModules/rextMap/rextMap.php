<?php

$RextMapConfRoute = APP_BASE_PATH.'/conf/inc/geozzyRextMap.php';
if(file_exists($RextMapConfRoute)){
  require_once($RextMapConfRoute);
}
Cogumelo::load( 'coreController/Module.php' );


class rextMap extends Module {

  public $name = 'rextMap';
  public $version = 1.0;


  public $models = array();

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtMapController.php',
    'view/RExtMapView.php',
    'js/rExtMapController.js',
    'js/rExtMapInstance.js'
  );


  public function __construct() {
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
