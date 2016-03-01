<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypePage extends Module {

  public $name = 'rtypePage';
  public $version = 1.0;
  public $rext = array( 'rextView', 'rextContact');

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypePageController.php',
    'view/RTypePageView.php'
  );

  public $nameLocations = array(
    'es' => 'Página',
    'en' => 'Page',
    'gl' => 'Páxina'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
