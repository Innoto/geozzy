<?php

/**
 * Controller de Recursos
 *
 * PHPMD: Suppress all warnings from these rules.
 * @SuppressWarnings(PHPMD.Superglobals)
 * @SuppressWarnings(PHPMD.ElseExpression)
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
 * @SuppressWarnings(PHPMD.CamelCaseVariableName)
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.NPathComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 **/
class GeozzyGarbageCollection {

  public $garbageCollCtrl = null;

  /**
    Busca elementos abandonados
    @param array $params Parametros
    @return bool
   */
  public function garbageCollection() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    $result = true;

    require_once( ModuleController::getRealFilePath( 'GarbageCollection.php', 'GarbageCollection' ) );
    GarbageCollection::load( 'controller/GarbageCollectionController.php' );
    $this->garbageCollCtrl = new GarbageCollectionController();
    echo "\n\n";

    $this->resourcesId = $this->garbageCollCtrl->listModelIds('resourceModel');
    // echo "\nRes. IDs: ".implode(', ', $this->resourcesId)."\n\n\n";

    $this->gcUrlAlias();
    echo "\n\n";

    $this->gcCollections();
    echo "\n\n";

    $this->gcTaxonomyModels();
    echo "\n\n";

    return $result;
  } // function garbageCollection()



  // Eliminando URLs con recursos desaparecidos
  public function gcUrlAlias() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    $objModel = new UrlAliasModel();

    // 'resourceNotIn' => ' modelTableName.resource NOT IN (?) ',
    $listModel = $objModel->listItems([
      'filters' => ['resourceNotIn' => $this->resourcesId],
      'fields' => ['id', 'resource']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        $resId = $dataObj->getter('resource');
        if( !empty( $resId ) ) {
          error_log('UrlAliasModel('.$dataObj->getter('id').')->delete() - non ten Resource');
          // $dataObj->delete();
        }
      }
    }
    echo "\n\n";
  }



  // Eliminando Collections y relelaciones descolgadas
  public function gcCollections() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    $modelName = 'CollectionModel';

    $listCollIds = $this->garbageCollCtrl->listModelIds( $modelName );


    // Limpieza inicial de relaciones "Normales"
    $this->gcCollNormalRelsRes();
    $this->gcCollNormalRelsColl( $listCollIds );


    $listCollUsedIds = array_keys( $this->garbageCollCtrl->listModelUsedIds( $modelName ) );

    $collBorrados = [];

    $objModel = new CollectionModel();
    $listModel = $objModel->listItems([
      'filters' => ['idNotIn' => $listCollUsedIds], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        $collId = $dataObj->getter('id');
        $collBorrados[] = $collId;
        error_log('CollectionModel('.$collId.')->delete() - non ten Pai');
        // $dataObj->delete();
      }
    }
    echo "\n\n";

    // echo "\ncollBorrados: ".implode( ', ', $collBorrados )."\n\n";

    if( !empty( $collBorrados ) ) {
      // Segunda limpieza de relaciones "Normales" con CollectionModel

      $listCollIds = array_diff( $listCollIds, $collBorrados );

      $this->gcCollNormalRelsRes();
      $this->gcCollNormalRelsColl( $listCollIds );
    }
    echo "\n\n";
  }

  // Eliminando relelaciones descolgadas de Resource
  public function gcCollNormalRelsRes() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    $objModel = new CollectionResourcesModel();

    $listModel = $objModel->listItems([
      'filters' => ['resourceNotIn' => $this->resourcesId], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        error_log('CollectionResourcesModel('.$dataObj->getter('id').')->delete() - non ten Resource');
        // $dataObj->delete();
      }
    }
    echo "\n\n";

    $objModel = new ResourceCollectionsModel();

    $listModel = $objModel->listItems([
      'filters' => ['resourceNotIn' => $this->resourcesId], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        error_log('ResourceCollectionsModel('.$dataObj->getter('id').')->delete() - non ten Resource');
        // $dataObj->delete();
      }
    }
    echo "\n\n";
  }

  // Eliminando relelaciones descolgadas de Collection
  public function gcCollNormalRelsColl( $listCollIds ) {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    $objModel = new CollectionResourcesModel();

    $listModel = $objModel->listItems([
      'filters' => ['collectionNotIn' => $listCollIds], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        error_log('CollectionResourcesModel('.$dataObj->getter('id').')->delete() - non ten Collection');
        // $dataObj->delete();
      }
    }
    echo "\n\n";

    $objModel = new ResourceCollectionsModel();

    $listModel = $objModel->listItems([
      'filters' => ['collectionNotIn' => $listCollIds], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        error_log('ResourceCollectionsModel('.$dataObj->getter('id').')->delete() - non ten Collection');
        // $dataObj->delete();
      }
    }
    echo "\n\n";
  }



  // Eliminando Taxonomy Models y relelaciones descolgadas
  public function gcTaxonomyModels() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    $modelName = 'TaxonomytermModel';

    $listModelIds = $this->garbageCollCtrl->listModelIds( $modelName );


    $objModel = new ResourceTaxonomytermModel();


    $listModel = $objModel->listItems([
      'filters' => ['taxonomytermNotIn' => $listModelIds], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        error_log('ResourceTaxonomytermModel('.$dataObj->getter('id').')->delete() - non ten Taxonomyterm');
        // $dataObj->delete();
      }
    }
    echo "\n\n";


    $listModel = $objModel->listItems([
      'filters' => ['resourceNotIn' => $this->resourcesId], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        error_log('ResourceTaxonomytermModel('.$dataObj->getter('id').')->delete() - non ten Resource');
        // $dataObj->delete();
      }
    }
    echo "\n\n";
  }


  /*
    ResourceModel
      ResourcetypeModel',
      UserModel',


    UrlAliasModel
      ResourceModel


    CollectionModel

    CollectionResourcesModel
      CollectionModel',
      ResourceModel',

    ResourceCollectionsModel
      ResourceModel',
      CollectionModel',


    TaxonomytermModel
      TaxonomygroupModel',

    TaxonomygroupModel

    ResourceTaxonomytermModel
      TaxonomytermModel',
      ResourceModel',




   */

}
