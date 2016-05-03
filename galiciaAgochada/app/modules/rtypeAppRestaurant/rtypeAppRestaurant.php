<?php

Cogumelo::load('coreController/Module.php');

class rtypeAppRestaurant extends Module {

  public $name = 'rtypeAppRestaurant';
  public $version = '1.0';
  public $rext = array( 'rextEatAndDrink', 'rextContact', 'rextSocialNetwork', 'rextAppZona', 'rextMapDirections', 'rextComment' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppRestaurantController.php',
    'view/RTypeAppRestaurantView.php'
  );

  public $nameLocations = array(
    'es' => 'Restaurante',
    'en' => 'Restaurant',
    'gl' => 'Restaurante'
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
