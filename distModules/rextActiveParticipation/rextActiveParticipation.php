<?php
Cogumelo::load("coreController/Module.php");

class rextActiveParticipation extends Module {

  public $name = "rextActiveParticipation";
  public $version = 1.0;

  public $models = array( 'ActiveParticipationModel' );

  public $dependences = array();
  public $includesCommon = array(
    'controller/RExtActiveParticipationController.php',
  );

  public $taxonomies = array();

  public function __construct() {}


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
