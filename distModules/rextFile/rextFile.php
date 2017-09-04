<?php
Cogumelo::load( 'coreController/Module.php' );


class rextFile extends Module {

  public $name = 'rextFile';
  public $version = 1.0;


  public $models = array(
    'RExtFileModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtFileController.php',
    'model/RExtFileModel.php'
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
