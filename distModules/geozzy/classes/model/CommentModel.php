<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class CommentModel extends Model
{
  static $tableName = 'geozzy_comment';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'status' => array(
      'type' => 'INT'
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
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'content' => array(
      'type' => 'TEXT'
    ),
    'replyTo' => array(
      'type' => 'INT'
    ),
    'timeCreation' => array(
      'type' => 'TIMESTAMP'
    ),
    'timePublish' => array(
      'type' => 'TIMESTAMP'
    ),
    'timeDeletion' => array(
      'type' => 'TIMESTAMP'
    )
  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
