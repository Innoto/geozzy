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

  public $nameLocations = array(
    'es' => 'URL',
    'en' => 'URL',
    'gl' => 'URL'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeUtils.php');
    ResourcetypeUtils::rTypeModuleRc( __CLASS__ );
  }
}