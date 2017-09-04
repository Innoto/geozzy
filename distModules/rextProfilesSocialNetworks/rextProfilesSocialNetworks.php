<?php
Cogumelo::load( 'coreController/Module.php' );


class rextProfilesSocialNetworks extends Module {

  public $name = 'rextProfilesSocialNetworks';
  public $version = 1.1;


  public $models = array(
    'ProfilesSNModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtProfilesSocialNetworksController.php',
    'model/ProfilesSNModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
