<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtCommunityAffinityModel extends Model {

  static $tableName = 'geozzy_resource_rext_community_affinity';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'affinityList' => array(
      'type' => 'STRING'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
