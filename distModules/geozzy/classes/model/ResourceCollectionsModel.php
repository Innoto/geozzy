<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceCollectionsModel extends Model {

  static $tableName = 'geozzy_resource_collections';

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
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = [
    'resourceNotIn' => ' geozzy_resource_collections.resource NOT IN (?) ',
    'collectionNotIn' => ' geozzy_resource_collections.collection NOT IN (?) ',
  ];


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
