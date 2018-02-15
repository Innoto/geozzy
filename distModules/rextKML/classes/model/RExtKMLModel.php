<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtKMLModel extends Model {

  static $tableName = 'geozzy_resource_rext_kml';
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
    'file' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir'=> '/rextKML/'
    )
  );



  static $extraFilters = [
    'idIn' => ' geozzy_resource_rext_kml.id IN (?) ',
    'resourceIn' => ' geozzy_resource_rext_kml.resource IN (?) ',
  ];


  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
