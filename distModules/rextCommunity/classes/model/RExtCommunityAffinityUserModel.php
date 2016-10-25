<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtCommunityAffinityUserModel extends Model {

  static $tableName = 'geozzy_resource_rext_community_affinity_user';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'affinityList' => array(
      'type' => 'VARCHAR',
      'size' => 15000
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
