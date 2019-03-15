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
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_resource_taxonomyterm.id IN ( ? ) ',
    'taxonomytermIn' => ' geozzy_resource_taxonomyterm.taxonomyterm IN ( ? ) ',
    'taxonomytermNotIn' => ' geozzy_resource_taxonomyterm.taxonomyterm NOT IN ( ? ) ',
    'resourceNotIn' => ' geozzy_resource_taxonomyterm.resource NOT IN ( ? ) ',
    'idInCSV' => ' geozzy_resource_taxonomyterm.id IN ( ? ) ',
    'idTermInCSV' => ' geozzy_resource_taxonomyterm.idName IN ( ? ) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
