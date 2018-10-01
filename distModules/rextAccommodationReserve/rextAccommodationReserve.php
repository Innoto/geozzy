<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAccommodationReserve extends Module {

  public $name = 'rextAccommodationReserve';
  public $version = 1.0;


  public $models = array(
    'RExtAccommodationReserveModel'
  );

  public $taxonomies = array();

  public $dependences = array(
    array(
     'id' =>'moment',
     'params' => array( 'moment' ),
     'installer' => 'yarn',
     'includes' => array( 'min/moment-with-locales.min.js' )
    ),
    array(
     "id" =>"moment-timezone",
     "params" => array("moment-timezone"),
     "installer" => "yarn",
     "includes" => array("builds/moment-timezone-with-data.min.js")
    ),
    array(
     'id' =>'bootstrap-daterangepicker',
     'params' => array( 'bootstrap-daterangepicker' ),
     'installer' => 'yarn',
     'includes' => array( 'daterangepicker.js', 'daterangepicker.css' )
    ),
  );

  public $includesCommon = array(
    'controller/RExtAccommodationReserveController.php',
    'model/RExtAccommodationReserveModel.php'
  );


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
