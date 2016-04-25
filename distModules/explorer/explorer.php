<?php

Cogumelo::load("coreController/Module.php");

class explorer extends Module {
  public $name = "explorer";
  public $version = 1.0;

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
    ),
    array(
     "id" =>"marker-clusterer-v3-innoto",
     "params" => array("marker-clusterer-v3-innoto"),
     "installer" => "bower",
     "includes" => array("src/markerclusterer.js")
    ),
    array(
     "id" =>"ionrangeslider",
     "params" => array("ionrangeslider#2"),
     "installer" => "bower",
     "includes" => array("js/ion.rangeSlider.js", "css/ion.rangeSlider.css")
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
    'js/view/filters/ExplorerFilterSliderView.js',
    'js/view/filters/ExplorerFilterResetView.js',
    'js/view/ExplorerActiveListView.js',
    'js/view/ExplorerActiveListTinyView.js',
    'js/view/ExplorerPasiveListView.js',
    'js/view/ExplorerMapView.js',
    'js/view/ExplorerClusterRoseView.js',
    'js/view/ExplorerMapInfoView.js',
    'js/Explorer.js',
    'styles/explorerMapArrows.less'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^api/explorer/(.*)#', 'view:ExplorerAPIView::explorer' );
    $this->addUrlPatterns( '#^api/explorer.json#', 'view:ExplorerAPIView::explorerJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/explorerList$#', 'view:ExplorerAPIView::explorerList' );
    $this->addUrlPatterns( '#^api/explorerList.json$#', 'view:ExplorerAPIView::explorerListJson' ); // Main swagger JSON
  }

}
