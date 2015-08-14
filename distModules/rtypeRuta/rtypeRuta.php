<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeRuta extends Module
{
  public $name = 'rtypeRuta';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeRutaController.php',
    'view/RTypeRutaView.php'
  );


  public function __construct() {

  }


}