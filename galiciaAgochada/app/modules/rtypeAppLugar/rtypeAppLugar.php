<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppLugar extends Module {

  public $name = 'rtypeAppLugar';
  public $version = '1.0';
  public $rext = array( 'rextAppLugar', 'rextContact', 'rextSocialNetwork', 'rextAppZona', 'rextMapDirections', 'rextComment' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppLugarController.php',
    'view/RTypeAppLugarView.php'
  );

  public $nameLocations = array(
    'es' => 'Lugar',
    'en' => 'Lugar',
    'gl' => 'Lugar'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
