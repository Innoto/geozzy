<?php
Cogumelo::load( 'coreController/Module.php' );


class rextMap extends Module {

  public $name = 'rextMap';
  public $version = 1.0;


  public $models = array();

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtMapController.php',
    'js/rExtMapController.js',
    'js/rExtMapInstance.js'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
