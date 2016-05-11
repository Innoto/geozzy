<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class TaxonomyViewModel extends Model {

  static $tableName = 'geozzy_taxonomy_view';

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
    ),
    'taxGroupIdName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'taxGroupName' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    )
  );

  static $extraFilters = array();

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.2',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_taxonomy_view;
        CREATE VIEW geozzy_taxonomy_view AS
          SELECT
            geozzy_taxonomyterm.id AS id,
            geozzy_taxonomyterm.idName AS idName,
            geozzy_taxonomyterm.name_es AS name_es,
            geozzy_taxonomyterm.name_gl AS name_gl,
            geozzy_taxonomyterm.name_en AS name_en,
            geozzy_taxonomyterm.weight AS weight,
            geozzy_taxonomyterm.icon AS icon,
            geozzy_taxonomyterm.taxgroup AS taxgroup,
            geozzy_taxonomygroup.idName AS taxGroupIdName,
            geozzy_taxonomygroup.name_es AS taxGroupName_es,
            geozzy_taxonomygroup.name_gl AS taxGroupName_gl,
            geozzy_taxonomygroup.name_en AS taxGroupName_en
          FROM `geozzy_taxonomygroup`
            JOIN `geozzy_taxonomyterm`
          WHERE geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id
          ORDER BY geozzy_taxonomygroup.id;
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
