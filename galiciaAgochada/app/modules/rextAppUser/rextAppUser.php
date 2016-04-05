<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppUser extends Module {

  public $name = 'rextAppUser';
  public $version = 1.0;


  public $models = array(
    'RExtAppUserModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAppUserController.php',
    'model/RExtAppUserModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
