<?php
Cogumelo::load( 'coreController/Module.php' );


class rextReccommended extends Module {

  public $name = 'rextReccommended';
  public $version = 1.0;


  public $models = array();

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtReccommendedController.php',
    'js/view/rExtReccommendedView.js',
    'js/rExtReccommended.js'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
