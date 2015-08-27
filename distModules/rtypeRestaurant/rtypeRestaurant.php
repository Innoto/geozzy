<?php

Cogumelo::load('coreController/Module.php');

class rtypeRestaurant extends Module {

  public $name = 'rtypeRestaurant';
  public $version = '1.0';
  public $rext = array( 'rextEatAndDrink' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeRestaurantController.php',
    'view/RTypeRestaurantView.php'
  );

  public $nameLocations = array(
    'es' => 'Restaurante',
    'en' => 'Restaurant',
    'gl' => 'Restaurante'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rTypeModuleRc( __CLASS__ );
  }
}
