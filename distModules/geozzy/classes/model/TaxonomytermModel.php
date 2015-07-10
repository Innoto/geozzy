<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class TaxonomytermModel extends Model
{
  static $tableName = 'geozzy_taxonomyterm';
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
      'multilang' => true
    ),
    'parent' => array(
      'type' => 'INT',
    ),
    'weight' => array(
      'type' => 'INT',
    ),
    'taxgroup' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomygroupModel',
      'key' => 'id'
    ),
    'icon' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id'
    )
  );

  static $extraFilters = array(
    'idInCSV' => ' id IN ( ? ) ',
    'idNameInCSV' => ' idName IN ( ? ) '
  );


  function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
