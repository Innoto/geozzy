<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class TaxonomygroupModel extends Model
{
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
      'key' => 'id'
    )
  );

  static $extraFilters = array(
    'idInCSV' => ' id IN (select taxgroup from geozzy_taxonomyterm where id IN (?))',
    'idNameInCSV' => ' idName IN ( ? ) '
  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
