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
    'routeFile' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
