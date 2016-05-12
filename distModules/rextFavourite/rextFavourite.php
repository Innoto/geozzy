<?php

Cogumelo::load( 'coreController/Module.php' );

class rextFavourite extends Module {

  public $name = 'rextFavourite';
  public $version = 1.0;


  public $models = array( 'FavouritesViewModel' );

  public $taxonomies = array();

  public $dependences = array(
    array(
     'id' =>'underscore',
     'params' => array('underscore#1.8.3'),
     'installer' => 'bower',
     'includes' => array('underscore-min.js')
    ),
    array(
     'id' =>'backbonejs',
     'params' => array('backbone#1.1.2'),
     'installer' => 'bower',
     'includes' => array('backbone.js')
    )
  );


  public $autoIncludeAlways = true;
  public $includesCommon = array(
    'js/rExtFavouriteController.js',
    'controller/RExtFavouriteController.php',
    'model/FavouritesViewModel.php',
    /*
    'js/router/UserRouter.js',
    'js/model/UserSessionModel.js',
    'js/view/Templates.js',
    'js/view/UserLoginBoxView.js',
    'js/view/UserRegisterBoxView.js',
    'js/view/UserRegisterOkBoxView.js',
    'js/UserSession.js',
    'js/userSessionInstance.js'
    */
  );


  public function __construct() {
    $this->addUrlPatterns( '#^geozzyFavourite/command$#', 'view:RExtFavouriteView::execCommand' );
  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
