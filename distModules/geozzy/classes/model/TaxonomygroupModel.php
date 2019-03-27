<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class TaxonomygroupModel extends Model {

  static $tableName = 'geozzy_taxonomygroup';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'unique' => true
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => 'true'
    ),
    'editable' => array(
      'type' => 'BOOLEAN'
    ),
    'nestable' => array(
      'type' => 'INT',
      'size' => 1
    ),
    'sortable' => array(
      'type' => 'BOOLEAN'
    ),
    'icon' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/TaxonomyGroup/'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = array(
    'idInCSV' => ' geozzy_taxonomygroup.id IN ( select geozzy_taxonomyterm.taxgroup from geozzy_taxonomyterm where geozzy_taxonomyterm.id IN ( ? ) ) ',
    'idNameInCSV' => ' geozzy_taxonomygroup.idName IN ( ? ) ',
    'idNames' => ' geozzy_taxonomygroup.idName IN ( ? ) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
