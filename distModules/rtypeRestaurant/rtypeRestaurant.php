<?php

Cogumelo::load("coreController/Module.php");

class rtypeRestaurant extends Module
{
  public $name = "rtypeRestaurant";
  public $version = "1.0";
  public $rext = array('rextEatAndDrink');

  public $dependences = array();
  
  public $includesCommon = array(
    'controller/RTypeRestaurantController.php',
    'view/RTypeRestaurantView.php'
  );
  
  public function __construct() {}
}
