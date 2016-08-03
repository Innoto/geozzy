<?php
require_once('conf/inc/geozzyRextRoutes.php');
Cogumelo::load( 'coreController/Module.php' );


class rextRoutes extends Module {

  public $name = 'rextRoutes';
  public $version = 1.1;



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
      "params" => array("dygraphs#v1.1.0"),
      "installer" => "bower",
      "includes" => array("dygraph-combined.js","extras/shapes.js")
    )
  );




  public $includesCommon = array(
    'controller/RExtRoutesController.php',
    'controller/RoutesController.php',
    'js/model/RouteModel.js',
    'js/collection/RouteCollection.js',
    'js/view/routeView.js',
    'js/view/routesExplorerView.js'
  );


  public function __construct() {


// TEST CODE

    $this->addUrlPatterns( '#^testRouteGraph#', 'view:TestRouteView::testRouteGraph' );
    $this->addUrlPatterns( '#^testroute$#', 'view:TestRouteView::routeConvert' );
// END TEST CODE

    $this->addUrlPatterns( '#^api/routes.json#', 'view:RoutesAPIView::routesJson' );
    $this->addUrlPatterns( '#^api/routes/?(.*)$#', 'view:RoutesAPIView::routes' );
    $this->addUrlPatterns( '#^api/adminRoutes/?(.*)$#', 'view:RoutesAPIView::adminRoutes' );

  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
