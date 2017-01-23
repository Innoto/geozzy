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
      'includes' => array( 'js/src/timeline/timeline-locales.js', 'js/src/timeline/timeline.js', 'js/src/timeline/timeline.css' )
    )
  );

  public $includesCommon = array(
    'js/model/StoryStepModel.js',
    'js/router/StoryRouter.js',
    'js/collection/StoryStepCollection.js',
    'js/view/StoryTemplates.js',
    'js/view/StoryList.js',
    'js/view/StoryBackground.js',
    'js/view/plugins/StoryPluginLegendView.js',
    'js/view/plugins/StoryPluginPOISView.js',
    'js/view/plugins/StoryPluginKMLView.js',
    'js/view/plugins/StoryPluginTimelineView.js',
    'js/Story.js'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^api/story/(.*)#', 'view:StoryAPIView::story' );
    $this->addUrlPatterns( '#^api/doc/story.json#', 'view:StoryAPIView::storyJson' ); // Main swagger JSON
    $this->addUrlPatterns( '#^api/storyList$#', 'view:StoryAPIView::storyList' );
    $this->addUrlPatterns( '#^api/doc/storyList.json$#', 'view:StoryAPIView::storyListJson' ); // Main swagger JSON
  }

  static function getGeozzyDocAPI() {

    $ret = array(
      array(
        'path'=> '/doc/story.json',
        'description' => 'Stories API'
      ),
      array(
        'path'=> '/doc/storyList.json',
        'description' => 'Stories List API'
      )
    );

    return $ret;
  }
}
