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
      'type' => 'VARCHAR',
      'size' => 500,
      'multilang' => true
    ),
    'activeGplus' => array(
      'type' => 'INT'
    ),
    'textGplus' => array(
      'type' => 'VARCHAR',
      'size' => 500,
      'multilang' => true
    ),
    'activeTwitter' => array(
      'type' => 'INT'
    ),
    'textTwitter' => array(
      'type' => 'VARCHAR',
      'size' => 500,
      'multilang' => true
    )
  );


  static $extraFilters = [];


  var $deploySQL = [
    [
      'version' => 'rextSocialNetwork#1.1',
      'sql'=> '
        {multilang:ALTER TABLE geozzy_resource_rext_socialnetwork ADD COLUMN textGplus_$lang VARCHAR(2000) default NULL;}
        ALTER TABLE geozzy_resource_rext_socialnetwork ADD COLUMN activeGplus INT default 0;
      '
    ],
    [
      'version' => 'rextSocialNetwork#1.2',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_socialnetwork MODIFY COLUMN activeGplus INT default NULL;'
    ],
    [
      'version' => 'rextSocialNetwork#1.3',
      'sql'=> '
        {multilang:ALTER TABLE geozzy_resource_rext_socialnetwork MODIFY COLUMN textFb_$lang VARCHAR(500) default NULL;}
        {multilang:ALTER TABLE geozzy_resource_rext_socialnetwork MODIFY COLUMN textGplus_$lang VARCHAR(500) default NULL;}
        {multilang:ALTER TABLE geozzy_resource_rext_socialnetwork MODIFY COLUMN textTwitter_$lang VARCHAR(500) default NULL;}
      '
    ],
  ];


  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
