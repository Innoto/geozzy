<?php
Cogumelo::load('coreController/Module.php');


class rextLike extends Module {

  public $name = 'rextLike';
  public $version = 2;

  public $models = ['LikeViewModel'];
  public $taxonomies = [];
  public $dependences = [];

  public $autoIncludeAlways = true;
  public $includesCommon = [
    'js/rExtLikeController.js',
    'controller/RExtLikeController.php',
    'model/LikeViewModel.php'
  ];


  public function __construct() {
    // $this->addUrlPatterns( '#^geozzyLike/command$#', 'view:RExtLikeView::execCommand' );
  }

  function setGeozzyUrlPatternsAPI() {

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();

    if( $useraccesscontrol->isLogged() ) {
      $this->addUrlPatterns( '#^api/like$#', 'view:RExtLikeAPIView::apiQuery' );
      $this->addUrlPatterns( '#^api/doc/like.json$#', 'view:RExtLikeAPIView::apiInfoJson' );
    }
  }

  function getGeozzyDocAPI() {
    $ret = [];

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();

    if( $useraccesscontrol->isLogged() ) {
      $ret = [
        [
          'path'=> '/doc/like.json',
          'description' => 'Likes API'
        ]
      ];
    }

    return $ret;
  }



  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }


}
