<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtSocialNetworkModel extends Model {

  static $tableName = 'geozzy_resource_rext_socialnetwork';
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
    'activeFb' => array(
      'type' => 'INT'
    ),
    'textFb' => array(
      'type' => 'TEXT',
      'size' => 2000,
      'multilang' => true
    ),
    'activeGplus' => array(
      'type' => 'INT'
    ),
    'textGplus' => array(
      'type' => 'TEXT',
      'size' => 2000,
      'multilang' => true
    ),
    'activeTwitter' => array(
      'type' => 'INT'
    ),
    'textTwitter' => array(
      'type' => 'TEXT',
      'size' => 2000,
      'multilang' => true
    )
  );

  static $extraFilters = array();



  var $deploySQL = array(
    array(
      'version' => 'rextSocialNetwork#1.1',
      'sql'=> '
        {multilang:ALTER TABLE geozzy_resource_rext_socialnetwork ADD COLUMN textGplus_$lang VARCHAR(2000) default NULL;}
        ALTER TABLE geozzy_resource_rext_socialnetwork ADD COLUMN activeGplus INT default 0;
      '
    ),
    array(
      'version' => 'rextSocialNetwork#1.2',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_socialnetwork MODIFY COLUMN activeGplus INT default NULL;'
    )
  );



  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
