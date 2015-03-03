<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class BlockModel extends Model
{
  static $tableName = 'geozzy_block';
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
      'type' => 'VARCHAR'
      'size' => 45
    )
    'idicon' => array(
      'type' => 'VARCHAR'
      'size' => 45
    )
  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
