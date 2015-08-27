<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceTaxonomytermModel extends Model {

  static $tableName = 'geozzy_resource_taxonomyterm';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'taxonomyterm' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomytermModel',
      'key' => 'id'
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'weight' => array(
      'type' => 'SMALLINT'
    )
  );

  static $extraFilters = array(
    'idInCSV' => ' id IN ( ? ) ',
    'idTermInCSV' => ' idName IN ( ? ) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
