<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppGeozzySample extends Module {

  public $name = 'rextAppGeozzySample';
  public $version = 1.0;

  public $models = ['RExtAppGeozzySampleModel'];

  public $taxonomies = [];

  public $dependences = [];

  public $includesCommon = array(
    'controller/RExtAppGeozzySampleController.php',
    'model/RExtAppGeozzySampleModel.php'
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
