<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class LikeUsersListViewModel extends Model {

  static $tableName = 'geozzy_like_users_list_view';

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
    'userList' => array(
      'type' => 'VARCHAR',
    ),
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_like_users_list_view.id IN (?) ',
    'userListNotNull' => ' geozzy_like_users_list_view.userList IS NOT NULL ',
    'inUserList' => ' ( geozzy_like_users_list_view.userList LIKE CONCAT(?,",%") OR geozzy_like_users_list_view.userList LIKE CONCAT("%,",?,",%") OR geozzy_like_users_list_view.userList LIKE CONCAT("%,",?) OR geozzy_like_users_list_view.userList = ? ) '
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextLike#2',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_like_users_list_view;

        CREATE VIEW geozzy_like_users_list_view AS

          SELECT CR.resource as id,
            GROUP_CONCAT( RES.user ) as userList

          FROM geozzy_resource as RES, geozzy_resourcetype as RT, geozzy_resource_collections as RC,
            geozzy_collection COL left OUTER JOIN geozzy_collection_resources as CR ON CR.collection = COL.id

          WHERE RES.rTypeId=RT.id AND RT.idName="rtypeLikes" AND
            RES.id = RC.resource AND RC.collection = COL.id AND COL.collectionType = "likes"

          GROUP BY CR.resource
        ;
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
