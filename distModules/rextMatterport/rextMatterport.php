<?php
Cogumelo::load( 'coreController/Module.php' );


class rextMatterport extends Module {

  public $name = 'rextMatterport';
  public $version = 1.0;


  public $models = array( 'RExtMatterportModel' );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtMatterportController.php',
    'model/RExtMatterportModel.php'
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
