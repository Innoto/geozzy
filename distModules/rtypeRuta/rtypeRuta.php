<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeRuta extends Module {

  public $name = 'rtypeRuta';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeRutaController.php',
    'view/RTypeRutaView.php'
  );

  public $nameLocations = array(
    'es' => 'Ruta',
    'en' => 'Ruta',
    'gl' => 'Ruta'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rTypeModuleRc( __CLASS__ );
  }
}