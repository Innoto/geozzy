<?php
Cogumelo::load('view/MasterView.php');
geozzy::load( 'view/GeozzyResourceView.php' );


class RecursoView extends MasterView
{

  private $formName = 'resourceCreate';
  private $formUrl = '/recurso-form-action';

  // PROBAS !!!!!!
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  // PROBAS !!!!!!
  /**
    Defino y muestro un formulario de creacion
  */
  // PROBAS !!!!!!
  public function crearForm() {
    error_log( "RecursoView: crearForm()" );

    $resourceView = new GeozzyResourceView();
    $formBlock = $resourceView->getFormBlock( $this->formName,  $this->formUrl, false );
    $this->template->setBlock( 'formNewResourceBlock', $formBlock );

    $this->template->setTpl( 'probandoFormRecurso.tpl' );
    //$this->template->setTpl( 'string:{$css_includes}{$js_includes}{$formNewResourceBlock}' );
    $this->template->exec();
  } // function crearForm()



  // PROBAS !!!!!!
  /**
    Defino y muestro un formulario de edicion
  */
  // PROBAS !!!!!!
  public function editarForm( $urlParams = false ) {
    error_log( "RecursoView: editarForm()". print_r( $urlParams, true ) );

    $recurso = false;

    if( isset( $urlParams['1'] ) ) {
      $idResource = $urlParams['1'];
      $recModel = new ResourceModel();
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ),
        'filters' => array( 'id' => $idResource ) ) );
      $recurso = $recursosList->fetch();
    }

    if( $recurso ) {
      $recursoData = $recurso->getAllData();

      //error_log( $recursoData );

      $resourceView = new GeozzyResourceView();
      $formBlock = $resourceView->getFormBlock( $this->formName,  $this->formUrl, $recursoData[ 'data' ] );
      $this->template->setBlock( 'formNewResourceBlock', $formBlock );

      $this->template->setTpl( 'probandoFormRecurso.tpl' );
      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function editarForm()


  // PROBAS !!!!!!
  /**
    Visualizamos el Recurso
  */
  // PROBAS !!!!!!
  public function verRecurso( $urlParams = false ) {
    error_log( "RecursoView: showRecurso()" . print_r( $urlParams, true ) );

    $resourceView = new GeozzyResourceView();
    $resourceView->showResourcePage( $urlParams );
  } // function verRecurso()


  // PROBAS !!!!!!
  public function verRecurso2( $urlParams = false ) {
    error_log( "RecursoView: showRecurso2()" . print_r( $urlParams, true ) );

    $idResource = false;

    $this->template->assign( 'langCogumelo', $GLOBALS['C_LANG'] );

    $recModel = new ResourceModel();
    if( isset( $urlParams['1'] ) ) {
      $idResource = $urlParams['1'];
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ),
        'filters' => array( 'id' => $idResource ) ) );
    }
    else {
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ),
        'order' => array( 'id' => -1 ) ) );
    }
    $recurso = $recursosList->fetch();

    //cogumelo::console( $recurso );
    $allData = $recurso->getAllData();
    $htmlRecurso = "\n<pre>\n" . print_r( $allData, true ) . "\n</pre>\n";

    foreach( $recurso->getCols() as $key => $value ) {
      $this->template->assign( $key, $recurso->getter( $key ) );
      // error_log( $key . ' === ' . print_r( $recurso->getter( $key ), true ) );
    }


    // Cargo los datos de image dentro de los del recurso
    $fileDep = $recurso->getterDependence( 'image' );
    if( $fileDep !== false ) {
      $titleImage = $fileDep['0']->getter('title');
      $this->template->assign( 'image', '<img src="/cgmlImg/' . $fileDep['0']->getter('id') . '"
        alt="' . $titleImage . '" title="' . $titleImage . '"></img>' );
      error_log( 'getterDependence fileData: ' . print_r( $fileDep['0']->getAllData(), true ) );
    }
    else {
      $this->template->assign( 'image', '<p>'.__('None').'</p>' );
    }


    // htmlspecialchars({$output}, ENT_QUOTES, SMARTY_RESOURCE_CHAR_SET);

    $this->template->assign( 'resourceHtml', $htmlRecurso );

    $this->template->setTpl( 'verRecurso.tpl' );
    $this->template->exec();
  } // function verRecurso2()



  // PROBAS !!!!!!
  /**
    Proceso formulario crear/editar Recurso
  */
  // PROBAS !!!!!!
  public function actionResourceForm() {
    error_log( "RecursoView: actionResourceForm()" );

    $resourceView = new GeozzyResourceView();
    $resourceView->actionResourceForm();
  } // actionResourceForm()


} // class showRecurso



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
