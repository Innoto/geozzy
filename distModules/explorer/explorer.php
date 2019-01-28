<?php

Cogumelo::load("coreController/Module.php");

class explorer extends Module {
  public $name = "explorer";
  public $version = 3.0;

  public $dependences = array(

    array(
      "id" =>"google-maps-utility-library-v3-markerwithlabel",
      "params" => array("google-maps-utility-library-v3-markerwithlabel"),
      "installer" => "yarn",
      "includes" => array("dist/markerwithlabel.min.js")
    ),
    array(
     "id" =>"underscore",
     "params" => array("underscore@1.8.3"),
     "installer" => "yarn",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone@1.1.2"),
     "installer" => "yarn",
     "includes" => ['backbone.js']
    ),
    array(
     "id" =>"backbone-fetch-cache.innoto",
     "params" => array("innoto-backbone-fetch-cache"),
     "installer" => "yarn",
     "includes" => array("backbone.fetch-cache.min.js")
    ),
    array(
      "id" =>"backbone.obscura",
      "params" => array("backbone.obscura@1.0.1"),
      "installer" => "yarn",
      "includes" => array("backbone.obscura.js")
    ),
    array(
     "id" =>"marker-clusterer-v3-innoto",
     "params" => array("marker-clusterer-v3-innoto"),
     "installer" => "yarn",
     "includes" => array("src/markerclusterer.js")
    ),
    array(
      "id" =>"innoto-switchery",
      "params" => array("innoto-switchery"),
      "installer" => "yarn",
      "includes" => array("dist/switchery.min.js", "dist/switchery.min.css")
    ),
    array(
     "id" =>"tiny_map_utilities",
     "params" => array("tiny_map_utilities"),
     "installer" => "yarn",
     "includes" => array("smart_infowindow/smart_infowindow.js", "smart_infowindow/vendor/jQueryRotate.js")
    )
  );

  public $includesCommon = array(
    'js/router/ExplorerRouter.js',
    'js/model/ExplorerResourceMinimalModel.js',
    'js/model/ExplorerResourcePartialModel.js',
    'js/collection/ExplorerResourceMinimalCollection.js',
    'js/collection/ExplorerResourcePartialCollection.js',
    'js/view/Templates.js',
    'js/view/ExplorerFilterView.js',
    'js/view/filters/ExplorerFilterButtonsView.js',
    'js/view/filters/ExplorerFilterComboView.js',
    'js/view/filters/ExplorerFilterGeoView.js',
    'js/view/filters/ExplorerFilterSwitchView.js',
    'js/view/filters/ExplorerFilterResetView.js',
    'js/view/ExplorerListView.js',
    'js/view/ExplorerListMobileView.js',
    'js/view/ExplorerListTinyView.js',
    'js/view/utils/twoLinesIntersection.js',
    'js/view/utils/RotateMapArrow.js',
    'js/view/ExplorerMapView.js',
    'js/view/ExplorerClusterRoseView.js',
    'js/view/ExplorerMapInfoBubbleView.js',
    'js/view/ExplorerMapInfoView.js',
    'js/view/ExplorerMapInfoMobileView.js',
    'js/Explorer.js',
    //'styles/explorerMapArrows.scss'
  );


  public function __construct() {

  }


  function setGeozzyUrlPatternsAPI() {
    $this->addUrlPatterns( '#^api/explorer/(.*)#', 'view:ExplorerAPIView::explorer' );
    $this->addUrlPatterns( '#^api/doc/explorer.json#', 'view:ExplorerAPIView::explorerJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/explorerList$#', 'view:ExplorerAPIView::explorerList' );
    $this->addUrlPatterns( '#^api/doc/explorerList.json$#', 'view:ExplorerAPIView::explorerListJson' ); // Main swagger JSON
  }


  function getGeozzyDocAPI() {

    $ret = array(
      array(
        'path'=> '/doc/explorer.json',
        'description' => 'Explorer API'
      ),
      array(
        'path'=> '/doc/explorerList.json',
        'description' => 'Explorer List API'
      )
    );

    return $ret;
  }

}
