<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ColectionResourceModel extends Model
{
  static $tableName = 'geozzy_colection_resource';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'description' => array(
      'type' => 'TEXT'
    ),    
    'weight' => array(
      'type' => 'VARCHAR',
      'size' => 45
    ),

    'colection' => array(
      'type'=>'FOREIGN',
      'vo' => 'ColectionModel',
      'key' => 'id'
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    )
  );

  static $extraFilters = array();


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
