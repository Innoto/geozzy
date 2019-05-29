<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceTopicModel extends Model {

  static $tableName = 'geozzy_resource_topic';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'taxonomyterm' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomytermModel',
      'key' => 'id'
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'topic' => array(
      'type'=>'FOREIGN',
      'vo' => 'TopicModel',
      'key' => 'id'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    ),
    'timeLastUpdate' => array(
      'type' => 'TIMESTAMP',
      'customDefault' => 'DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL'
    )
  );

  static $extraFilters = array(
    'taxonomytermnIn' => ' geozzy_resource_topic.taxonomyterm IN ( ? ) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }


  var $deploySQL = array(
    array(
      'version' => 'geozzy#4',
      'sql'=> 'ALTER TABLE `geozzy_resource_topic`
        ADD `timeLastUpdate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL;'
    )

  );
}
