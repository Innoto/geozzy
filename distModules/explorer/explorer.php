<?php

Cogumelo::load("coreController/Module.php");

class explorer extends Module {
  public $name = "explorer";
  public $version = 3.0;

  public $dependences = array(

    array(
      "id" =>"google-maps-utility-library-v3-markerwithlabel",
      "params" => array("google-maps-utility-library-v3-markerwithlabel"),
      "installer" => "bower",
      "includes" => array("dist/markerwithlabel.min.js")
    ),
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
     "id" =>"backbone-fetch-cache.innoto",
     "params" => array("backbone-fetch-cache.innoto"),
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
      "id" =>"switchery",
      "params" => array("switchery"),
      "installer" => "bower",
      "includes" => array("dist/switchery.min.js", "dist/switchery.min.css")
    ),
    array(
     "id" =>"ionrangeslider",
     "params" => array("ionrangeslider#2"),
     "installer" => "bower",
     "includes" => array("js/ion.rangeSlider.min.js", "css/ion.rangeSlider.css")
    ),
    array(
     "id" =>"tiny_map_utilities",
     "params" => array("tiny_map_utilities"),
     "installer" => "bower",
     "includes" => array("smart_infowindow/smart_infowindow.js", "smart_infowindow/vendor/jQueryRotate.js")
    ),
    array(
      "id" =>"raphael",
      "params" => array("raphael"),
      "installer" => "bower",
      "includes" => array("raphael.min.js")
    ),
    array(
      'id' =>'jquery-mapael-2.1.0',
      'params' => array( 'jquery-mapael-2.1.0' ),
      'installer' => 'manual',
      'includes' => array( "js/jquery.mapael.min.js")
    ),
    array(
     'id' =>'bootstrap-daterangepicker',
     'params' => array( 'bootstrap-daterangepicker' ),
     'installer' => 'bower',
     'includes' => array( 'daterangepicker.js', 'daterangepicker.css' )
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
    'js/view/filters/ExplorerFilterDatepickerView.js',    
    'js/view/filters/ExplorerFilterComboView.js',
    'js/view/filters/ExplorerFilterMinimapView.js',
    'js/view/filters/ExplorerFilterGeoView.js',
    'js/view/filters/ExplorerFilterSwitchView.js',
    'js/view/filters/ExplorerFilterSliderView.js',
    'js/view/filters/ExplorerFilterSwitchView.js',
    'js/view/filters/ExplorerFilterResetView.js',
    'js/view/ExplorerBIView.js',
    'js/view/ExplorerActiveListView.js',
    'js/view/ExplorerActiveListMobileView.js',
    'js/view/ExplorerActiveListTinyView.js',
    'js/view/ExplorerReccommendedListTinyView.js',
    'js/view/utils/twoLinesIntersection.js',
    'js/view/utils/RotateMapArrow.js',
    'js/view/ExplorerMapView.js',
    'js/view/ExplorerClusterRoseView.js',
    'js/view/ExplorerMapInfoBubbleView.js',
    'js/view/ExplorerMapInfoView.js',
    'js/view/ExplorerMapInfoMobileView.js',
    'js/Explorer.js',
    //'styles/explorerMapArrows.less'
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
