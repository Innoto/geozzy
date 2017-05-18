<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtMatterportModel extends Model {

  static $tableName = 'geozzy_resource_rext_matterport';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resource' => array(
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'idModel' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'autostart' => array(
      'type' => 'INT'
    ),
    'looped' => array(
      'type' => 'BOOLEAN'
    ),
    'autoload' => array(
      'type' => 'BOOLEAN'
    ),
    'enableScrollWheel' => array(
      'type' => 'BOOLEAN'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
