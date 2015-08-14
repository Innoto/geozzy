<?php
Cogumelo::load( 'coreModel/VO.php' );
Cogumelo::load( 'coreModel/Model.php' );


class RExtUrlModel extends Model {

  static $tableName = 'geozzy_resource_rext_url';
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
    'URL' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'embed' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'author' => array(
      'type' => 'VARCHAR',
      'size' => 500
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
