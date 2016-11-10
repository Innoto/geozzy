<?php
Cogumelo::load('view/MasterView.php');

class ExamplesView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  public function registerForm() {
/*
    $form = new FormController('registerForm'); //actionform
    $form->setAction('/webview/senduserregister');
    $form->setSuccess( 'accept', 'Usuario creado correctamente' );

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
    $template->setTpl('Example_register.tpl');

    $template->exec();
*/
  }


  public function sendRegisterForm() {
    /*
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
    */
  }

}
