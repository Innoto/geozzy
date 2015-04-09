<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ResourcetypeTopicModel extends Model
{
  static $tableName = 'geozzy_resourcetype_topic';
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
    )

  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
