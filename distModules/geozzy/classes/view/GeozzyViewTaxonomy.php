<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
form::autoIncludes();
geozzy::autoIncludes();

class GeozzyViewTaxonomy extends View
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    return true;
  }

  /**
  *
  * Update taxterm form
  * @param request(id)
  * @return Form Html
  *
  **/
  function taxtermUpdateFormDefine( $request ){

    $taxtermModel = new TaxonomytermModel();
    $taxterm = $taxtermModel->listItems( array('filters' => array('id' => $request[1] )))->fetch();
    if(!$taxterm){
      Cogumelo::redirect( SITE_URL.'404' );
    }

    $form = $this->taxtermFormDefine( $taxterm );
    return $form;
  }



  /**
   *
   * Create form fields and validations
   * @return object
   *
   **/

  function taxtermFormDefine( $taxterm = '' ) {

    $form = new FormController( 'taxtermForm', '/user/sendtaxtermform' ); //actionform
    $form->setSuccess( 'redirect', '/' );

    $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );

    $form->setField( 'name', array( 'placeholder' => 'Nombre' ) );
    $form->setField( 'surname', array( 'placeholder' => 'Apellidos' ) );
    $form->setField( 'email', array( 'placeholder' => 'Email' ) );

    $form->setField( 'description', array( 'type' => 'textarea', 'placeholder' => 'Descripción' ) );
    $form->setField( 'avatar', array( 'type' => 'file', 'id' => 'inputFicheiro',
      'placeholder' => 'Escolle un ficheiro', 'label' => 'Colle un ficheiro',
      'destDir' => '/users' ) );

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => 'Save' ) );

    /******************************************************************************************** VALIDATIONS */
    $form->setValidationRule( 'login', 'required' );
    $form->setValidationRule( 'email', 'required' );

    //Esto es para verificar si es un create
    if(!isset($dataVO) || $dataVO == ''){
      $form->setValidationRule( 'password', 'required' );
      $form->setValidationRule( 'password2', 'required' );
      $form->setValidationRule( 'password', 'equalTo', '#password2' );
    }
    $form->setValidationRule( 'avatar', 'minfilesize', 1024 );
    $form->setValidationRule( 'avatar', 'accept', 'image/png' );
    //$form->setValidationRule( 'avatar', 'required' );


    $form->setValidationRule( 'email', 'email' );

    $form->loadVOValues( $dataVO );

    return $form;
  }

   /**
   *
   * Returns necessary html form
   * @param $form
   * @return string
   *
   **/
  function userFormGet($form) {
    $form->saveToSession();

    $this->template->assign("userFormOpen", $form->getHtmpOpen());
    $this->template->assign("userFormFields", $form->getHtmlFieldsArray());
    $this->template->assign("userFormClose", $form->getHtmlClose());
    $this->template->assign("userFormValidations", $form->getScriptCode());

    $this->template->setTpl('userForm.tpl', 'user');

    return $this->template->execToString();
  }

  /**
   *
   * Assigns the forms validations
   * @return $form
   *
   **/
  function actionUserForm() {
    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
      //Validaciones extra
      $userControl = new UserModel();
      // Donde diferenciamos si es un update o un create para validar el login
      $loginExist = $userControl->listItems( array('filters' => array('login' => $form->getFieldValue('login'))) )->fetch();


      if( isset($valuesArray['id']) && $valuesArray['id'] ){
        $user = $userControl->listItems( array('filters' => array('id' => $valuesArray['id'])) )->fetch();
        if($valuesArray['login'] !== $user->getter('login')){
          if($loginExist){
            $form->addFieldRuleError('login', 'cogumelo', 'El campo login específicado ya esta en uso.');
          }
        }

      }else{
        // Create: comprobamos si el login existe y si existe mostramos error.
        if($loginExist){
          $form->addFieldRuleError('login', 'cogumelo', 'El campo login específicado ya esta en uso.');
        }
      }
    }

    return $form;
  }

  /**
   *
   * Edit/Create User
   * @return $user
   *
   **/

  function userFormOk( $form ) {
    //Si todo esta OK!
    $asignRole = false;

    if( !$form->processFileFields() ) {
      $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea necesario subirlos otra vez.', 'formError' );
    }

    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
      $valuesArray['active'] = 0;

       // Donde diferenciamos si es un update o un create
      if( !isset($valuesArray['id']) || !$valuesArray['id'] ){
        $password = $valuesArray['password'];
        unset($valuesArray['password']);
        unset($valuesArray['password2']);
        $valuesArray['timeCreateUser'] = date("Y-m-d H:i:s", time());
        $asignRole = true;
      }

      $user = new UserModel( $valuesArray );

      if(isset($password)){
        $user->setPassword( $password );
      }
      if($valuesArray['avatar']['values']){
        $user->setterDependence( 'avatar', new FiledataModel( $valuesArray['avatar']['values'] ) );
      }
      $user->save( array( 'affectsDependences' => true ));

      /*Asignacion de ROLE user*/
      if($asignRole){
        $roleModel = new RoleModel();
        $role = $roleModel->listItems( array('filters' => array('name' => 'user') ))->fetch();
        $userRole = new UserRoleModel();
        if( $role ){
          $userRole->setterDependence( 'role', $role );
        }
        $userRole->setterDependence( 'user', $user );
        $userRole->save(array( 'affectsDependences' => true ));
      }


    }
    return $user;
  }

}

