<?php
Cogumelo::load( 'coreController/Module.php' );


class rextStoryStep extends Module {

  public $name = 'rextStoryStep';
  public $version = 1.5;


  public $models = array(
    'RExtStoryStepModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtStoryStepController.php',
    'model/RExtStoryStepModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
