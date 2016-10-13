<?php

Cogumelo::load( 'coreController/Module.php' );


class rtypeStoryStep extends Module {

  public $name = 'rtypeStoryStep';
  public $version = '1.6';
  public $rext = array( 'rextSocialNetwork', 'rextEvent', 'rextComment', 'rextAudioguide', 'rextStoryStep', 'rextPoiCollection' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeStoryStepController.php',
    'view/RTypeStoryStepView.php',
    'js/StorySteps.js',
    'js/collection/StoryStepCollection.js',
    'js/view/ListStoryStepView.js',
    'js/view/StoryStepsListTemplate.js',
    'js/view/StoryStepTemplate.js',
    'js/storyStepsInstance.js'
  );

  public $nameLocations = array(
    'es' => 'Paso',
    'en' => 'Step',
    'gl' => 'Paso'
  );

  public function __construct() {
    $this->addUrlPatterns( '#^admin/storysteps/(.*)/assign$#', 'view:RTypeStoryStepView::addStoryStep' );
    $this->addUrlPatterns( '#^admin/storysteps/(.*)$#', 'view:RTypeStoryStepView::StoryStepList' );
    $this->addUrlPatterns( '#^admin/story/table/(\d+)$#', 'view:RTypeStoryStepView::listStepsTable' );

    $this->addUrlPatterns( '#^api/admin/adminStorySteps/(.*)$#', 'view:StoryStepAdminAPIView::storySteps' );
    $this->addUrlPatterns( '#^api/admin/adminStorySteps.json$#', 'view:StoryStepAdminAPIView::storyStepsJson' );

  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
