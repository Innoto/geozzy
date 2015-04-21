<?php

Cogumelo::load("coreController/Module.php");

define('GEOZZY_API_URL_DIR', 'api');

class geozzyAPI extends Module
{
  public $name = "geozzyAPI";
  public $version = "";



  public $dependences = array(
    array(
      "id" => "swagger-ui-2",
      "params" => array("swagger-ui-2.0.24"),
      "installer" => "manual",
      "includes" => array("")
    )
  );

  function __construct() {
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'$#', 'view:DocAPIView::main' );
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy$#', 'view:MainAPIView::main' );
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/explorer$#', 'view:explorerView::main' );
    //$this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/views$#', 'view:ResourceAPIView::main' );
  }

}
 