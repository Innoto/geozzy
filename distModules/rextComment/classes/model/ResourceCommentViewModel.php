<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class ResourceCommentViewModel extends Model
{
  static $tableName = 'geozzy_resource_rext_comment_view';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'rTypeId' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourcetypeModel',
      'key'=> 'id'
    ),
    'rTypeIdName' => array(
      'type' => 'VARCHAR',
      'size' => 45,
      'unique' => true
    ),
    'activeComment' => array(
      'type' => 'BOOLEAN',
      'default' => 0
    )
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_resource_rext_comment_view.id IN (?) '
  );

  var $notCreateDBTable = true;
  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextComment#1.2',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_rext_comment_view;

        CREATE VIEW geozzy_resource_rext_comment_view AS

          SELECT RES.id as id, RES.published, RES.user, RES.rTypeId,
            RT.idName as rTypeIdName,
            RCM.activeComment
          FROM geozzy_resource as RES left OUTER JOIN geozzy_resource_rext_comment as RCM ON RES.id = RCM.resource,
            geozzy_resourcetype as RT
          WHERE RES.rTypeId = RT.id
        ;
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
