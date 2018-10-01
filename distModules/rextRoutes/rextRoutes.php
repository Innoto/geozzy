<?php
require_once('conf/inc/geozzyRextRoutes.php');
Cogumelo::load( 'coreController/Module.php' );

global $rextRoutes_difficulty;

class rextRoutes extends Module {

  public $name = 'rextRoutes';
  public $version = 1.2;

  public $models = array(
    'RoutesModel'
  );

  public $taxonomies = array(
    'routeCircuitType' => array(
      'idName' => 'routeCircuitType',
      'name' => array(
        'en' => 'Route circuit type',
        'es' => 'Tipo de recorrido',
        'gl' => 'Tipo de percorrido'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        /*array(
          'idName' => 'hoteles',
          //'icon' => 'view/categoryIcons/hotel.svg',
          'name' => array(
            'en' => 'Hotels',
            'es' => 'Hoteles',
            'gl' => 'Hoteis'
          )
        )*/
      )
    )
  );

  public $dependences = array(
    array(
      "id" => "geophp",
      "params" => array("innoto/geophp", "dev-master"),
      "installer" => "composer",
      "includes" => array("geoPHP.inc")
    ),

    array(
      "id" => "dygraphs",
      "params" => array("dygraphs@1.1.0"),
      "installer" => "yarn",
      "includes" => array("dygraph-combined.js","extras/shapes.js")
    ),

    array(
      "id" =>"switchery",
      "params" => array("switchery"),
      "installer" => "yarn",
      "includes" => array("switchery.js", "switchery.css")
    )
  );




  public $includesCommon = array(
    'controller/RExtRoutesController.php',
    'controller/RoutesController.php',
    'js/model/RouteModel.js',
    'js/collection/RouteCollection.js',
    'js/view/routeView.js',
    //'js/view/ExplorerRoutesView.js'
  );


  public function __construct() {
// TEST CODE
    //$this->addUrlPatterns( '#^testRouteGraph#', 'view:TestRouteView::testRouteGraph' );
    //$this->addUrlPatterns( '#^testroute$#', 'view:TestRouteView::routeConvert' );
// END TEST CODE
  }

  function setGeozzyUrlPatternsAPI() {
    $this->addUrlPatterns( '#^api/doc/routes.json#', 'view:RoutesAPIView::routesJson' );
    $this->addUrlPatterns( '#^api/routes/?(.*)$#', 'view:RoutesAPIView::routes' );
    $this->addUrlPatterns( '#^api/adminRoutes/?(.*)$#', 'view:RoutesAPIView::adminRoutes' );
  }

  function getGeozzyDocAPI() {
    $ret = array(
      array(
        'path'=> '/doc/routes.json',
        'description' => 'Routes API'
      )
    );
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
