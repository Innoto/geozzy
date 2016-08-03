<?php
Cogumelo::load( 'coreController/Module.php' );


class rextStory extends Module {

  public $name = 'rextStory';
  public $version = 1.0;


  public $models = array(
    'RExtStoryModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtStoryController.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
