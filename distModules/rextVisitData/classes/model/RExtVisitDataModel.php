<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtVisitDataModel extends Model {

  static $tableName = 'geozzy_resource_rext_visitdata';
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
    'duration' => array(
      'type' => 'INT'
    ),
    'timetable' => array(
      'type' => 'VARCHAR',
      'size' => 20
    )
  );


  static $extraFilters = [
    'idIn' => ' geozzy_resource_rext_visitdata.id IN (?) ',
    'resourceIn' => ' geozzy_resource_rext_visitdata.resource IN (?) ',
  ];


  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
