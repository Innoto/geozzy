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
     "params" => array("ion-rangeslider@2"),
     "installer" => "yarn",
     "includes" => array("js/ion.rangeSlider.min.js", "css/ion.rangeSlider.css")
   ),
    array(//widget audio
      "id" =>"mediaelement",
      "params" => array("mediaelement@4.2.6"),
      "installer" => "yarn",
      "includes" => array("build/mediaelement-and-player.min.js", "build/mediaelementplayer.min.css")
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

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
