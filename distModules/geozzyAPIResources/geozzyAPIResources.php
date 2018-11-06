<?php


Cogumelo::load("coreController/Module.php");



class geozzyAPIResources extends Module {
  public $name = "geozzyAPIResources";
  public $version = 1.0;


  public $dependences = array( );

  function __construct() {}

  function setGeozzyUrlPatternsAPI() {
    global $COGUMELO_INSTANCED_MODULES;

    // geozzy api resources
    $COGUMELO_INSTANCED_MODULES['geozzy']->addUrlPatterns( '#^api/doc/resources.json$#', 'view:GeozzyAPIView::resourcesJson' );
    $COGUMELO_INSTANCED_MODULES['geozzy']->addUrlPatterns( '#^api/doc/resourceIndex.json$#', 'view:GeozzyAPIView::resourceIndexJson' );

    $COGUMELO_INSTANCED_MODULES['geozzy']->addUrlPatterns( '#^api/core/resourcelist(.*)$#', 'view:GeozzyAPIView::resourceList' );
    $COGUMELO_INSTANCED_MODULES['geozzy']->addUrlPatterns( '#^api/core/resourceIndex(.*)#', 'view:GeozzyAPIView::resourceIndex' );

  }


  function getGeozzyDocAPI() {
    $ret = [];

    // únicas apis públicas
    $ret[] = array(
      'path' => '/doc/resources.json',
      'description' => 'Core Resource'
    );
    $ret[] = array(
      'path' => '/doc/resourceIndex.json',
      'description' => 'Resource index'
    );

    return $ret;
  }

}
