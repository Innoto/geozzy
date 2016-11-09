<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class ProfilesSNModel extends Model
{
  static $tableName = 'geozzy_resource_rext_profiles_socialnetworks';
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
    'googleplus' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'facebook' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'twitter' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'linkedin' => array(
      'type' => 'VARCHAR',
      'size' => 100
    )
  );

  static $extraFilters = array(
    'resourceIn' => ' geozzy_resource_rext_profiles_socialnetworks.resource IN (?) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
