<?php
Cogumelo::load('coreView/View.php');



class GeozzyResourceView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
    filedata::autoIncludes();
    //user::autoIncludes();
  }

  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }



  /**
    Defino un formulario con su TPL como Bloque
  */
  public function formCreateBlock( $valuesArray = false, $formName = 'resourceCreate', $urlAction = '/recurso-form-action' ) {
    error_log( "RecursoView: formCreateBlock()" );

    $langAvailable = false;
    $this->template->assign( 'JsLangAvailable', 'false' );
    $this->template->assign( 'JsLangDefault', 'false' );
    if( defined( 'LANG_AVAILABLE' ) ) {
      $langAvailable = explode( ',', LANG_AVAILABLE );
      $langDefault = LANG_DEFAULT;
      $tmp = implode( "', '", $langAvailable );
      $this->template->assign( 'JsLangAvailable', "['".$tmp."']" );
      $this->template->assign( 'JsLangDefault', "'".LANG_DEFAULT."'" );
    }

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', 'Gracias por participar' );

    // 'image' 'type'=>'FOREIGN','vo' => 'FiledataModel','key' => 'id'
    // 'loc'   'type' => 'GEOMETRY'
    $campos = array(
      'headKeywords' => array(
        'params' => array( 'label' => 'Label de headKeywords' ),
        'rules' => array( 'maxlength' => '150' )
      ),
      'headDescription' => array(
        'translate' => true,
        'params' => array( 'label' => 'Label de headDescription' ),
        'rules' => array( 'maxlength' => '150' )
      ),
      'headTitle' => array(
        'translate' => true,
        'params' => array( 'label' => 'Label de headTitle' ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'title' => array(
        'translate' => true,
        'params' => array( 'label' => 'Label de title' ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'shortDescription' => array(
        'translate' => true,
        'params' => array( 'label' => 'Label de shortDescription' ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'mediumDescription' => array(
        'translate' => true,
        'params' => array( 'label' => 'Label de mediumDescription', 'type' => 'textarea', 'htmlEditor' => 'true' )
      ),
      'content' => array(
        'translate' => true,
        'params' => array( 'label' => 'Label de content', 'type' => 'textarea',
          'value' => '<p>ola mundo<br />...probando ;-)</p>', 'htmlEditor' => 'true' )
      ),
      /*
      'image' => array(
        'params' => array( 'label' => 'Label de image', 'type' => 'file', 'id' => 'imgResource',
          'placeholder' => 'Escolle unha imaxe', 'destDir' => '/imgResource' )
      ),
      */
      'defaultZoom' => array(
        'params' => array( 'label' => 'Label de defaultZoom' ),
        'rules' => array( 'required' => true, 'max' => '20' )
      )
    );


    foreach( $campos as $fieldName => $definition ) {
      if( !isset( $definition['params'] ) ) {
        $definition['params'] = false;
      }
      if( isset( $definition['translate'] ) && $definition['translate'] === true ) {
        foreach( $langAvailable as $lang ) {
          $form->setField( $fieldName.'_'.$lang, $definition['params'] );
          if( isset( $definition['rules'] ) ) {
            foreach( $definition['rules'] as $ruleName => $ruleParams ) {
              $form->setValidationRule( $fieldName.'_'.$lang, $ruleName, $ruleParams );
            }
          }
          $form->setFieldGroup( $fieldName.'_'.$lang, $fieldName.'_translate' );
        }
      }
      else {
        $form->setField( $fieldName, $definition['params'] );
        if( isset( $definition['rules'] ) ) {
          foreach( $definition['rules'] as $ruleName => $ruleParams ) {
            $form->setValidationRule( $fieldName, $ruleName, $ruleParams );
          }
        }
      }
    }


    $form->setValidationRule( 'title_'.$langDefault, 'required' );


    //Si es una edicion, añadimos el ID y cargamos los datos
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
      $form->loadArrayValues( $valuesArray );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'label' => 'Pulsa para enviar', 'value' => 'Manda' ) );

    // Una vez que lo tenemos definido, guardamos el form en sesion
    $form->saveToSession();


    $this->template->assign( 'formOpen', $form->getHtmpOpen() );
    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );
    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'newRecursoFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function formCreateBlock()



  /**
    Proceso formulario
  */
  public function actionCreate() {
    error_log( "RecursoView: actionCreate()" );

    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    /*
    if( !$form->existErrors() ) {
      if( !$form->processFileFields() ) {
        $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea '.
          'necesario subirlos otra vez.', 'formError' );
      }
    }
    */

    if( !$form->existErrors() ) {
      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) ) {
        $valuesArray['timeLastUpdate'] = date( "Y-m-d H:i:s", time() );
      }

      error_log( print_r( $valuesArray, true ) );

      $recurso = new ResourceModel( $valuesArray );
      $recurso->save();

      /*
      if($valuesArray['image']['values']){
        $recurso->setterDependence( 'image', new FiledataModel( $valuesArray['image']['values'] ) );
        $recurso->save( array( 'affectsDependences' => true ));
      }
      else {
        $recurso->save();
      }
      */
      echo $form->jsonFormOk();
    }
    else {
      $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
      echo $form->jsonFormError();
    }

  } // function actionCreate()

} // class ResourceView extends View



/*
  public function showRecurso() {
    print "RecursoView: showRecurso()\n\n";

    $recModel = new ResourceModel();
    $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ), 'order' => array( 'id' => -1 ) ) );
    $recurso = $recursosList->fetch();

    //cogumelo::console( $recurso );
    print("<pre>\n");
    print_r( $recurso->getAllData() );
  } // function showRecurso()
*/


/*
  function userFormOk( $form ) {
    $asignRole = false;

    if( !$form->processFileFields() ) {
      $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea necesario subirlos otra vez.', 'formError' );
    }

    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();

      $user = new UserModel( $valuesArray );

      if($valuesArray['avatar']['values']){
        $user->setterDependence( 'avatar', new FiledataModel( $valuesArray['avatar']['values'] ) );
      }
      $user->save( array( 'affectsDependences' => true ));

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
*/


/*
    $campo = 'headKeywords';
    //public function setField( $fieldName, $params = false ) {
    //public function setValidationRule( $fieldName, $ruleName, $ruleParams = true ) {
    $form->setField( $campo, array( 'label' => 'Label de '.$campo ) );
    $form->setValidationRule( $campo, 'maxlength', '150' );

    $grupo = 'headDescription';
    foreach( array( 'gl', 'es', 'en' ) as $lang ) {
      $campo = $grupo.'_'.$lang;
      $form->setField( $campo, array( 'label' => 'Label de '.$grupo ) );
      $form->setValidationRule( $campo, 'maxlength', '150' );
      $form->setFieldGroup( $campo, $grupo );
    }

    $grupo = 'headTitle';
    foreach( array( 'gl', 'es', 'en' ) as $lang ) {
      $campo = $grupo.'_'.$lang;
      $form->setField( $campo, array( 'label' => 'Label de '.$grupo ) );
      $form->setValidationRule( $campo, 'maxlength', '100' );
      $form->setFieldGroup( $campo, $grupo );
    }

    $grupo = 'title';
    foreach( array( 'gl', 'es', 'en' ) as $lang ) {
      $campo = $grupo.'_'.$lang;
      $form->setField( $campo, array( 'label' => 'Label de '.$grupo ) );
      $form->setValidationRule( $campo, 'maxlength', '100' );
      $form->setFieldGroup( $campo, $grupo );
    }

    $grupo = 'shortDescription';
    foreach( array( 'gl', 'es', 'en' ) as $lang ) {
      $campo = $grupo.'_'.$lang;
      $form->setField( $campo, array( 'label' => 'Label de '.$grupo ) );
      $form->setValidationRule( $campo, 'maxlength', '100' );
      $form->setFieldGroup( $campo, $grupo );
    }

    $grupo = 'mediumDescription';
    foreach( array( 'gl', 'es', 'en' ) as $lang ) {
      $campo = $grupo.'_'.$lang;
      $form->setField( $campo, array( 'label' => 'Label de '.$grupo, 'type' => 'textarea' ) );
      $form->setFieldGroup( $campo, $grupo );
    }

    $grupo = 'content';
    foreach( array( 'gl', 'es', 'en' ) as $lang ) {
      $campo = $grupo.'_'.$lang;
      $form->setField( $campo, array( 'label' => 'Label de '.$grupo, 'type' => 'textarea' ) );
      $form->setFieldGroup( $campo, $grupo );
    }

    //$form->setField( 'image', array( 'type' => 'file', 'id' => 'inputFicheiro', 'placeholder' => 'Escolle un ficheiro JPG', 'label' => 'Colle un ficheiro JPG', 'destDir' => '/porto' ) );

    //$form->setField( 'loc', array( 'label' => 'Label de '.$campo ) );

    $campo = 'defaultZoom';
    $form->setField( $campo, array( 'label' => 'Label de '.$campo ) );
    $form->setValidationRule( $campo, 'maxlength', '100' );

    $campo = 'countVisits';
    $form->setField( $campo, array( 'label' => 'Label de '.$campo ) );
    $form->setValidationRule( $campo, 'maxlength', '100' );

    $campo = 'averageVotes';
    $form->setField( $campo, array( 'label' => 'Label de '.$campo ) );
    $form->setValidationRule( $campo, 'maxlength', '100' );
*/


/*
    $newRec = array(
      //'id' => '',
      //'type' => '',
      //'user' => '',
      //'userUpdate' => '',
      'published' => true,
      //'timeCreation' => '',
      //'timeLastUpdate' => '',
      //'timeLastPublish' => '',
      'headKeywords' => 'olameu probando',
      'headDescription_gl' => '',
      'headDescription_es' => '',
      'headDescription_en' => '',
      'headTitle_gl' => '',
      'headTitle_es' => '',
      'headTitle_en' => '',
      'title_gl' => '',
      'title_es' => '',
      'title_en' => '',
      'shortDescription_gl' => '',
      'shortDescription_es' => '',
      'shortDescription_en' => '',
      'mediumDescription_gl' => '',
      'mediumDescription_es' => '',
      'mediumDescription_en' => '',
      'content_gl' => '',
      'content_es' => '',
      'content_en' => '',
      //'image' => '',
      //'loc' => '',
      'defaultZoom' => 1234,
      'countVisits' => 5678,
      'averageVotes' => 0
    );

    $recurso = new ResourceModel( $newRec );
    print_r( $newRec );
    print_r( $recurso );
    $recurso->save();
*/


/*
    [data] => Array
    (
      [id] => 10
      [published] => 0
      [timeCreation] => 2015-04-15 18:19:33
      [timeLastUpdate] => 0000-00-00 00:00:00
      [timeLastPublish] => 0000-00-00 00:00:00
      [headKeywords] => olameu probando
      [headDescription_gl] =>
      [headDescription_es] =>
      [headDescription_en] =>
      [headTitle_gl] =>
      [headTitle_es] =>
      [headTitle_en] =>
      [title_gl] =>
      [title_es] =>
      [title_en] =>
      [shortDescription_gl] =>
      [shortDescription_es] =>
      [shortDescription_en] =>
      [mediumDescription_gl] =>
      [mediumDescription_es] =>
      [mediumDescription_en] =>
      [content_gl] =>
      [content_es] =>
      [content_en] =>
      [defaultZoom] => 1234
      [countVisits] => 5678
      [averageVotes] => 0
    )
*/


/*
    $dataVO = $recurso->listItems( array('filters' => array('id' => $request[1] )))->fetch();
    if( !$dataVO ) {
      Cogumelo::redirect( SITE_URL.'404' );
    }
*/


/*
    $user = new UserModel( $valuesArray );

    if( isset($password) ){
      $recurso->setPassword( $password );
    }
    if( $valuesArray['avatar']['values'] ){
      $recurso->setterDependence( 'avatar', new FiledataModel( $valuesArray['avatar']['values'] ) );
    }
    $recurso->save( array( 'affectsDependences' => true ));

    // Asignacion de ROLE user
    if($asignRole){
      $roleModel = new RoleModel();
      $role = $roleModel->listItems( array('filters' => array('name' => 'user') ))->fetch();
      $recursoRole = new UserRoleModel();
      if( $role ){
        $recursoRole->setterDependence( 'role', $role );
      }
      $recursoRole->setterDependence( 'user', $recurso );
      $recursoRole->save(array( 'affectsDependences' => true ));
    }
*/

