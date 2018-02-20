<?php
Cogumelo::load( 'coreController/Module.php' );


class rextPoiCollection extends Module {

  public $name = 'rextPoiCollection';
  public $version = '4';

  public $models = array();

  public $taxonomies = array();


  public $dependences = array(
    array(
      "id" => "pannellum",
      "params" => array("pannellum"),
      "installer" => "bower",
      "includes" => array("js/libpannellum.js","/js/pannellum.js","css/pannellum.css")
    )
  );


  public $includesCommon = array(
    'controller/RExtPoiCollectionController.php'
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
