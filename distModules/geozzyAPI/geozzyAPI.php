<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load("coreController/Module.php");


class geozzyAPI extends Module
{
  public $name = "geozzyAPI";
  public $version = "";



  public $dependences = array(

    array(
     "id" =>"swagger-ui",
     "params" => array("swagger-ui#v2.0.24"),
     "installer" => "bower",
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
