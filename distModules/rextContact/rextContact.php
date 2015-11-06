<?php
Cogumelo::load( 'coreController/Module.php' );


class rextContact extends Module {

  public $name = 'rextContact';
  public $version = '1.0';


  public $models = array(
    'ContactModel'
  );

  public $taxonomies = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtContactController.php',
    'model/ContactModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeUtils.php');
    ResourcetypeUtils::rExtModuleRc( __CLASS__ );
  }
}
