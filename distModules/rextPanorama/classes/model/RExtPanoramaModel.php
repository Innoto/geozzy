<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtPanoramaModel extends Model {

  static $tableName = 'geozzy_resource_rext_panorama';

  static $cols = [
    'id' => [
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ],
    'resource' => [
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ],
    'panoramicImage' => [
      'type'=> 'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/Panorama/'
    ],
    'horizontalAngleView' => [
      'type' => 'INT'
    ],
    'verticalAngleView' => [
      'type' => 'INT'
    ]
  ];

  static $extraFilters = [];

  public function __construct( $datarray = [], $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
