<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtCommunityModel extends Model {

  static $tableName = 'geozzy_resource_rext_community';

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
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'share' => array(
      'type' => 'BOOLEAN'
    ),
    'facebook' => array(
      'type' => 'VARCHAR',
      'size' => 500
    ),
    'twitter' => array(
      'type' => 'VARCHAR',
      'size' => 500
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
