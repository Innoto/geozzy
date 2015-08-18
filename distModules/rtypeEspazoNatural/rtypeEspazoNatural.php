<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeEspazoNatural extends Module {

  public $idName = 'rtypeEspazoNatural';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeEspazoNaturalController.php',
    'view/RTypeEspazoNaturalView.php'
  );

  public $nameLocations = array(
    'es' => 'Espazo Natural',
    'en' => 'Espazo Natural',
    'gl' => 'Espazo Natural'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rTypeModuleRc( __CLASS__ );
  }
}