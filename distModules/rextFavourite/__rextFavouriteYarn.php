<?php

Cogumelo::load( 'coreController/Module.php' );

class rextFavourite extends Module {

  public $name = 'rextFavourite';
  public $version = 1.1;


  public $models = array( 'FavouritesViewModel' );

  public $taxonomies = array();

  public $dependences = array(
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
    )
  );


  public $autoIncludeAlways = true;
  public $includesCommon = array(
    'js/rExtFavouriteController.js',
    'controller/RExtFavouriteController.php',
    'model/FavouritesViewModel.php'
  );


  public function __construct() {
    // $this->addUrlPatterns( '#^geozzyFavourite/command$#', 'view:RExtFavouriteView::execCommand' );
  }

  function setGeozzyUrlPatternsAPI() {

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();

    if( $useraccesscontrol->isLogged() ) {
      $this->addUrlPatterns( '#^api/favourites$#', 'view:RExtFavouriteAPIView::apiQuery' );
      $this->addUrlPatterns( '#^api/doc/favourites.json$#', 'view:RExtFavouriteAPIView::apiInfoJson' );
    }
  }

  function getGeozzyDocAPI() {
    $ret = [];

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();

    if( $useraccesscontrol->isLogged() ) {
      $ret = array(
        array(
          'path'=> '/doc/favourites.json',
          'description' => 'Favourites API'
        )
      );
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
