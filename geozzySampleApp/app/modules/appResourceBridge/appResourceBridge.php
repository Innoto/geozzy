<?php


class appResourceBridge extends Module {

  public $name = 'appResourceBridge';
  public $version = '1.0';

  public $dependences = array();

  public $includesCommon = array();



  public function __construct() {
    // geozzy show Resource
    $this->addUrlPatterns( '#^'.Cogumelo::getSetupValue('mod:geozzy:resource:directUrl').'/(\d+)(.*)$#', 'view:AppResourceBridgeView::showResourcePage' );
  }


  public function moduleRc() {
  }
}
