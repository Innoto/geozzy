<?php
Cogumelo::load("coreController/Module.php");

class rextTravelPlanner extends Module {

  public $name = "rextTravelPlanner";
  public $version = 1.0;

  public $models = array( 'TravelPlannerModel' );
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
    array(
      "id" =>"nestable2",
      "params" => array("nestable2-old"),
      "installer" => "yarn",
      "includes" => array("jquery.nestable.js")
    )
  );
  public $taxonomies = array();

  public $autoIncludeAlways = false;

  public $includesCommon = array(
    'js/view/Templates.js',
    'js/view/TravelPlannerInterfaceView.js',
    'js/view/TravelPlannerDatesView.js',
    'js/view/TravelPlannerPlanView.js',
    'js/view/TravelPlannerMapView.js',
    'js/view/TravelPlannerMapPlanView.js',
    'js/view/TravelPlannerResourceView.js',
    'js/view/TravelPlannerGetDatesView.js',
    'js/view/TravelPlannerOptimizeDayView.js',
    'js/router/TravelPlannerRouter.js',
    'js/model/TravelPlannerModel.js',
    'js/TravelPlannerApp.js',
    'controller/RExtTravelPlannerController.php',
    'model/TravelPlannerModel.php'
  );


  public function __construct() {

  }

  function setGeozzyUrlPatternsAPI() {
    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();

    if( $useraccesscontrol->isLogged() ) {
      $this->addUrlPatterns( '#^api/travelplanner#', 'view:RExtTravelPlannerAPIView::apiQuery' );
      $this->addUrlPatterns( '#^api/doc/travelplanner.json$#', 'view:RExtTravelPlannerAPIView::apiInfoJson' );
    }
  }


  function getGeozzyDocAPI() {
    $ret = [];

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();

    if( $useraccesscontrol->isLogged() ) {
      $ret = array(
        array(
          'path'=> '/doc/travelplanner.json',
          'description' => 'TravelPlanner API'
        )
      );
    }

    return $ret;
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
