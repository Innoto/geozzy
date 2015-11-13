<?php


class appResourceBridge extends Module {

  public $name = 'appResourceBridge';
  public $version = '1.0';

  public $dependences = array();

  public $includesCommon = array();



  public function __construct() {
    // geozzy show Resource
    $this->addUrlPatterns( '#^resource/(\d+)$#', 'view:AppResourceBridgeView::showResourcePage' );
    $this->addUrlPatterns( '#^resourceTmp/(\d+)$#', 'view:AppResourceBridgeView::showResourcePageTmp' );
  }


  public function moduleRc() {
  }
}
