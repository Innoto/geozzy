<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeHotel extends Module {

  public $idName = 'rtypeHotel';
  public $version = '1.0';
  public $rext = array( 'rextAccommodation' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeHotelController.php',
    'view/RTypeHotelView.php'
  );

  public $nameLocations = array(
    'es' => 'Hotel',
    'en' => 'Hotel',
    'gl' => 'Hotel'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rTypeModuleRc( __CLASS__ );
  }
}