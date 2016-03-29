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
  public function accessCheck(){
    return true;
  }
  public function loginCheck(){
    $useraccesscontrol = new UserAccessController();
    $res = true;
    if($useraccesscontrol->isLogged()){
      Cogumelo::redirect('/');
      $res = false;
    }
    return $res;
  }

  public function loginForm() {
    $this->loginCheck();
    $userView = new UserView();

    $form = $userView->loginFormDefine();
    $form->setAction('/geozzyuser/senduserlogin');
    //$form->setSuccess( 'redirect', '/' );
    $form->setSuccess( 'jsEval', 'geozzy.userSessionInstance.successLoginBox();' );

    echo ($userView->loginFormGet( $form ));

  }
  public function sendLoginForm() {
    $this->loginCheck();
    $userView = new UserView();
    $form = $userView->actionLoginForm();
    $form->sendJsonResponse();
  }



  public function registerForm() {

    $this->loginCheck();
    $form = new FormController( 'registerModalForm', '/user/senduserform' ); //actionform

    $form->setAction('/geozzyuser/senduserregister');
    $form->setSuccess( 'jsEval', 'geozzy.userSessionInstance.successRegisterBox();' );

    $fieldsInfo = array(
      'id' => array(
        'params' => array( 'type' => 'reserved', 'value' => null )
      ),

      'login' => array(
        'params' => array( 'type' => 'reserved' ),
        'rules' => array( 'required' => true )
      ),
      'active' => array(
        'params' => array( 'type' => 'reserved', 'value'=> '1')
      ),
      'name' => array(
        'params' => array( 'placeholder' => __('Name'), 'label' => __('Name') ),
      ),
      'surname' => array(
        'params' => array( 'placeholder' => __('Surname'), 'label' => __('Surname') ),
      ),
      'email' => array(
        'params' => array( 'id' => 'email', 'placeholder' => __('Email'), 'label' => __('Email') ),
        'rules' => array( 'required' => true )
      ),
      'repeatEmail' => array(
        'params' => array( 'id' => 'repeatEmail', 'placeholder' => __('Repeat email'), 'label' => __('Repeat email') ),
        'rules' => array( 'required' => true )
      ),
      'password' => array(
        'params' => array( 'id' => 'password', 'type' => 'password', 'placeholder' => __('Password'), 'label' => __('Password') ),
        'rules' => array( 'required' => true )
      ),
      'password2' => array(
        'params' => array( 'id' => 'password2', 'type' => 'password', 'placeholder' => __('Repeat password'), 'label' => __('Repeat password') ),
        'rules' => array( 'required' => true )
      ),
      'submit' => array(
        'params' => array( 'type' => 'submit', 'value' => __('Create account') )
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    $form->setValidationRule( 'password', 'equalTo', '#password2' );
    $form->setValidationRule( 'email', 'email' );
    $form->setValidationRule( 'email', 'equalTo', '#repeatEmail' );

    $form->saveToSession();

    $template = new Template( $this->baseDir );
    $template->assign("userFormOpen", $form->getHtmpOpen());
    $template->assign("userFormFields", $form->getHtmlFieldsArray());
    $template->assign("userFormClose", $form->getHtmlClose());
    $template->assign("userFormValidations", $form->getScriptCode());
    $template->setTpl('createModalUser.tpl', 'geozzyUser');

    echo ( $template->execToString() );
  }


  public function sendRegisterForm() {
    $this->loginCheck();
    $userView = new UserView();
    $form = $userView->actionUserForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $user = $userView->userFormOk( $form );
      // AutoLogueamos al usuario
      if($user){
        $useraccesscontrol = new UserAccessController();
        $useraccesscontrol->userAutoLogin( $user->getter('login') );
      }
      // Enviamos un mail de verificación

      echo $form->getJsonOk();
    }
  }
  public function sendLogout(){
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/');
  }
}