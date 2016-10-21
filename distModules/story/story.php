<?php

Cogumelo::load("coreController/Module.php");

class story extends Module {
  public $name = "story";
  public $version = 1.0;

  public $dependences = array(
    array(
      "id" =>"mathjs",
      "params" => array("mathjs"),
      "installer" => "bower",
      "includes" => array('dist/math.min.js')
    ),
    array(
      'id' =>'CanvasLayer',
      'params' => array('CanvasLayer'),
      'installer' => 'manual',
      'includes' => array('src/CanvasLayer.js',)
    ),
    array(
      'id' =>'chap-links-library',
      'params' => array('chap-links-library'),
      'installer' => 'bower',
      'includes' => array()
    )
  );

  public $includesCommon = array(
/*
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
    'js/view/filters/ExplorerFilterSwitchView.js',
    'js/view/filters/ExplorerFilterResetView.js',
    'js/view/ExplorerActiveListView.js',
    'js/view/ExplorerActiveListTinyView.js',
    'js/view/ExplorerReccommendedListTinyView.js',
    'js/view/ExplorerMapView.js',
    'js/view/ExplorerClusterRoseView.js',
    'js/view/ExplorerMapInfoBubbleView.js',
    'js/view/ExplorerMapInfoView.js',
    'js/Explorer.js',
    'styles/explorerMapArrows.less'
*/
  );


  public function __construct() {
    $this->addUrlPatterns( '#^api/story/(.*)#', 'view:StoryAPIView::story' );
    $this->addUrlPatterns( '#^api/doc/story.json#', 'view:StoryAPIView::storyJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/storyList$#', 'view:StoryAPIView::storyList' );
    $this->addUrlPatterns( '#^api/doc/storyList.json$#', 'view:StoryAPIView::storyListJson' ); // Main swagger JSON
  }

}
