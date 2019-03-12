<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class CollectionModel extends Model {

  static $tableName = 'geozzy_collection';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 240
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 240,
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'size' => 240,
      'multilang' => true
    ),
    'description' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'image' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/Collection/'
    ),
    'share' => array(
      'type' => 'TINYINT'
    ),
    'collectionType' => array(
      'type' => 'VARCHAR',
      'size' => 100
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

  static $extraFilters = array(
    'createdfrom' => ' ( geozzy_collection.timeCreation >= ? ) ',
    'lastUpdatefrom' => ' ( geozzy_collection.timeLastUpdate >= ? ) ',
    'idIn' => ' ( geozzy_collection.id IN (?) ) ',
    'idNotIn' => ' geozzy_collection.id NOT IN (?) ',
  );


  var $deploySQL = array(
    array(
      'version' => 'geozzy#4',
      'sql'=> 'ALTER TABLE `geozzy_collection`
        ADD `timeLastUpdate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL AFTER `timeCreation`;'
    ),
    array(
      'version' => 'geozzy#1.99',
      'sql'=> 'ALTER TABLE `geozzy_collection`
        CHANGE COLUMN `collectionType` `collectionType` VARCHAR(100) NULL DEFAULT NULL ;'
    ),
    array(
      'version' => 'geozzy#1.1',
      'sql'=> 'ALTER TABLE `geozzy_collection`
        ADD COLUMN `timeCreation` DATETIME NULL DEFAULT NULL AFTER `collectionType` ;'
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
