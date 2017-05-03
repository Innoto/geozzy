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

  public function __construct( $base_dir = false ) {
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
  public function commonFields() {
    return array(
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
      ),
      'password2' => array(
        'params' => array( 'id' => 'password2', 'type' => 'password', 'placeholder' => __('Repeat password'), 'label' => __('Repeat password') ),
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
      'legal' => array(
        'params' => array( 'type' => 'checkbox', 'name' => 'legal',
          'options' => [ '1' => __( 'Acepto las <a class="gzzAppPrivacidad">condiciones de servicio y la política de privacidad</a>' ) ]
        ),
        'rules' => [ 'required' => true ]
      ),
      'submit' => array(
        'params' => array( 'type' => 'submit', 'value' => __('Create account') )
      )
    );
    //data-toggle="modal" data-target="#link-info-legal"
  }
  public function registerForm() {

    $this->loginCheck();
    $form = new FormController('registerModalForm'); //actionform
    $form->setAction('/geozzyuser/senduserregister');
    $form->setSuccess( 'jsEval', 'geozzy.userSessionInstance.successRegisterBox();' );

    $fields = $this->commonFields();
    unset( $fields['description'] );
    unset( $fields['avatar'] );

    $form->definitionsToForm( $fields );

    $form->setValidationRule( 'password', 'required', true );
    $form->setValidationRule( 'password2', 'required', true );
    $form->setValidationRule( 'password', 'equalTo', '#password2' );
    $form->setValidationRule( 'email', 'email' );
    $form->setValidationRule( 'email', 'equalTo', '#repeatEmail' );

    $form->captchaEnable( true );

    $form->saveToSession();

    $template = new Template( $this->baseDir );
    $template->assign("userFormOpen", $form->getHtmpOpen());
    $template->assign("userFormFields", $form->getHtmlFieldsArray());
    $template->assign("userFormClose", $form->getHtmlClose());
    $template->assign("formCaptcha" ,$form->getHtmlCaptcha());
    $template->assign("userFormValidations", $form->getScriptCode());
    $template->setTpl('createModalUser.tpl', 'geozzyUser');

    $template->exec();
  }

  public function registerWVForm() {

    $this->loginCheck();

    $form = new FormController('registerModalForm'); //actionform
    $form->setAction('/geozzyuser/senduserregister');
    $form->setSuccess( 'jsEval', 'GeozzyMobileApp.resultGeozzyUserRegistration(true);' );

    $fields = $this->commonFields();
    unset( $fields['description'] );
    unset( $fields['avatar'] );
    $form->definitionsToForm( $fields );

    $form->setValidationRule( 'password', 'required', true );
    $form->setValidationRule( 'password2', 'required', true );
    $form->setValidationRule( 'password', 'equalTo', '#password2' );
    $form->setValidationRule( 'email', 'email' );
    $form->setValidationRule( 'email', 'equalTo', '#repeatEmail' );

    $form->captchaEnable( true );

    $form->saveToSession();

    $template = new Template( $this->baseDir );
    $template->assign("userFormOpen", $form->getHtmpOpen());
    $template->assign("userFormFields", $form->getHtmlFieldsArray());
    $template->assign("userFormClose", $form->getHtmlClose());
    $template->assign("formCaptcha" ,$form->getHtmlCaptcha());
    $template->assign("userFormValidations", $form->getScriptCode());
    $template->setTpl('registerWV.tpl', 'geozzyUser');


    // Usamos nuestros propios datos
    $formBlockInfo['data']['title'] = __('User Registration');
    $formBlockInfo['data']['headDescription'] = __('User Registration');
    $formBlockInfo['data']['urlAlias'] = '/geozzyuser/registerWV';
    $formBlockInfo['ext']= '';

    // Nos quedamos solo con los template que nos interesan
    $formBlockInfo['header'] = false;
    $formBlockInfo['footer'] = false;
    $formBlockInfo['template'] = [
      'registerWV' => $template
    ];

    appResourceBridge::load('view/AppResourceBridgeView.php');
    $bridgeCtrl = new AppResourceBridgeView();
    $pageTemplate = $bridgeCtrl->getResourcePageTemplate( $formBlockInfo );
    if( $pageTemplate ) {
      $pageTemplate->addClientStyles( 'styles/masterWV.less' );
      $pageTemplate->exec();
    }
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
      'geozzyuser/verify/'.$userData['id'].'/'.$this->hashVerifyUser( $userData, 'VerifyEmailLabel' );

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
      $hash = $this->hashVerifyUser( $userData, 'VerifyEmailLabel' );

      $resourceCtrl = new ResourceController();
      $resourceModel = new ResourceModel( );

      if ( $urlCode === $hash ) {
        $userVO->setter( 'verified', 1 );
        $userVO->setter( 'timeLastUpdate', date( 'Y-m-d H:i:s', time() ) );
        $userVO->save();


        $res = $resourceModel->listItems(
          array(
            'filters'=> array(
              'idName' => 'userVerifiedOk'
            )
          )
        )->fetch();

      }
      else {

        $res = $resourceModel->listItems(
          array(
            'filters'=> array(
              'idName' => 'userVerifiedNotOk'
            )
          )
        )->fetch();

        error_log( '(Notice) checkVerifyLink: URL no valida. $urlParams '.print_r( $urlParams, true ) );
        error_log( '$userData '.print_r( $userData, true ) );
        error_log( '$hash '.print_r( $hash, true ) );
      }

      $urlAlias = $resourceCtrl->getUrlAlias( $res->getter('id') );
      Cogumelo::redirect( $urlAlias );
    }
  }

  public function sendUnknownPassEmail( $userData ) {
    error_log( 'sendUnknownPassEmail: '.json_encode( $userData ) );
    $status = false;

    Cogumelo::load( 'coreController/MailController.php' );
    $mailCtrl = new MailController();

    $adresses = $userData['email'];
    $name = $userData['name'].' '.$userData['surname'];

    // ^geozzyuser/verify/([0-9a-f]+)$
    $url = Cogumelo::getSetupValue( 'setup:webBaseUrl:urlCurrent' ).
      'geozzyuser/unknownpass/'.$userData['id'].'/'.$this->hashVerifyUser( $userData, 'UnknownPassLabel' );

    $bodyPlain = new Template();
    $bodyHtml = new Template();

    $bodyPlain->setTpl( 'unknownpassPlain.tpl', 'geozzyUser' );
    $bodyHtml->setTpl( 'unknownpassHtml.tpl', 'geozzyUser' );

    $vars = array(
      'name' => $name,
      'email' => $adresses,
      'url' => $url
    );

    $mailCtrl->setBody( $bodyPlain, $bodyHtml, $vars );
    $status = $mailCtrl->send( $adresses, 'Recuperar contraseña' );

    error_log( 'sendUnknownPassEmail vars: '.print_r( $vars, true ) );

    return $status;
  }

  public function checkUnknownPass( $urlParams ) {
    error_log( 'checkUnknownPass: UID='.$urlParams['1'].' CODE='.$urlParams['2'] );

    $userId = $urlParams['1'];
    $urlCode = $urlParams['2'];
    $saltString = '$5$rounds=5000$usesomesillystringforsalt$';

    $userVO = $this->getUserVO( $userId );

    if( $userVO ) {
      $userData = $userVO->getAllData('onlydata');
      $hash = $this->hashVerifyUser( $userData, 'UnknownPassLabel' );


      if ( $urlCode === $hash ) {
        $userVO->setter( 'timeLastUpdate', date( 'Y-m-d H:i:s', time() ) );
        $userVO->save();

        // AutoLogueamos al usuario
        $useraccesscontrol = new UserAccessController();
        $useraccesscontrol->userAutoLogin( $userData['login'] );

        Cogumelo::redirect( '/userprofile#user/profile' );

        error_log( '(Notice) OK, URL de recuperacion de contraseña. Login:'.$userData['login'].' Email:'.$userData['email'] );
      }
      else {
        echo "<pre>\n\nKO. URL no valida.\n\n\n";

        error_log( '(Notice) checkUnknownPass: URL no valida. $urlParams '.print_r( $urlParams, true ) );
        error_log( '$userData '.print_r( $userData, true ) );
        error_log( '$hash '.print_r( $hash, true ) );
      }
    }
  }

  public function hashVerifyUser( $userData, $label = 'general' ) {
    $hash = false;

    $saltString = '$5$rounds=5000$usesomesillystringforsalt$';
    $str = $label.$userData['verified'].$userData['id'].$userData['login'].$userData['email'].$userData['timeCreateUser'];
    if( isset( $userData['timeLastUpdate'] ) ) {
      $str .= $userData['timeLastUpdate'];
    }
    $criptString = strtr( crypt( $str, $saltString ), '/', '_' );
    $hash = 'A'.strtr( preg_replace( '/^\$\d\$rounds=\d+\$[^\$]+\$/', '', $criptString ), '/', '_' ).'Z'; // Quito saltString

    return $hash;
  }

  public function getUserVO( $id, $login = false ) {
    $userVO = false;

    $user = new UserModel();
    $filter = false;

    if( $id ) {
      $filter = array( 'id' => $id );
    }
    if( $login ) {
      $filter = array( 'login' => $login );
    }
    if( $filter ) {
      $userList = $user->listItems( array( 'filters' => $filter, 'affectsDependences' => array( 'FiledataModel' ) ) );
      $userVO = ( $userList ) ? $userList->fetch() : false;
    }

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

    $fields = $this->commonFields();
    unset($fields['legal']);
    $fields['submit']['params']['value'] = __('Send');
    $form->definitionsToForm( $fields );

    $form->setValidationRule( 'password', 'equalTo', '#password2' );
    $form->setValidationRule( 'email', 'email' );
    $form->setValidationRule( 'email', 'equalTo', '#repeatEmail' );
    $form->setValidationRule( 'avatar', 'minfilesize', 1024 );
    $form->setValidationRule( 'avatar', 'accept', 'image/jpeg' );

    $data['repeatEmail'] = $data['email'];
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
    if(isset($profileBlock)){
      $template->setFragment( "profileBlock", $profileBlock );
    }
    $template->addClientScript('js/userProfile.js', 'geozzyUser');
    $template->addClientStyles('styles/master.less');
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
  public function sendLogout( $urlParams ){
    $redirect = $urlParams['1'];

    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();

    if(array_key_exists('1', $urlParams )){
      Cogumelo::redirect($redirect);
    }else{
      Cogumelo::redirect('/');
    }
    //
  }
}
