<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class AccommodationModel extends Model
{
  static $tableName = 'geozzy_resource_rext_accommodation';
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
    'reservationURL' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'reservationPhone' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'singleRooms' => array(
      'type' => 'INT'
    ),
    'doubleRooms' => array(
      'type' => 'INT'
    ),
    'familyRooms' => array(
      'type' => 'INT'
    ),
    'beds' => array(
      'type' => 'INT'
    ),
    'averagePrice' => array(
      'type' => 'FLOAT'
    ),
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
