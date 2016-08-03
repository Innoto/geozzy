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

            {multilang:geozzy_taxonomyterm.name_$lang AS name_$lang,}

            {multilang:geozzy_taxonomygroup.name_$lang AS taxGroupName_$lang,}

            geozzy_taxonomyterm.weight AS weight,
            geozzy_taxonomyterm.icon AS icon,
            geozzy_taxonomyterm.taxgroup AS taxgroup,
            geozzy_taxonomygroup.idName AS taxGroupIdName

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
