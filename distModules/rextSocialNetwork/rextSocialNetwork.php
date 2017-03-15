<?php
Cogumelo::load( 'coreController/Module.php' );


class rextSocialNetwork extends Module {

  public $name = 'rextSocialNetwork';
  public $version = 1.2;


  public $models = array(
    'RExtSocialNetworkModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtSocialNetworkController.php',
    'model/RExtSocialNetworkModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
