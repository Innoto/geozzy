<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtAppGeozzySampleModel extends Model {

  static $tableName = 'geozzy_resource_rext_AppGeozzySample';
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
    'contentGzzSample' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'textGzzSample' => array(
      'type' => 'VARCHAR',
      'size' => 100
    )
  );

  var $deploySQL = array();

  static $extraFilters = array();

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
