<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class CollectionResourcesModel extends Model {

  static $tableName = 'geozzy_collection_resources';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'collection' => array(
      'type'=>'FOREIGN',
      'vo' => 'CollectionModel',
      'key' => 'id'
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'note' => array(
      'type' => 'VARCHAR',
      'size' => 500
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'TIMESTAMP',
      'customDefault' => 'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = [
    'resourceNotIn' => ' geozzy_collection_resources.resource NOT IN (?) ',
    'collectionNotIn' => ' geozzy_collection_resources.collection NOT IN (?) ',
  ];


  var $deploySQL = array(
    array(
      'version' => 'geozzy#4',
      'sql'=> 'ALTER TABLE `geozzy_collection_resources`
        ADD `timeLastUpdate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL AFTER `timeCreation`;'
    ),
    array(
      'version' => 'geozzy#1.1',
      'sql'=> 'ALTER TABLE `geozzy_collection_resources`
        ADD COLUMN `timeCreation` DATETIME NULL DEFAULT NULL AFTER `resource`,
        ADD COLUMN `note` VARCHAR(500) NULL DEFAULT NULL AFTER `resource` ;'
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
