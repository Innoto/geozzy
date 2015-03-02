<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ResourceModel extends Model
{
  static $tableName = 'geozzy_resource';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'type' => array(
      'type' => 'VARCHAR',
      'size' => '100'
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'userUpdate' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'published' => array(
      'type' => 'BOOLEAN'
    ),
    'timeCreation' => array(
      'type' => 'TIMESTAMP'
    ),
    'timeLastUpdate' => array(
      'type' => 'TIMESTAMP'
    ),
    'timeLastPublish' => array(
      'type' => 'TIMESTAMP'
    ),

    'headKeywords' => array(
      'type' => 'VARCHAR',
      'size' => 150
    ),
    'headDescription' => array(
      'type' => 'VARCHAR',
      'size' => 150
    ),
    'titleHead' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'translate' => true      
    ),    
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'translate' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR'
      'size' => 100,
      'translate' => true
    ),
    'mediumDescription' => array(
      'type' => 'TEXT',
      'translate' => true
    ),
    'content' => array(
      'type' => 'TEXT',
      'translate' => true
    ),
    'loc' => array(
      'type' => 'GEOMETRY'
    ),
    'defaultZoom' => array(
      'type' => 'INT'
    ),
    'asociatedBlock' => array(
      'type' => 'INT'
    ),
    'image' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id'
    )
    'galery' => array(
      'type'=>'FOREIGN',
      'vo' => 'FilegroupModel',
      'key' => 'groupId'
    )
  );

  var $filters = array( );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
