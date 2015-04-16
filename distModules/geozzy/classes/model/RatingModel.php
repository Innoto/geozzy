<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class RatingModel extends Model
{
  static $tableName = 'geozzy_rating';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key'=> 'id'
    ),
    'points' => array(
      'type' => 'INT'
    ),
    'timeVote' => array(
      'type' => 'TIMESTAMP'
    ),
    'ipVote' => array(
      'type' => 'VARCHAR',
      'size' => 15  
    ),
  );

  static $extraFilters = array();


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
