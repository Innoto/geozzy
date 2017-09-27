<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class ContactModel extends Model {

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
      'size' => 2000,
      'multilang' => true
    ),
    'timetable' => array(
      'type' => 'VARCHAR',
      'size' => 800,
      'multilang' => true
    ),
    'url' => array(
      'type' => 'VARCHAR',
      'size' => 1000
    ),
  );


  static $extraFilters = [];


  var $deploySQL = [
    [
      'version' => 'rextContact#1.1',
      'sql'=> '
        {multilang:ALTER TABLE geozzy_resource_rext_contact MODIFY COLUMN timetable_$lang VARCHAR(800) default NULL;}
        ALTER TABLE geozzy_resource_rext_contact MODIFY COLUMN url VARCHAR(1000) default NULL;
      '
    ]
  ];

}
