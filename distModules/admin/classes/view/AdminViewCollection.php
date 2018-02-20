<?php
admin::load('view/AdminViewMaster.php');
geozzy::load( 'view/GeozzyCollectionView.php' );

class AdminViewCollection extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
    Creacion/Edicion de Collection
  */

  public function createForm( $urlParams = false ) {
    $formName = 'collectionCreate';
    $formUrl = '/admin/collection/sendcollection';

    if( isset( $urlParams['1'] ) ) {
      $urlParamRTypeParent = $urlParams['1'];
      $valuesArray['filterRTypeParent'] = $urlParamRTypeParent;
    }
    else{
      $valuesArray = false;
    }

    $valuesArray['collectionType'] = $_POST['colType'];
    $valuesArray['collectionSelect'] = $_POST['colSelect'];

    $collectionView = new GeozzyCollectionView();
    $formBlock = $collectionView->getFormBlock( $formName, $formUrl, $valuesArray );
    $formBlock->setTpl( 'collectionFormBlockBase.tpl', 'admin' );

    // Template base
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->addToFragment( 'col12', $this->getPanelBlock( $formBlock, __('Create Collection'), 'fa-archive' ) );
    $this->template->exec();
  } // function createForm()

  public function editForm( $urlParams = false ) {
    $formName = 'collectionEdit';
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


      if(isset( $urlParams['2'] )){
        $collectionData['filterRTypeParent'] = $urlParams['2'];
      }

      // Cargo los datos de image dentro de los del collection
      $fileDep = $collection->getterDependence( 'image' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $fileData = $fileModel->getAllData();
          $collectionData[ 'image' ] = $fileData[ 'data' ];
        }
      }

      // Cargo los datos de recursos asociados a la collection
      $collectionResourcesModel = new CollectionResourcesModel();
      $colResList = $collectionResourcesModel->listItems(
        array(
          'filters' => array( 'collection' => $idCollection ),
          'order' => array( 'weight' => 1 )
        )
      );
      while( $res = $colResList->fetch() ){
        if( $res ){
          $collectionData[ 'resources' ][] = $res->getter( 'resource' );
        }
      }

      $collectionData['collectionSelect'] = $_POST['colSelect'];

      $collectionView = new GeozzyCollectionView();
      $formBlock = $collectionView->getFormBlock( $formName,  $formUrl, $collectionData );

      // Cambiamos el template del formulario
      $formBlock->setTpl( 'collectionFormBlockBase.tpl', 'admin' );

      // Template base
      $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
      $this->template->addToFragment( 'col12', $this->getPanelBlock( $formBlock, 'Edit Collection', 'fa-archive' ) );
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
