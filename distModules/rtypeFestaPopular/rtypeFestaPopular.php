<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeFestaPopular extends Module
{
  public $name = 'rtypeFestaPopular';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeFestaPopularController.php',
    'view/RTypeFestaPopularView.php'
  );


  public function __construct() {

  }


}