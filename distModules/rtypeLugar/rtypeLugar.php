<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeLugar extends Module
{
  public $name = 'rtypeLugar';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeLugarController.php',
    'view/RTypeLugarView.php'
  );


  public function __construct() {

  }


}