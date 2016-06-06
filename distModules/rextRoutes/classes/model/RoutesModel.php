<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );



class RoutesModel extends Model
{
  static $tableName = 'geozzy_resource_rext_routes';
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
    'durationMinutes' => array(
      'type' => 'INT'
    ),
    'slopeUp' => array(
      'type' => 'INT'
    ),
    'slopeDown' => array(
      'type' => 'INT'
    ),
    'travelDistance' => array(
      'type' => 'INT'
    ),
    'difficultyEnvironment' => array(
      'type' => 'INT'
    ),
    'difficultyItinerary' => array(
      'type' => 'INT'
    ),
    'difficultyDisplacement' => array(
      'type' => 'INT'
    ),
    'difficultyEffort' => array(
      'type' => 'INT'
    ),
    'difficultyGlobal' => array(
      'type' => 'INT'
    ),
    'routeFile' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir'=> '/RExtRouteFiles/'
    ),
    'routeStart' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'routeEnd' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'circular' => array(
      'type' => 'BOOLEAN'
    )
  );

  var $deploySQL = array(
    array(
      'version' => 'rextRoutes#1.1',
      'sql'=> '
        ALTER TABLE `geozzy_resource_rext_routes`
        ADD (`difficultyGlobal` INT DEFAULT NULL,
            `routeStart_es` VARCHAR(100),
            `routeStart_en` VARCHAR(100),
            `routeStart_gl` VARCHAR(100),
            `routeEnd_es` VARCHAR(100),
            `routeEnd_en` VARCHAR(100),
            `routeEnd_gl` VARCHAR(100),
            `circular` BOOLEAN);'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
