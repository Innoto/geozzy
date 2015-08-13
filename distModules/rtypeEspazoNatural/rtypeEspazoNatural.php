<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeEspazoNatural extends Module
{
  public $name = 'rtypeEspazoNatural';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeEspazoNaturalController.php',
    'view/RTypeEspazoNaturalView.php'
  );


  public function __construct() {

  }


}