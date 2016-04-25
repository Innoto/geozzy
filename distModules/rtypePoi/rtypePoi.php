<?php
Cogumelo::load( 'coreController/Module.php' );


class rtypePoi extends Module {

  public $name = 'rtypePoi';
  public $version = 1.0;
  public $rext = array( 'rextPoi' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypePoiController.php'
    //'view/RTypePoiView.php'
  );

  public $nameLocations = array(
    'es' => 'POI',
    'en' => 'POI',
    'gl' => 'POI'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^rtypePoi/Poi/create$#', 'view:RTypePoiView::createModalForm' );
    $this->addUrlPatterns( '#^rtypePoi/Poi/edit/(\d+)$#', 'view:RTypePoiView::editModalForm' );
    $this->addUrlPatterns( '#^rtypePoi/Poi/sendPoi$#', 'view:RTypePoiView::sendModalResourceForm' );
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');


    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
