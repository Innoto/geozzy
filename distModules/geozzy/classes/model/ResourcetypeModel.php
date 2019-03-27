<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourcetypeModel extends Model {

  static $tableName = 'geozzy_resourcetype';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 45,
      'unique' => true
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'size' => 45,
      'multilang' => true
    ),
    'relatedModels' => array(
      'type' => 'VARCHAR',
      'size' => 400
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = array(
    'intopic' => ' geozzy_resourcetype.id IN ( select geozzy_resourcetype_topic.resourceType from geozzy_resourcetype_topic where topic=? ) ',
    'idNameExists' => ' geozzy_resourcetype.idName IN (?) ',
    'inIdName' => ' geozzy_resourcetype.idName IN (?) ',
    'notIdName' => ' geozzy_resourcetype.idName NOT IN (?) ',
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
