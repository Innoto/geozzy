<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtAccommodationReserveModel extends Model {

  static $tableName = 'geozzy_resource_rext_accommodation_reserve';
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
    'channel' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'idRelate' => array(
      'type' => 'VARCHAR',
      'size' => 100
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
