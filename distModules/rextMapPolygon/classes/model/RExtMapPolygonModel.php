<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtMapPolygonModel extends Model {

  static $tableName = 'geozzy_resource_rext_mapPolygon';
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
    'polygonGeometry' => array(
      'type' => 'POLYGON'
    )
  );

  static $extraFilters = [
  ];

  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
