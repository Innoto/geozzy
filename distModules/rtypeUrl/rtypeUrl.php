<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeUrl extends Module {

  public $name = 'rtypeUrl';
  public $version = '1.0';
  public $rext = array( 'rextUrl' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeUrlController.php',
    'view/RTypeUrlView.php'
  );


  public function __construct() {
  }

}