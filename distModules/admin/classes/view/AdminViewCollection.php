<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyCollectionView.php' );

class AdminViewCollection extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  /**
    Creacion/Edicion de Recursos
  */

  public function createForm() {
    $formName = 'collectionCreate';
    $formUrl = '/admin/collection/sendcollection';

    $collectionView = new GeozzyCollectionView();

    $formBlock = $collectionView->getFormBlock( $formName, $formUrl, false );
    $formBlock->setTpl( 'collectionFormBlockBase.tpl', 'admin' );

    // Template base
    $this->template->assign( 'headTitle', __('Create Collection') );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->addToBlock( 'col12', $this->getPanelBlock( $formBlock, 'Edit Collection', 'fa-archive' ) );
    $this->template->exec();
  } // function createForm()


  public function editForm( $urlParams = false ) {
    $formName = 'collectionCreate';
    $formUrl = '/admin/collection/sendcollection';

    $collection = false;

    if( isset( $urlParams['1'] ) ) {
      $idCollection = $urlParams['1'];
      $collectionsModel = new CollectionModel();
      $collectionsList = $collectionsModel->listItems( array( 'affectsDependences' =>
        array( 'FiledataModel', 'CollectionResourcesModel' ),
        'filters' => array( 'id' => $idCollection ) ) );
      $collection = $collectionsList->fetch();
    }

    if( $collection ) {
      $collectionData = $collection->getAllData();
      $collectionData = $collectionData[ 'data' ];

      // Cargo los datos de image dentro de los del collection
      $fileDep = $collection->getterDependence( 'image' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $fileData = $fileModel->getAllData();
          $collectionData[ 'image' ] = $fileData[ 'data' ];
        }
      }

      // Cargo los datos de recursos asociados a la collection
      $resourcesDep = $collection->getterDependence( 'id', 'CollectionResourcesModel');
      if( $resourcesDep !== false ) {
        foreach( $resourcesDep as $resourceRel ) {
          $resourcesArray[] = $resourceRel->getter('resource');
        }
        $collectionData[ 'resources' ] = $resourcesArray;
        // error_log( 'resourcesArray: '.print_r( $resourcesArray, true ) );
      }



      $collectionView = new GeozzyCollectionView();
      error_log( 'collectionData para FORM: ' . print_r( $collectionData, true ) );
      $formBlock = $collectionView->getFormBlock( $formName,  $formUrl, $collectionData );

      // Cambiamos el template del formulario
      $formBlock->setTpl( 'collectionFormBlockBase.tpl', 'admin' );

      // Template base
      $this->template->assign( 'headTitle', __('Edit Collection') );
      $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
      $this->template->addToBlock( 'col12', $this->getPanelBlock( $formBlock, 'Edit Collection', 'fa-archive' ) );
      $this->template->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder a la collection indicada.' );
    }
  } // function collectionEditForm()




  public function sendCollectionForm() {
    $collectionView = new GeozzyCollectionView();
    $collectionView->actionForm();
  } // sendCollectionForm()

}
