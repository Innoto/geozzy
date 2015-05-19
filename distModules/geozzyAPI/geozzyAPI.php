<?php


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
    
    require_once APP_BASE_PATH."/conf/geozzyAPI.php";

    // API DOC GENERATOR
    $this->addUrlPatterns( '#^api$#', 'view:DocAPIView::main' );
    $this->addUrlPatterns( '#^api/apidoc.json#', 'view:DocAPIView::apidocJson' ); // Main swagger JSON
  
    
    // geozzy core api doc
    $this->addUrlPatterns( '#^api/resources.json$#', 'view:CoreAPIView::resourcesJson' );
    $this->addUrlPatterns( '#^api/resourceTypes.json$#', 'view:CoreAPIView::resourceTypesJson' );
    $this->addUrlPatterns( '#^api/categoryList.json$#', 'view:CoreAPIView::categoryListJson' );
    $this->addUrlPatterns( '#^api/categoryTerms.json$#', 'view:CoreAPIView::categoryTermsJson' );
    $this->addUrlPatterns( '#^api/topicList.json$#', 'view:CoreAPIView::topicListJson' );

    // resources
    $this->addUrlPatterns( '#^api/core/resourcelist$#', 'view:CoreAPIView::resourceList' );    
    $this->addUrlPatterns( '#^api/core/resourcetypes$#', 'view:CoreAPIView::resourceTypes' );
    
    // Categories
    $this->addUrlPatterns( '#^api/core/categorylist$#', 'view:CoreAPIView::categoryList' );
    $this->addUrlPatterns( '#^api/core/categoryterms/(.*)$#', 'view:CoreAPIView::categoryTerms' );

    // Topics
    $this->addUrlPatterns( '#^api/core/topiclist$#', 'view:CoreAPIView::topicList' );


  }

}
