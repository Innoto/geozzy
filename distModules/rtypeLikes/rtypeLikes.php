<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeLikes extends Module {

  public $name = 'rtypeLikes';
  public $version = 1.0;
  public $rext = [];


  public $dependences = [];
  // public $dependences = array(
  //   array(
  //     'id' =>'underscore',
  //     'params' => array('underscore#1.8.3'),
  //     'installer' => 'bower',
  //     'includes' => array('underscore-min.js')
  //   ),
  //   array(
  //     'id' =>'backbonejs',
  //     'params' => array('backbone#1.1.2'),
  //     'installer' => 'bower',
  //     'includes' => array('backbone.js')
  //   ),
  //   array(
  //     'id' =>'select2',
  //     'params' => array('select2#4'),
  //     'installer' => 'bower',
  //     'includes' => array('dist/js/select2.full.min.js', 'dist/css/select2.min.css')
  //   )
  // );


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
