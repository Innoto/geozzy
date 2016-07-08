<?php
Cogumelo::load("coreController/Module.php");

class rextParticipation extends Module {

  public $name = "rextParticipation";
  public $version = 1.0;

  public $models = array( 'ParticipationModel' );

  public $dependences = array();
  public $includesCommon = array(
    'controller/RExtParticipationController.php',
  );

  public $taxonomies = array();

  public function __construct() {}


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
