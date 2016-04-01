<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class EventModel extends Model
{
  static $tableName = 'geozzy_resource_rext_event';
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
    'rextEventInitDate' => array(
      'type' => 'VARCHAR',
      'size' => '200'
    ),
    'rextEventEndDate' => array(
      'type' => 'VARCHAR',
      'size' => '200'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
