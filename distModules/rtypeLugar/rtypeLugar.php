<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeLugar extends Module {

  public $name = 'rtypeLugar';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeLugarController.php',
    'view/RTypeLugarView.php'
  );

  public $nameLocations = array(
    'es' => 'Lugar',
    'en' => 'Lugar',
    'gl' => 'Lugar'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rTypeModuleRc( __CLASS__ );
  }
}