<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
form::autoIncludes();
form::loadDependence( 'ckeditor' );
user::autoIncludes();

class GeozzyUserView extends View
{

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $res = true;
    if($useraccesscontrol->isLogged()){
      Cogumelo::redirect('/');
      $res = false;
    }
    return $res;
  }


  public function loginForm() {
    $userView = new UserView();

    $form = $userView->loginFormDefine();
    $form->setAction('/geozzyuser/senduserlogin');
    //$form->setSuccess( 'redirect', '/' );
    $form->setSuccess( 'jsEval', 'userSession.successLoginBox();' );

    echo ($userView->loginFormGet( $form ));

  }


  public function sendLoginForm() {
    $userView = new UserView();
    $form = $userView->actionLoginForm();
    $form->sendJsonResponse();
  }

}
