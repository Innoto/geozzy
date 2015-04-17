<?php
Cogumelo::load( 'coreView/View.php' );
//Cogumelo::load( 'coreModel/ResourceModel.php' );

common::autoIncludes();
geozzy::autoIncludes();

form::autoIncludes();
filedata::autoIncludes();



class RecursoView extends View
{

  public function __construct( $baseDir ) {

    parent::__construct( $baseDir );
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
    Defino y muestro un formulario
  */
  public function loadForm() {
    error_log( "RecursoView: loadForm()" );

    $langAvailable = false;
    $this->template->assign( 'JsLangAvailable', 'false' );
    $this->template->assign( 'JsLangDefault', 'false' );
    if( defined( 'LANG_AVAILABLE' ) ) {
      $langAvailable = explode( ',', LANG_AVAILABLE );
      $tmp = implode( "', '", $langAvailable );
      $this->template->assign( 'JsLangAvailable', "['".$tmp."']" );
      $this->template->assign( 'JsLangDefault', "'".LANG_DEFAULT."'" );
    }

    $form = new FormController( 'probaPorto', '/form-group-action' );

    $form->setSuccess( 'accept', 'Gracias por participar' );

    /*
    'headKeywords'      'size' => 150
    'headDescription'      'size' => 150, 'multilang' => true
    'headTitle'      'size' => 100,      'multilang' => true
    'title'      'size' => 100,      'multilang' => true
    'shortDescription'      'size' => 100,     'multilang' => true
    'mediumDescription'      'type' => 'TEXT',      'multilang' => true
    'content'      'type' => 'TEXT',      'multilang' => true
    'image'
    'loc'      'type' => 'GEOMETRY'
    'defaultZoom'      'type' => 'INT'
    'countVisits'      'type' => 'INT'
    'averageVotes'      'type' => 'FLOAT'
    */

    $campo = 'headKeywords';
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


    $form->setField( 'submit', array( 'type' => 'submit', 'label' => 'Pulsa para enviar', 'value' => 'Manda' ) );

    // Una vez que hemos definido all, guardamos el form en sesion
    $form->saveToSession();


    $this->template->assign( 'formOpen', $form->getHtmpOpen() );
    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );
    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'recursoForm.tpl' );
    $this->template->exec();


    // $recurso = new ResourceModel();

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

  } // function loadForm()


  /**
    Visualizamos el Recurso
  */
  public function showRecurso() {
    print "RecursoView: showRecurso()\n\n";

    $recObj = new ResourceModel();
    $recurso = $recObj->listItems()->fetch();

    //cogumelo::console( $recurso );
    print("<pre>\n");
    print_r( $recurso );

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
  } // function loadForm()


} // class BloquesTest extends View
