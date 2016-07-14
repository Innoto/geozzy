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

    $template->exec();
  }


  public function sendRegisterForm() {
    $this->loginCheck();

    $userView = new UserView();
    $form = $userView->actionUserForm();

    if( !$form->existErrors() ) {
      $user = $userView->userFormOk( $form );

      if( $user ) {
        $userData = $user->getAllData('onlydata');
        error_log( 'USER: '.json_encode( $userData ) );

        // AutoLogueamos al usuario
        $useraccesscontrol = new UserAccessController();
        $useraccesscontrol->userAutoLogin( $userData['login'] );

        // Enviamos un mail de verificación
        if( $userData['active'] === 1 && $userData['verified'] === 0 && $userData['email'] !== '' ) {

          $this->sendVerifyEmail( $userData );
        }
      }
    }

    $form->sendJsonResponse();
  }


  public function sendVerifyEmail( $userData ) {
    error_log( 'sendVerifyEmail: '.json_encode( $userData ) );

    Cogumelo::load( 'coreController/MailController.php' );
    $mailCtrl = new MailController();

    $adresses = $userData['email'];
    $name = $userData['name'].' '.$userData['surname'];

    // ^geozzyuser/verify/([0-9a-f]+)$
    $url = Cogumelo::getSetupValue( 'setup:webBaseUrl:urlCurrent' ).
      'geozzyuser/verify/'.$userData['id'].'/'.$this->hashVerifyUser( $userData );

    $bodyPlain = new Template();
    $bodyHtml = new Template();

    $bodyPlain->setTpl( 'verifyPlain.tpl', 'geozzyUser' );
    $bodyHtml->setTpl( 'verifyHtml.tpl', 'geozzyUser' );

    $vars = array(
      'name' => $name,
      'email' => $adresses,
      'url' => $url
    );

    $mailCtrl->setBody( $bodyPlain, $bodyHtml, $vars );
    $mailCtrl->send( $adresses, 'Verifica tu email' );

    error_log( 'sendVerifyEmail vars: '.print_r( $vars, true ) );
  }


  public function checkVerifyLink( $urlParams ) {
    error_log( 'checkVerifyLink: UID='.$urlParams['1'].' CODE='.$urlParams['2'] );

    $userId = $urlParams['1'];
    $urlCode = $urlParams['2'];
    $saltString = '$5$rounds=5000$usesomesillystringforsalt$';

    $userVO = $this->getUserVO( $userId );

    if( $userVO ) {
      $userData = $userVO->getAllData('onlydata');
      $hash = $this->hashVerifyUser( $userData );


      if ( $urlCode === $hash ) {
        $userVO->setter( 'verified', 1 );
        $userVO->setter( 'timeLastUpdate', date( 'Y-m-d H:i:s', time() ) );
        $userVO->save();
        echo "OK, verificado.\n";
      }
      else {
        echo "<pre>\n\nKO. URL no valida.\n\n\n";

        var_dump( $urlParams );
        var_dump( $userData );
        var_dump( $hash );
      }
      /*
      if( hash_equals( $hashed_password, crypt( $urlCode, $hashed_password ) ) ) {
         echo "Password verified!";
      }
      */
    }
  }


  public function hashVerifyUser( $userData ) {
    $hash = false;

    $saltString = '$5$rounds=5000$usesomesillystringforsalt$';
    $str = $userData['verified'].$userData['id'].$userData['login'].$userData['email'].$userData['timeCreateUser'];
    if( isset( $userData['timeLastUpdate'] ) ) {
      $str .= $userData['timeLastUpdate'];
    }
    $criptString = strtr( crypt( $str, $saltString ), '/', '_' );
    $hash = 'A'.strtr( preg_replace( '/^\$\d\$rounds=\d+\$[^\$]+\$/', '', $criptString ), '/', '_' ).'Z'; // Quito saltString

    return $hash;
  }


  public function getUserVO( $id ) {
    $userVO = false;

    $user = new UserModel();
    $userList = $user->listItems( array(
      'filters' => array( 'id' => $id ),
      'affectsDependences' => array( 'FiledataModel' )
    ));

    $userVO = ( $userList ) ? $userList->fetch() : false;

    return $userVO;
  }


  public function myProfileForm() {
    $useraccesscontrol = new UserAccessController();
    $userSess = $useraccesscontrol->getSessiondata();

    $userVO = $this->getUserVO( $userSess['data']['id'] );

    if( !$userVO ) {
      Cogumelo::redirect( SITE_URL.'404' );
      die();
    }

    $data = $userVO->getAllData( 'onlydata' );
    unset( $data['password'] );
    $fileDep = $userVO->getterDependence( 'avatar' );
    if( $fileDep !== false ) {
      foreach( $fileDep as $fileModel ) {
        $fileData = $fileModel->getAllData();
        $data[ 'avatar' ] = $fileData[ 'data' ];
      }
    }

    // BASE FORM FIELDS
    $form = new FormController( 'userProfileBaseForm');
    $form->setAction('/geozzyuser/senduserbaseprofile');
    $form->setSuccess( 'jsEval', 'geozzy.userSessionInstance.userRouter.successProfileForm();' );

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

      $successArray[ 'jsEval' ] = 'geozzy.userSessionInstance.userRouter.successProfileForm();';

      if($resUser){
        //Update
        $recursoData = $resCtrl->getResourceData( $resUser->getter('resource') );
        $formBlockInfo = $resCtrl->getFormBlockInfo( "resProfileEdit", "/geozzyuser/resource/sendresource", $successArray, $recursoData );
        $profileBlock = $formBlockInfo['template']['formProfile'];
      }else{
        //create
        $rTypeItem = false;
        $rtypeControl = new ResourcetypeModel();
        $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'idName' => $rtypeName ) ) )->fetch();
        $recursoData['rTypeId'] = $rTypeItem->getter('id');
        $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');

        $formBlockInfo = $resCtrl->getFormBlockInfo( "resProfileCreate", "/geozzyuser/resource/sendresource", $successArray, $recursoData );
        $formBlockInfo['objForm']->setFieldValue('rExtUserProfile_user', $userSess['data']['id']);
        $formBlockInfo['objForm']->saveToSession();
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

    $template->exec();
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
