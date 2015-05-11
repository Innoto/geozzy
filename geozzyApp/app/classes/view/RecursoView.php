<?php
Cogumelo::load( 'coreView/View.php' );
geozzy::load( 'view/GeozzyResourceView.php' );

common::autoIncludes();
geozzy::autoIncludes();



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
    Defino y muestro un formulario de creacion
  */
  public function crearForm() {
    error_log( "RecursoView: crearForm()" );

    $resourceView = new GeozzyResourceView();
    $formBlock = $resourceView->formCreateBlock();
    $this->template->setBlock( 'formNewResourceBlock', $formBlock );

    $this->template->setTpl( 'probandoFormRecurso.tpl' );
    $this->template->exec();
  } // function crearForm()



  /**
    Defino y muestro un formulario de edicion
  */
  public function editarForm() {
    error_log( "RecursoView: editarForm()" );

    $resourceView = new GeozzyResourceView();
    $formBlock = $resourceView->formCreateBlock();
    $this->template->setBlock( 'formNewResourceBlock', $formBlock );

    $this->template->setTpl( 'probandoFormRecurso.tpl' );
    $this->template->exec();
  } // function editarForm()



  /**
    Visualizamos el Recurso
  */
  public function verRecurso( $urlParams = false ) {
    error_log( "RecursoView: showRecurso()" . print_r( $urlParams, true ) );

    $idResource = false;

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

    /*
    if( isset( $allData['relationship']['0']['data']['absLocation'] ) ) {
      $this->template->assign( 'image', '<img src="/cgmlformfilews/' . $allData['relationship']['0']['data']['id'] . '"></img>' );
    }
    */

    // htmlspecialchars({$output}, ENT_QUOTES, SMARTY_RESOURCE_CHAR_SET);

    $this->template->assign( 'resourceHtml', $htmlRecurso );

    $this->template->setTpl( 'verRecurso.tpl' );
    $this->template->exec();
  } // function showRecurso()


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

