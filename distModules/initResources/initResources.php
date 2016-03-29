<?php
Cogumelo::load( 'coreController/Module.php' );


class initResources extends Module {
  public $version = 1.0;
  public $includesCommon = array();


  public function __construct() {
    //$this->addUrlPatterns( '#^initResources$#', 'view:InitResourcesView::generateResources' );
  }


  public function moduleRc() {

  }

  public function moduleDeploy( $isFirstGenerateModel = false ) {
    initResources::load('controller/InitResourcesController.php');
    $initResources = new InitResourcesController();
    $initResources->generateResources( $isFirstGenerateModel );
  }
}