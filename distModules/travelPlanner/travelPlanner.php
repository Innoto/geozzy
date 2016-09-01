<?php

Cogumelo::load("coreController/Module.php");


class travelPlanner extends Module {

  public $name = "travelPlanner";
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
    )
  );


  public $autoIncludeAlways = true;
  public $includesCommon = array(
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

  function __construct() {
    /*
    $this->addUrlPatterns( '#^geozzyuser/logout$#', 'view:GeozzyUserView::sendLogout' );
    $this->addUrlPatterns( '#^geozzyuser/login$#', 'view:GeozzyUserView::loginForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserlogin$#', 'view:GeozzyUserView::sendLoginForm' );
    $this->addUrlPatterns( '#^geozzyuser/register$#', 'view:GeozzyUserView::registerForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserregister$#', 'view:GeozzyUserView::sendRegisterForm' );
    $this->addUrlPatterns( '#^geozzyuser/profile#', 'view:GeozzyUserView::myProfileForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserbaseprofile#', 'view:GeozzyUserView::sendUserBaseProfileForm' );
    $this->addUrlPatterns( '#^geozzyuser/resource/sendresource#', 'view:GeozzyUserView::sendUserProfileResourceForm' );
    $this->addUrlPatterns( '#^geozzyuser/verify/(\d+)/(.+)$#', 'view:GeozzyUserView::checkVerifyLink' );
    $this->addUrlPatterns( '#^geozzyuser/unknownpass/(\d+)/(.+)$#', 'view:GeozzyUserView::checkUnknownPass' );
    $this->addUrlPatterns( '#^geozzyuser/newpass/(\d+)/(.+)$#', 'view:GeozzyUserView::newPass' );
    */
  }

  public function moduleRc() {
    $res = array(
      'idName' => 'travelplanner',
      'rType' => 'rtypePage',
      'title' => array(
        'en' => 'Travel Planner',
        'es' => 'Planificador de viajes',
        'gl' => 'Planificador de viaxes'
      ),
      'shortDescription' => array(),
      'viewType' => 'tplEmpty',
      'urlAlias' => array(
        'en' => '/travelplanner',
        'es' => '/travelplanner',
        'gl' => '/travelplanner'
      )
    );

    initResources::load('controller/InitResourcesController.php');
    $initResources = new InitResourcesController();
    $initResources->generateResource( $res );
  }

}
