<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class TopicModel extends Model
{
  static $tableName = 'geozzy_topic';
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
      'multilang' => true,
      'size' => 45
    ),
    'weight' => array(
      'type' => 'INT'
    ),
    'taxgroup' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomygroupModel',
      'key' => 'id'
    )

  );

  static $extraFilters = array();


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
