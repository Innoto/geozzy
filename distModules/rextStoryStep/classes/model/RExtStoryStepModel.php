<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RExtStoryStepModel extends Model
{
  static $tableName = 'geozzy_resource_rext_storystep';
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
    'storystepResource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'drawLine' => array(
      'type'=>'BOOLEAN'
    ),
    'dialogPosition' => array(
      'type' => 'INT'
    ),
    'viewMoreButton' => array(
      'type'=>'BOOLEAN'
    ),
    'showTimeline' => array(
      'type'=>'BOOLEAN'
    ),
    'mapType' => array(
      'type'=>'VARCHAR',
      'size'=> 100
    ),
    'storystepLegend' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/RExtStoryStepFiles/'
    ),
    'storystepKML' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/RExtStoryStepFiles/'
    ),
    'urlVideo' => array(
      'type'=> 'VARCHAR',
      'size' => 2000
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

  var $deploySQL = array(
    array(
      'version' => 'rextStoryStep#2',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_storystep
        ADD COLUMN urlVideo VARCHAR(2000);'
    ),
    array(
      'version' => 'rextStoryStep#1.1',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_storystep
        ADD COLUMN drawLine BIT,
        ADD COLUMN mapType VARCHAR(100) ;'
    ),
    array(
      'version' => 'rextStoryStep#1.2',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_storystep
        ADD COLUMN storystepKML INT ;'
    ),
    array(
      'version' => 'rextStoryStep#1.4',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_storystep
        ADD COLUMN showTimeline BIT ;'
    ),
    array(
      'version' => 'rextStoryStep#1.5',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_storystep
        ADD INDEX storystepKML (storystepKML);'
    ),
    array(
      'version' => 'rextStoryStep#1.6',
      'sql'=> 'ALTER TABLE geozzy_resource_rext_storystep
        ADD COLUMN dialogPosition INT,
        ADD COLUMN viewMoreButton BIT;'
    )
  );

}
