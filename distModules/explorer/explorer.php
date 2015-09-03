<?php


Cogumelo::load("coreController/Module.php");


class explorer extends Module
{
  public $name = "explorer";
  public $version = "1.0";



  public $dependences = array(
    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array("underscore-min.js")
   ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    ),
    array(
     "id" =>"backbone-fetch-cache",
     "params" => array("backbone-fetch-cache#1.3.0"),
     "installer" => "bower",
     "includes" => array("backbone.fetch-cache.min.js")
    ),
     array(
      "id" =>"backbone.obscura",
      "params" => array("backbone.obscura#1.0.1"),
      "installer" => "bower",
      "includes" => array("backbone.obscura.js")
     )

  );



  public $includesCommon = array(
    'js/models/ExplorerResourceMinimalModel.js',
    'js/models/ExplorerResourcePartialModel.js',
    'js/collections/ExplorerResourceMinimalCollection.js',
    'js/collections/ExplorerResourcePartialCollection.js',
    'js/views/ExplorerActiveListView.js',
    'js/views/ExplorerPasiveListView.js',
    'js/views/ExplorerMapView.js',
    'js/Explorer.js'
  );


  function __construct() {
    $this->addUrlPatterns( '#^api/explorer/(.*)#', 'view:ExplorerAPIView::explorer' );
    $this->addUrlPatterns( '#^api/explorer.json#', 'view:ExplorerAPIView::explorerJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/explorerList$#', 'view:ExplorerAPIView::explorerList' );
    $this->addUrlPatterns( '#^api/explorerList.json$#', 'view:ExplorerAPIView::explorerListJson' ); // Main swagger JSON
  }

}
