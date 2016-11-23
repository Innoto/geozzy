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
    ),
    'email' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'personalUrl' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    )
  );

  var $deploySQL = array(
    array(
      'version' => 'rextProfilesSocialNetworks#1.1',
      'sql'=> '
        ALTER TABLE geozzy_resource_rext_profiles_socialnetworks ADD COLUMN email VARCHAR(100) NULL,ADD COLUMN personalUrl VARCHAR(2000) NULL;
      '
    )
  );

  static $extraFilters = array(
    'resourceIn' => ' geozzy_resource_rext_profiles_socialnetworks.resource IN (?) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
