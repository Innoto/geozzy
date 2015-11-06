<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppEspazoNatural extends Module {

  public $name = 'rtypeAppEspazoNatural';
  public $version = '1.0';
  public $rext = array( 'rextAppEspazoNatural' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppEspazoNaturalController.php',
    'view/RTypeAppEspazoNaturalView.php'
  );

  public $nameLocations = array(
    'es' => 'Espazo Natural',
    'en' => 'Espazo Natural',
    'gl' => 'Espazo Natural'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeUtils.php');
    ResourcetypeUtils::rTypeModuleRc( __CLASS__ );
  }
}