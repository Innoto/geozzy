<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceColectionModel extends Model
{
  static $tableName = 'geozzy_resource_colection';

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
    'colection' => array(
      'type'=>'FOREIGN',
      'vo' => 'ColectionModel',
      'key' => 'id'
    ),
    'weight' => array(
      'type' => 'SMALLINT'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
