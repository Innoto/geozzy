<?php
Cogumelo::load('view/MasterView.php');
geozzy::load( 'view/GeozzyCollectionView.php' );

common::autoIncludes();
geozzy::autoIncludes();



class ColeccionView extends MasterView
{

  private $formName = 'collectionCreate';
  private $formUrl = '/coleccion-form-action';

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
    error_log( "ColeccionView: crearForm()" );

    $collectionView = new GeozzyCollectionView();
    $formBlock = $collectionView->getFormBlock( $this->formName,  $this->formUrl, false );

    $this->template->setBlock( 'formNewCollectionBlock', $formBlock );

    $this->template->setTpl( 'probandoFormColeccion.tpl' );
    $this->template->exec();
  } // function crearForm()



  /**
    Defino y muestro un formulario de edicion
  */
  public function editarForm( $urlParams = false ) {
    error_log( "ColeccionView: editarForm()". print_r( $urlParams, true ) );

    $collection = false;

    if( isset( $urlParams['1'] ) ) {
      $elemId = $urlParams['1'];
      $elemModel = new CollectionModel();
      $elemList = $elemModel->listItems( array( 'affectsDependences' =>
        array( 'FiledataModel', 'CollectionResourcesModel' ),
        'filters' => array( 'id' => $elemId ) ) );
      $collection = $elemList->fetch();
    }

    if( $collection ) {
      $collectionData = $collection->getAllData();
      $collectionData = $collectionData[ 'data' ];

      // Cargo los datos de image
      $fileDep = $collection->getterDependence( 'image' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $fileData = $fileModel->getAllData();
          $collectionData[ 'image' ] = $fileData[ 'data' ];
        }
      }

      // Cargo los datos de recurso asociados a la collection
      $resourcesDep = $collection->getterDependence( 'id', 'CollectionResourcesModel');
      if( $resourcesDep !== false ) {
        foreach( $resourcesDep as $resourceRel ) {
          $resourcesArray[] = $resourceRel->getter('resource');
        }
        $collectionData[ 'resources' ] = $resourcesArray;
        error_log( 'resourcesArray: '.print_r( $resourcesArray, true ) );
      }

      error_log( 'collectionData: '.print_r( $collectionData, true ) );

      $collectionView = new GeozzyCollectionView();
      $formBlock = $collectionView->getFormBlock( $this->formName,  $this->formUrl, $collectionData );
      $this->template->setBlock( 'formNewCollectionBlock', $formBlock );

      $this->template->setTpl( 'probandoFormColeccion.tpl' );
      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder a la coleccion indicada.' );
    }
  } // function editarForm()



  /**
    Visualizamos el Recurso
  */
  public function mostrar( $urlParams = false ) {
    error_log( "ColeccionView: showRecurso()" . print_r( $urlParams, true ) );

    $idCollection = false;

    $this->template->assign( 'langCogumelo', $GLOBALS['C_LANG'] );

    $recModel = new CollectionModel();
    if( isset( $urlParams['1'] ) ) {
      $idCollection = $urlParams['1'];
      $recursosList = $recModel->listItems( array( 'affectsDependences' => array( 'FiledataModel' ),
        'filters' => array( 'id' => $idCollection ) ) );
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
      $this->template->assign( 'image', '<img src="/cgmlformfilews/' . $fileDep['0']->getter('id') . '"
        alt="' . $titleImage . '" title="' . $titleImage . '"></img>' );
      error_log( 'getterDependence fileData: ' . print_r( $fileDep['0']->getAllData(), true ) );
    }
    else {
      $this->template->assign( 'image', '<p>'.__('None').'</p>' );
    }


    // htmlspecialchars({$output}, ENT_QUOTES, SMARTY_RESOURCE_CHAR_SET);

    $this->template->assign( 'allData', $htmlRecurso );

    $this->template->setTpl( 'coleccion.tpl' );
    $this->template->exec();
  } // function mostrar()



  /**
    Proceso formulario crear/editar Recurso
  */
  public function actionForm() {
    error_log( "ColeccionView: actionForm()" );

    $collectionView = new GeozzyCollectionView();
    $collectionView->actionForm();
  } // actionCollectionForm()


} // class ColeccionView
