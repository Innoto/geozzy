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
    // echo "\nRes. IDs: ".implode(', ', $this->resourcesId)."\n\n";
    echo "\n\n";

    $this->gcUrlAlias();
    echo "\n\n";

    $this->gcCollections();
    echo "\n\n";

    return $result;
  } // function garbageCollection()


  // Eliminando URLs con recursos desaparecidos
  public function gcUrlAlias() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    // $modelName = 'UrlAliasModel';

    // $relations = $this->garbageCollCtrl->listModelRelations( $modelName );
    // var_dump( $relations );

    // $idFilesUsed = $this->garbageCollCtrl->listModelUsedIds( $modelName );
    // var_dump( $idFilesUsed );

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
          echo "\nSobra a URL Id:".$dataObj->getter('id')." - non hai o recurso $resId";

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
    // echo "\nlistCollIds:     ".implode( ', ', $listCollIds )."\n\n";

    // Limpieza inicial de relaciones "Normales"
    $this->gcCollNormalRelsRes();
    $this->gcCollNormalRelsColl( $listCollIds );





    $listCollUsedIds = array_keys( $this->garbageCollCtrl->listModelUsedIds( $modelName ) );
    // echo "\nlistCollUsedIds: ".implode( ', ', $listCollUsedIds )."\n\n";

    $collBorrados = [];

    $objModel = new CollectionModel();
    $listModel = $objModel->listItems([
      'filters' => ['idNotIn' => $listCollUsedIds], 'fields' => ['id']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        $collId = $dataObj->getter('id');
        $collBorrados[] = $collId;
        echo "\nSobra CollectionModel Id:".$collId." - non ten Pai";

        // $dataObj->delete();

      }
    }
    echo "\n\n";
    echo "\ncollBorrados: ".implode( ', ', $collBorrados )."\n\n";





    if( !empty( $collBorrados ) ) {
      // $listCollIds = $this->garbageCollCtrl->listModelIds( $modelName );
      $listCollIds = array_diff( $listCollIds, $collBorrados );
      // echo "\nlistCollIds: ".implode( ', ', $listCollIds )."\n\n";

      // Segunda limpieza de relaciones "Normales" con CollectionModel
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
        echo "\nSobra CollectionResourcesModel Id:".$dataObj->getter('id')." - non ten Resource";

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
        echo "\nSobra ResourceCollectionsModel Id:".$dataObj->getter('id')." - non ten Resource";

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
        echo "\nSobra CollectionResourcesModel Id:".$dataObj->getter('id')." - non ten Collection";

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
        echo "\nSobra ResourceCollectionsModel Id:".$dataObj->getter('id')." - non ten Collection";

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

    ResourceTaxonomytermModel extends Model {
      TaxonomytermModel',
      ResourceModel',




   */

}
