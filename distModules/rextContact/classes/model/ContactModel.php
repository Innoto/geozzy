<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class ContactModel extends Model
{
  static $tableName = 'geozzy_resource_rext_contact';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'address' => array(
      'type' => 'VARCHAR',
      'size' => 200
    ),
    'city' => array(
      'type' => 'VARCHAR',
      'size' => 60
    ),
    'province' => array(
      'type' => 'VARCHAR',
      'size' => 60
    ),
    'cp' => array(
      'type' => 'VARCHAR',
      'size' => 8
    ),
    'phone' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'email' => array(
      'type' => 'VARCHAR',
      'size' => 255
    ),
    'directions' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'timetable' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}