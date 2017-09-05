<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeStory extends Module {

  public $name = 'rtypeStory';
  public $version = 1.0;
  public $rext = array('rextStory', 'rextView', 'rextSocialNetwork','rextComment');
  public $dependences = array(

  );

  public $includesCommon = array(
    'controller/RTypeStoryController.php',
    'view/RTypeStoryView.php',
    'js/model/StoryStepModel.js',
    'js/collection/StoryStepCollection.js'

  );

  public $nameLocations = array(
    'es' => 'Historia',
    'en' => 'Story',
    'gl' => 'Historia'
  );


  public function __construct() {

  }


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
