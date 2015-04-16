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
    print "RecursoView: loadForm()\n\n";

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


    $recObj = new ResourceModel();
    $recurso = $recObj->listItems()->fetch();
    print("<pre>\n");
    print_r( $recurso );

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
