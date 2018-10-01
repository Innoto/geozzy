<?php
Cogumelo::load( 'coreController/Module.php' );


class rextReservation extends Module {

  public $name = 'rextReservation';
  public $version = 1.0;


  public $models = array(
    'RExtReservationModel'
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
    )
  );

  public $includesCommon = array(
    'controller/RExtReservationController.php',
    'model/RExtReservationModel.php'
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
