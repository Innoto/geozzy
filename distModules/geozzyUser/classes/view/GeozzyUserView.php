<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
geozzy::load( 'view/GeozzyResourceView.php' );
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
    $form = new FormController('registerModalForm'); //actionform
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
  public function myProfileForm() {

    $useraccesscontrol = new UserAccessController();
    $userSess = $useraccesscontrol->getSessiondata();
    $user = new UserModel();
    $dataVO = $user->listItems( array(
      'filters' => array('id' => $userSess['data']['id'] ),
      'affectsDependences' => array( 'FiledataModel')
    ))->fetch();

    if(!$dataVO){
      Cogumelo::redirect( SITE_URL.'404' );
    }

    $data = $dataVO->getAllData('onlydata');
    unset( $data['password']);
    $fileDep = $dataVO->getterDependence( 'avatar' );
    if( $fileDep !== false ) {
      foreach( $fileDep as $fileModel ) {
        $fileData = $fileModel->getAllData();
        $data[ 'avatar' ] = $fileData[ 'data' ];
      }
    }

    // BASE FORM FIELDS
    $form = new FormController( 'userProfileBaseForm');
    $form->setAction('/geozzyuser/senduserbaseprofile');
    $form->setSuccess( 'redirect', '/userprofile#user/profile' );

    $fieldsInfo = array(
      'id' => array(
        'params' => array( 'type' => 'reserved', 'value' => null )
      ),
      'login' => array(
        'params' => array( 'type' => 'reserved' ),
        'rules' => array( 'required' => true )
      ),
      'active' => array(
        'params' => array( 'type' => 'reserved' )
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
        'params' => array( 'id' => 'password', 'type' => 'password', 'placeholder' => __('Password'), 'label' => __('Password') )
      ),
      'password2' => array(
        'params' => array( 'id' => 'password2', 'type' => 'password', 'placeholder' => __('Repeat password'), 'label' => __('Repeat password') )
      ),
      'avatar' => array(
        'params' => array(
          'type' => 'file',
          'id' => 'inputFicheiro',
          'placeholder' => 'Escolle un ficheiro',
          'label' => 'Avatar',
          'destDir' => '/users'
        )
      ),
      'description' => array(
        'params' => array( 'type' => 'textarea', 'placeholder' => 'Descripción'),
        'translate' => true
      ),
      'submit' => array(
        'params' => array( 'type' => 'submit', 'value' => __('Send') )
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    $form->setValidationRule( 'password', 'equalTo', '#password2' );
    $form->setValidationRule( 'email', 'email' );
    $form->setValidationRule( 'email', 'equalTo', '#repeatEmail' );
    $form->setValidationRule( 'avatar', 'minfilesize', 1024 );
    $form->setValidationRule( 'avatar', 'accept', 'image/jpeg' );


    $form->loadArrayValues( $data );

    $form->saveToSession();

    //RESOURCE PROFILE
    if( Cogumelo::getSetupValue('mod:geozzyUser:profile') && Cogumelo::getSetupValue('mod:geozzyUser:profile') != "" ){
      $rtypeName = Cogumelo::getSetupValue('mod:geozzyUser:profile');
      $userRExt = new rextUserProfileModel();
      $resUser = $userRExt->listItems(array('filters' => array('user' => $userSess['data']['id'] )))->fetch();
      $resCtrl = new ResourceController();
      $recursoData = false;

      if($resUser){
        //Update
        $recursoData = $resCtrl->getResourceData( $resUser->getter('resource') );
        $formBlockInfo = $resCtrl->getFormBlockInfo( "resProfileEdit", "/geozzyuser/resource/sendresource", $recursoData );
        $profileBlock = $formBlockInfo['template']['formProfile'];
      }else{
        //create
        $rTypeItem = false;
        $rtypeControl = new ResourcetypeModel();
        $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'idName' => $rtypeName ) ) )->fetch();
        $recursoData['rTypeId'] = $rTypeItem->getter('id');
        $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
        $recursoData['rExtUserProfile_user'] = $userSess['data']['id'];

        $formBlockInfo = $resCtrl->getFormBlockInfo( "resProfileCreate", "/geozzyuser/resource/sendresource", $recursoData );
        $profileBlock = $formBlockInfo['template']['formProfile'];
      }
    }



    $template = new Template( $this->baseDir );
    $template->setFragment( "profileBlock", $profileBlock );
    $template->addClientScript('js/userProfile.js', 'geozzyUser');
    $template->assign("userBaseFormOpen", $form->getHtmpOpen());
    $template->assign("userBaseFormFields", $form->getHtmlFieldsArray());
    $template->assign("userBaseFormClose", $form->getHtmlClose());
    $template->assign("userBaseFormValidations", $form->getScriptCode());
    $template->setTpl('userProfile.tpl', 'geozzyUser');

    echo ( $template->execToString() );
  }


  public function sendUserBaseProfileForm() {
    $userView = new UserView();
    $form = $userView->actionUserForm();
    if( $form->existErrors() ) {
      echo $form->getJsonError();
    }
    else {
      $user = $userView->userFormOk( $form );

      echo $form->getJsonOk();
    }
  }

  public function sendUserProfileResourceForm() {
    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  }
  public function sendLogout(){
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/');
  }
}
