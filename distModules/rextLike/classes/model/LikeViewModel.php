<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class LikeViewModel extends Model {

  static $tableName = 'geozzy_like_view';

  /**
   * Listado de recursos likes con su coleccion 'like' y recurso RTypeLike
   * Se incluyen recursos RTypeLike con colecciones 'like' sin contenido
   */
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'resource' => array(
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'colId' => array(
      'type' => 'FOREIGN',
      'vo' => 'CollectionModel',
      'key' => 'id'
    ),
    'colTimeCreation' => array(
      'type' => 'DATETIME'
    ),
    'resourceMain' => array(
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'published' => array(
      'type' => 'BOOLEAN'
    )
  );

  static $extraFilters = array(
    'idNotNull' => ' geozzy_like_view.id IS NOT NULL '
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextLike#2',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_like_view;

        CREATE VIEW geozzy_like_view AS

          SELECT CR.id, CR.resource, CR.timeCreation,
            COL.id as colId, COL.timeCreation as colTimeCreation,
            RES.id as resourceMain, RES.user, RES.published

          FROM geozzy_resource as RES, geozzy_resourcetype as RT, geozzy_resource_collections as RC,
            geozzy_collection COL left OUTER JOIN  geozzy_collection_resources as CR ON CR.collection = COL.id

          WHERE RES.rTypeId=RT.id AND RT.idName="rtypeLikes" AND
            RES.id = RC.resource AND RC.collection = COL.id AND COL.collectionType = "likes"
        ;
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
