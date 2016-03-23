<?php


Cogumelo::load("coreController/Module.php");


class geozzyUser extends Module
{
  public $name = "geozzyUser";
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

  public $includesCommon = array(
    'js/router/UserRouter.js',
    'js/model/UserSessionModel.js',
    'js/view/Templates.js',
    'js/view/UserLoginBoxView.js',
    'js/view/UserRegisterBoxView.js',
    'js/view/UserRegisterOkBoxView.js',
    'js/UserSession.js',
    'js/UserSessionInstance.js'
  );


  function __construct() {
    $this->addUrlPatterns( '#^geozzyuser/logout$#', 'view:GeozzyUserView::sendLogout' );
    $this->addUrlPatterns( '#^geozzyuser/login$#', 'view:GeozzyUserView::loginForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserlogin$#', 'view:GeozzyUserView::sendLoginForm' );
    $this->addUrlPatterns( '#^geozzyuser/register$#', 'view:GeozzyUserView::registerForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserregister$#', 'view:GeozzyUserView::sendRegisterForm' );
  }

}
