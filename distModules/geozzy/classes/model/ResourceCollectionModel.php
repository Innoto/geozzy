<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceCollectionModel extends Model
{
  static $tableName = 'geozzy_resource_collection';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'collection' => array(
      'type'=>'FOREIGN',
      'vo' => 'CollectionModel',
      'key' => 'id'
    ),
    'weight' => array(
      'type' => 'SMALLINT'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
