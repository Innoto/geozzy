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
    'relatedResource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),

    'initDateFirst' => array(
      'type' => 'DATETIME'
    ),
    'initDateSecond' => array(
      'type' => 'DATETIME'
    ),
    'selectInitTime' => array(
      'type' => 'VARCHAR',
      'size' => 50
    ),

    'dateRange' => array(
      'type' => 'BOOLEAN'
    ),

    'endDateFirst' => array(
      'type' => 'DATETIME'
    ),
    'endDateSecond' => array(
      'type' => 'DATETIME'
    ),
    'selectEndTime' => array(
      'type' => 'VARCHAR',
      'size' => 50
    ),

    'eventTitle' => array(
      'multilang' => true,
      'type' => 'VARCHAR',
      'size' => 100
    )
  );

  var $deploySQL = array(
    array(
      'version' => 'rextEvent#3',
      'sql' => '
        ALTER TABLE geozzy_resource_rext_event
          CHANGE COLUMN initDate initDateFirst DATETIME NULL DEFAULT NULL,
          ADD COLUMN initDateSecond DATETIME NULL AFTER initDateFirst,
          ADD COLUMN selectInitTime VARCHAR(50) NULL AFTER initDateSecond,

          ADD COLUMN dateRange INT NULL AFTER selectInitTime,

          CHANGE COLUMN endDate endDateFirst DATETIME NULL DEFAULT NULL,
          ADD COLUMN endDateSecond DATETIME NULL AFTER endDateFirst,
          ADD COLUMN selectEndTime VARCHAR(50) NULL AFTER endDateSecond;
      '
    ),
    array(
      'version' => 'rextEvent#2',
      'sql' => '
        {multilang:ALTER TABLE geozzy_resource_rext_event ADD COLUMN eventTitle_$lang VARCHAR(100) NULL;}
      '
    )
  );

  static $extraFilters = array(
    'inId' => ' geozzy_resource_rext_event.resource IN (?) ',
    'resourceIn' => ' geozzy_resource_rext_event.resource IN (?) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
