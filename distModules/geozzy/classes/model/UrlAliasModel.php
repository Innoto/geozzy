<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class UrlAliasModel extends Model {

  static $tableName = 'geozzy_url_alias';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'http' => array(
      'type' => 'CHAR',
      'size' => 4
    ),
    'canonical' => array(
      'type' => 'BOOLEAN'
    ),
    'lang' => array(
      'type' => 'CHAR',
      'size' => 4
    ),
    'urlFrom' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'urlTo' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'weight' => array(
      'type' => 'SMALLINT'
    )
  );

  static $extraFilters = array();


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
