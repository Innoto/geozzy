<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppRuta extends Module {

  public $name = 'rtypeAppRuta';
  public $version = '1.0';
  public $rext = array('rextRoutes', 'rextContact', 'rextSocialNetwork', 'rextBI', 'rextMap',  'rextMapDirections', 'rextPoiCollection', 'rextFavourite');

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppRutaController.php',
    'view/RTypeAppRutaView.php'
  );

  public $nameLocations = array(
    'es' => 'Ruta',
    'en' => 'Ruta',
    'gl' => 'Ruta'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
