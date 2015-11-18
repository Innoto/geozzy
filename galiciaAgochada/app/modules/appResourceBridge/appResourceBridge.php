<?php


class appResourceBridge extends Module {

  public $name = 'appResourceBridge';
  public $version = '1.0';

  public $dependences = array();

  public $includesCommon = array();



  public function __construct() {
    global $CGMLCONF;

    // geozzy show Resource
    $this->addUrlPatterns( '#^'.$CGMLCONF['geozzy']['resourceURL'].'/(\d+)$#', 'view:AppResourceBridgeView::showResourcePage' );
    // TODO: a borrar
    $this->addUrlPatterns( '#^recurso/(\d+)$#', 'view:AppResourceBridgeView::showResourcePage' );
  }


  public function moduleRc() {
  }
}
