<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeCommunity extends Module {

  public $name = 'rtypeCommunity';
  public $version = 1.0;
  public $rext = array();


  public $dependences = array();


  public $includesCommon = array(
    'controller/RTypeCommunityController.php',
    'view/RTypeCommunityView.php',
    'js/communityView.js'
  );


  public $nameLocations = array(
    'es' => 'Comunidad',
    'en' => 'Community',
    'gl' => 'Comunidade'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
