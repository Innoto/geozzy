<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ColectionModel extends Model
{
  static $tableName = 'geozzy_colection';
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
    'idicon' => array(
      'type' => 'VARCHAR',
      'size' => 45
    )
  );

  static $extraFilters = array();


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
