<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAudioguide extends Module {

  public $name = 'rextAudioguide';
  public $version = 1.1;


  public $models = array(
    'AudioguideModel'
  );

  public $taxonomies = array();

  public $dependences = array(
    array(
     "id" =>"ionrangeslider",
     "params" => array("ionrangeslider#2"),
     "installer" => "bower",
     "includes" => array("js/ion.rangeSlider.js", "css/ion.rangeSlider.css")
    )
  );

  public $includesCommon = array(
    'controller/RExtAudioguideController.php',
    'model/AudioguideModel.php'
  );
  public $autoIncludeAlways = true;

  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
