<?php
Cogumelo::load( 'coreController/Module.php' );


class rextUserProfile extends Module {

  public $name = 'rextUserProfile';
  public $version = 1.0;


  public $models = array(
    'RExtUserProfileModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtUserProfileController.php',
    'model/RExtUserProfileModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
