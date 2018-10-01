<?php

Cogumelo::load("coreController/Module.php");


class geozzyUser extends Module {

  public $name = "geozzyUser";
  public $version = 1.0;


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
    'js/router/UserRouter.js',
    'js/model/UserSessionModel.js',
    'js/view/Templates.js',
    'js/view/UserLoginBoxView.js',
    'js/view/UserRegisterBoxView.js',
    'js/view/UserRegisterOkBoxView.js',
    'js/UserSession.js',
    'js/userSessionInstance.js'
  );

  function __construct() {
    $this->addUrlPatterns( '#^geozzyuser/logout(\/.+)?$#', 'view:GeozzyUserView::sendLogout' );
    $this->addUrlPatterns( '#^geozzyuser/login$#', 'view:GeozzyUserView::loginForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserlogin$#', 'view:GeozzyUserView::sendLoginForm' );
    $this->addUrlPatterns( '#^geozzyuser/register$#', 'view:GeozzyUserView::registerForm' );
    $this->addUrlPatterns( '#^geozzyuser/wv/register$#', 'view:GeozzyUserView::registerWVForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserregister$#', 'view:GeozzyUserView::sendRegisterForm' );
    $this->addUrlPatterns( '#^geozzyuser/profile#', 'view:GeozzyUserView::myProfileForm' );
    $this->addUrlPatterns( '#^geozzyuser/senduserbaseprofile#', 'view:GeozzyUserView::sendUserBaseProfileForm' );
    $this->addUrlPatterns( '#^geozzyuser/resource/sendresource#', 'view:GeozzyUserView::sendUserProfileResourceForm' );
    $this->addUrlPatterns( '#^geozzyuser/verify/(\d+)/(.+)$#', 'view:GeozzyUserView::checkVerifyLink' );
    $this->addUrlPatterns( '#^geozzyuser/unknownpass/(\d+)/(.+)$#', 'view:GeozzyUserView::checkUnknownPass' );
    $this->addUrlPatterns( '#^geozzyuser/recoveryPassword/#', 'view:GeozzyUserView::checkUnknownPass' );

  }

  public function moduleRc() {

    $res = array(
      array(
        'idName' => 'user',
        'rType' => 'rtypePage',
        'title' => array(
          'en' => 'User',
          'es' => 'Usuario',
          'gl' => 'Usuario'
        ),
        'shortDescription' => array(),
        'viewType' => 'tplEmpty',
        'urlAlias' => array(
          'en' => '/userprofile',
          'es' => '/userprofile',
          'gl' => '/userprofile'
        )
      ),
      array(
        'idName' => 'userVerifiedOk',
        'rType' => 'rtypePage',
        'title' => array(
          'en' => 'You have successfully completed registration',
          'es' => 'Has completado el registro con éxito',
          'gl' => 'Completaches o rexistro con éxito'
        ),
        'shortDescription' => array(
          'en' => 'You have successfully completed registration',
          'es' => 'Has completado el registro con éxito',
          'gl' => 'Completaches o rexistro con éxito'
        ),
        'viewType' => 'tplDefault',
        'urlAlias' => array(
          'en' => '/userverified/ok',
          'es' => '/userverified/ok',
          'gl' => '/userverified/ok'
        )
      ),
      array(
        'idName' => 'userVerifiedNotOk',
        'rType' => 'rtypePage',
        'title' => array(
          'en' => 'Verification link is no longer valid',
          'es' => 'El enlace de verificación ya no es válido',
          'gl' => 'A ligazón de verificación xa non é válido'
        ),
        'shortDescription' => array(
          'en' => 'Verification link is no longer valid',
          'es' => 'El enlace de verificación ya no es válido',
          'gl' => 'A ligazón de verificación xa non é válido'
        ),
        'viewType' => 'tplDefault',
        'urlAlias' => array(
          'en' => '/userverified/notok',
          'es' => '/userverified/notok',
          'gl' => '/userverified/notok'
        )
      )

    );

    initResources::load('controller/InitResourcesController.php');
    $initResources = new InitResourcesController();

    foreach ($res as $r){
      $initResources->generateResource( $r );
    }

  }

}
