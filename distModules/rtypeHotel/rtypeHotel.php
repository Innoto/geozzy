<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeHotel extends Module
{
  public $name = 'rtypeHotel';
  public $version = '1.0';
  public $rext = array( 'rextAccommodation' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeHotelController.php',
    'view/RTypeHotelView.php'
  );


  public function __construct() {

  }


}