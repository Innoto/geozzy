<?php
Cogumelo::load( 'coreController/Module.php' );


class rextPoiCollection extends Module {

  public $name = 'rextPoiCollection';
  public $version = '1.5';


  public $models = array();

  public $taxonomies = array();

  public $dependences = array();

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
}
