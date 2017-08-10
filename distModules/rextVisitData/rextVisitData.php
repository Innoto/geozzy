<?php
Cogumelo::load( 'coreController/Module.php' );


class rextVisitData extends Module {

  public $name = 'rextVisitData';
  public $version = 1.0;


  public $models = array(
    'RExtVisitDataModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtVisitDataController.php',
    'model/RExtVisitDataModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
