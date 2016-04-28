<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class CommentModel extends Model {

  static $tableName = 'geozzy_comment';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'type' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomytermModel',
      'key' => 'id'
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
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'content' => array(
      'type' => 'TEXT',
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'rate' => array(
      'type' => 'SMALLINT'
    ),
    'suggestType' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomytermModel',
      'key' => 'id'
    ),
    'status' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomytermModel',
      'key' => 'id'
    ),
    'anonymousName' => array(
      'type' => 'CHAR',
      'size' => '50'
    ),
    'anonymousEmail' => array(
      'type' => 'CHAR',
      'size' => '50',
    )
  );

  static $extraFilters = array( );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
