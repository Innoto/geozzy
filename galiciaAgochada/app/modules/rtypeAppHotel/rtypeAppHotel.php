<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppHotel extends Module {

  public $name = 'rtypeAppHotel';
  public $version = '1.0';
  public $rext = array( 'rextAccommodation', 'rextContact', 'rextSocialNetwork', 'rextAppZona', 'rextBI',
'rextMap', 'rextMapDirections', 'rextComment', 'rextReccommended', 'rextFavourite'/*, 'rextAccommodationReserve' */);

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppHotelController.php',
    'view/RTypeAppHotelView.php'
  );

  public $nameLocations = array(
    'es' => 'Hotel',
    'en' => 'Hotel',
    'gl' => 'Hotel'
  );
/*
  public $collectionRTypeFilter = array(
    'rtypeAppHotel', 'rtypeAppRestaurant'
  );
*/
  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
