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
    // API DOC
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'$#', 'view:DocAPIView::main' );
    
    // resources
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy/resource$#', 'view:MainAPIView::resource' );
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy/resourcelist$#', 'view:MainAPIView::resourceList' );    
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy/resourcetypes$#', 'view:MainAPIView::resourceTypes' );
    
    // Categories
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy/categorylist$#', 'view:MainAPIView::categoryList' );
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy/categoryTerms$#', 'view:MainAPIView::categoryTerms' );

    // Topics
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/geozzy/topiclist$#', 'view:MainAPIView::topicList' );

    // Explorer
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/explorers$#', 'view:MainAPIView::explorers' );
  }

}
