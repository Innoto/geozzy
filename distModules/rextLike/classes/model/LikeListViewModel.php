<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class LikeListViewModel extends Model {

  static $tableName = 'geozzy_like_list_view';

  /**
   * Listado de recursos likes con su coleccion 'like' y recurso RTypeLike
   * Se incluyen recursos RTypeLike con colecciones 'like' sin contenido
   */
  static $cols = array(
    'id' => array(
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id',
      'primarykey' => true
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'colId' => array(
      'type' => 'FOREIGN',
      'vo' => 'CollectionModel',
      'key' => 'id'
    ),
    'resourceList' => array(
      'type' => 'VARCHAR',
    ),
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_like_list_view.id IN (?) ',
    'userIn' => ' geozzy_like_list_view.user IN (?) ',
    'resourceListNotNull' => ' resourceList IS NOT NULL ',
    'inResourceList' => ' ( resourceList like concat(?,",%") OR resourceList LIKE CONCAT("%,",?,",%") OR resourceList LIKE CONCAT("%,",?)  OR resourceList = ? ) '
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextLike#1.1',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_like_list_view;

        CREATE VIEW geozzy_like_list_view AS

          SELECT RES.id as id,
            RES.timeCreation as timeCreation,
            RES.user,
            RES.published,
            COL.id as colId,
            GROUP_CONCAT( CR.resource ) as resourceList

          FROM geozzy_resource as RES, geozzy_resourcetype as RT, geozzy_resource_collections as RC,
            geozzy_collection COL left OUTER JOIN  geozzy_collection_resources as CR ON CR.collection = COL.id

          WHERE RES.rTypeId=RT.id AND RT.idName="rtypeLike" AND
            RES.id = RC.resource AND RC.collection = COL.id AND COL.collectionType = "like"

          GROUP BY RES.id
        ;
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
