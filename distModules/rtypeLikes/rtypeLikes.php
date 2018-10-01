<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeLikes extends Module {

  public $name = 'rtypeLikes';
  public $version = 1.0;
  public $rext = [];


  public $dependences = [];



  public $includesCommon = [
    'controller/RTypeLikesController.php',
    'view/RTypeLikesView.php',
    'js/likesView.js'
  ];


  public $nameLocations = [
    'es' => 'Me gustan',
    'en' => 'Likes',
    'gl' => 'Gústame'
  ];


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }


  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleDeploy();
  }

}
