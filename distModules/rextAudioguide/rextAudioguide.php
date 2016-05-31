<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAudioguide extends Module {

  public $name = 'rextAudioguide';
  public $version = 1.0;


  public $models = array(
    'AudioguideModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAudioguideController.php',
    'model/AudioguideModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
