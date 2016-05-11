<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppFesta extends Module {

  public $name = 'rtypeAppFesta';
  public $version = '1.0';
  public $rext = array('rextAppFesta', 'rextContact', 'rextSocialNetwork', 'rextAppZona', 'rextMap',  'rextMapDirections', 'rextEvent', 'rextEventCollection');

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppFestaController.php',
    'view/RTypeAppFestaView.php'
  );

  public $nameLocations = array(
    'es' => 'Festa',
    'en' => 'Festa',
    'gl' => 'Festa'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');


    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
