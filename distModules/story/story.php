<?php

Cogumelo::load("coreController/Module.php");

class story extends Module {
  public $name = "story";
  public $version = 1.6;

  public $dependences = array(
    array(
      "id" =>"mathjs",
      "params" => array("mathjs#4.0.1"),
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
      'includes' => array( 'js/src/timeline/timeline-locales.js', 'js/src/timeline/timeline-min.js', 'js/src/timeline/timeline.css' )
    )
  );

  public $includesCommon = array(
    'js/model/StoryStepModel.js',
    'js/router/StoryRouter.js',
    'js/collection/StoryStepCollection.js',
    'js/view/StoryTemplates.js',
    'js/view/StoryList.js',
    'js/view/StoryListAcc.js',
    'js/view/StoryBackground.js',
    'js/view/plugins/StoryPluginLegendView.js',
    'js/view/plugins/StoryPluginPOISView.js',
    'js/view/plugins/StoryPluginKMLView.js',
    'js/view/plugins/StoryPluginTimelineView.js',
    'js/Story.js'
  );


  public function __construct() {
  }

  function setGeozzyUrlPatternsAPI() {
    global $COGUMELO_INSTANCED_MODULES;

    //$this->addUrlPatterns( '#^api/story/(.*)#', 'view:StoryAPIView::story' );
    //$this->addUrlPatterns( '#^api/doc/story.json#', 'view:StoryAPIView::storyJson' ); // Main swagger JSON
    //$this->addUrlPatterns( '#^api/storyList$#', 'view:StoryAPIView::storyList' );
    //$this->addUrlPatterns( '#^api/doc/storyList.json$#', 'view:StoryAPIView::storyListJson' ); // Main swagger JSON

    $COGUMELO_INSTANCED_MODULES['rtypeStoryStep']->addUrlPatterns( '#^api/storySteps/(.*)$#', 'view:StoryStepAPIView::storySteps' );
    $COGUMELO_INSTANCED_MODULES['rtypeStoryStep']->addUrlPatterns( '#^api/doc/storySteps.json$#', 'view:StoryStepAPIView::storyStepsJson' );


    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();
    if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) ) {
      $COGUMELO_INSTANCED_MODULES['rtypeStory']->addUrlPatterns( '#^api/admin/adminStories$#', 'view:StoryAdminAPIView::stories' );
      $COGUMELO_INSTANCED_MODULES['rtypeStory']->addUrlPatterns( '#^api/admin/adminStories.json$#', 'view:StoryAdminAPIView::storiesJson' );
    }
  }

  function getGeozzyDocAPI() {

    $ret = array(
      /*array(
        'path'=> '/doc/story.json',
        'description' => 'Stories API'
      ),*/
      /*array(
        'path'=> '/doc/storyList.json',
        'description' => 'Stories List API'
      ),*/
      array(
        'path'=> '/doc/storySteps.json',
        'description' => 'Stories Steps API'
      )
    );


    //user::autoIncludes();
    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();


    if( $useraccesscontrol->isLogged() && ($useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full')) ) {
      $ret[] = array(
        'path' => '/admin/adminStories.json',
        'description' => 'Admin Stories'
      );
      $ret[] = array(
        'path' => '/admin/adminStorySteps.json',
        'description' => 'Admin Story Steps'
      );
    }

    return $ret;
  }
}
