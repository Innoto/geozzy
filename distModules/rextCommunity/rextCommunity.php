<?php

Cogumelo::load( 'coreController/Module.php' );

class rextCommunity extends Module {

  public $name = 'rextCommunity';
  public $version = 1.1;


  public $models = array( 'RExtCommunityModel', 'RExtCommunityFollowModel' );

  public $taxonomies = array();

  public $dependences = array();


  public $autoIncludeAlways = true;
  public $includesCommon = array(
    'js/rExtCommunityController.js',
    'controller/RExtCommunityController.php',
    'model/RExtCommunityModel.php',
    'model/RExtCommunityFollowModel.php',
    'model/RExtCommunityAffinityUserModel.php'
  );


  public function __construct() {
    // $this->addUrlPatterns( '#^geozzyCommunity/command$#', 'view:RExtCommunityView::execCommand' );
    $this->addUrlPatterns( '#^api/community$#', 'view:RExtCommunityAPIView::apiQuery' );
    $this->addUrlPatterns( '#^api/doc/community.json$#', 'view:RExtCommunityAPIView::apiInfoJson' );

    $this->addUrlPatterns( '#^cgml_cron/rextCommunity/prepareAffinity$#', 'view:RExtCommunityAffinityView::prepareAffinity' );
  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
