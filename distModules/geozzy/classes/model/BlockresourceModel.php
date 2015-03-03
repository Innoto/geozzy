<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class BlockresourceModel extends Model
{
  static $tableName = 'geozzy_block_resource';
  static $cols = array(
     'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    )
  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
