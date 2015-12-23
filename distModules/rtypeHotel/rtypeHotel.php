<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeHotel extends Module {

  public $name = 'rtypeHotel';
  public $version = '1.0';
  public $rext = array( 'rextAccommodation', 'rextContact', 'rextAppZona');

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

  public $collectionRTypeFilter = array(
    'rtypeHotel', 'rtypeRestaurant'
  );

  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
