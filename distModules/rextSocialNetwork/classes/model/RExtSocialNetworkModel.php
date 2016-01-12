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
      'size' => 2000,
      'multilang' => true
    ),
    'activeTwitter' => array(
      'type' => 'INT'
    ),
    'textTwitter' => array(
      'type' => 'VARCHAR',
      'size' => 2000,
      'multilang' => true
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
