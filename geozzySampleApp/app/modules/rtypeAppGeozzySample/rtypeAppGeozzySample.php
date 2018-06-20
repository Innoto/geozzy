<?php
Cogumelo::load( 'coreController/Module.php' );


class rtypeAppGeozzySample extends Module {

  public $name = 'rtypeAppGeozzySample';
  public $version = 1.0;
  public $rext = array( 'rextAudioguide', 'rextContact', 'rextComment', 'rextSocialNetwork', 'rextMap', 'rextAppGeozzySample' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppGeozzySampleController.php',
    'view/RTypeAppGeozzySampleView.php'
  );

  public $nameLocations = array(
    'es' => 'GeozzySampleApp'
  );

  public function __construct() {
  }

  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleDeploy();
  }
}
