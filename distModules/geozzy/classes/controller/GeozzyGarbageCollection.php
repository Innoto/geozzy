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

    $this->resourcesId = $this->garbageCollCtrl->listModelIds('resourceModel');
    // echo "\nRes. IDs: ".implode(', ', $this->resourcesId)."\n\n";

    $this->gcUrlAlias();

    // $this->gcCollections();

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
    $listModel = $objModel->listItems([
      'filters' => ['resourceNotIn' => $this->resourcesId],
      'fields' => ['id', 'resource']
    ]);
    if( is_object( $listModel ) ) {
      while( $dataObj = $listModel->fetch() ) {
        $resId = $dataObj->getter('resource');
        if( !empty( $resId ) ) {
          echo "\nSobra a URL Id:".$dataObj->getter('id')." porque non hai o recurso $resId";

          // $dataObj->delete();

        }
      }
    }
  }



  // Eliminando URLs con recursos desaparecidos
  public function gcCollections() {
    Cogumelo::debug( __METHOD__ );
    error_log( __METHOD__ );

    // $modelName = 'UrlAliasModel';

    // $idFilesUsed = $this->garbageCollCtrl->listModelUsedIds( $modelName );

    // var_dump( $idFilesUsed );
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
