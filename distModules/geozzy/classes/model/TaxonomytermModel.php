<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class TaxonomytermModel extends Model {

  static $tableName = 'geozzy_taxonomyterm';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'name' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'mediumDescription' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'parent' => array(
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
      'key' => 'id',
      'uploadDir' => '/Taxonomyterm/'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = array(
    'idInCSV' => ' geozzy_taxonomyterm.id IN ( ? ) ',
    'idNameInCSV' => ' geozzy_taxonomyterm.idName IN ( ? ) ',
    'idNames' => ' geozzy_taxonomyterm.idName IN ( ? ) ',
    'taxgroupIn' => ' geozzy_taxonomyterm.taxgroup IN ( ? ) '
  );


  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.8',
      'sql'=> '
        {multilang:ALTER TABLE geozzy_taxonomyterm ADD COLUMN mediumDescription_$lang TEXT NULL;}
      '
    ),
    // multiple unique constrait
    array(
      'version' => 'geozzy#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        ALTER TABLE geozzy_taxonomyterm ADD CONSTRAINT taxgroup_idName UNIQUE (taxgroup, idName);
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
