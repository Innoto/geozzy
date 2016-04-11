<?php
Cogumelo::load( 'coreController/Module.php' );


class rextRoutes extends Module {

  public $name = 'rextRoutes';
  public $version = 1.0;



  public $models = array(
    'RExtRoutesModel'
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
      "params" => array("phayes/geophp", "1.2"),
      "installer" => "composer",
      "includes" => array("geoPHP.inc")
    ),
    array(
      "id" => "dygraphs",
      "params" => array("dygraphs#v1.1.0"),
      "installer" => "bower",
      "includes" => array("dygraph-combined.js")
    )
  );




  public $includesCommon = array(
    /*'controller/RExtRoutesController.php',
    'model/RExtRoutesModel.php'*/
  );


  public function __construct() {

    $this->addUrlPatterns( '#^testroute$#', 'view:TestRouteView::routeConvert' );

  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
