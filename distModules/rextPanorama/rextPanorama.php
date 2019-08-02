<?php
Cogumelo::load( 'coreController/Module.php' );


class rextPanorama extends Module {

  public $name = 'rextPanorama';
  public $version = 1;


  public $models = [ 'RExtPanoramaModel' ];

  public $taxonomies = [];

  public $dependences = [
    [
      'id' => 'pannellum',
      'params' => [ 'pannellum' ],
      'installer' => 'yarn',
      'includes' => [ 'src/js/libpannellum.js', 'src/js/pannellum.js', 'src/css/pannellum.css' ]
    ]
  ];

  public $includesCommon = [
    'controller/RExtPanoramaController.php',
    'model/RExtPanoramaModel.php'
  ];


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
