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
    'mediumDescription' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'parent' => array(
      'type' => 'INT',
    ),
    'icon' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id'
    ),
    'iconName' => [
      'type' => 'VARCHAR',
      'size' => 250
    ],
    'iconAKey' => [
      'type' => 'VARCHAR',
      'size' => 16
    ],
    'taxgroup' => array(
      'type'=>'FOREIGN',
      'vo' => 'TaxonomygroupModel',
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
    ),
    'weight' => array(
      'type' => 'INT',
    ),
  );

  static $extraFilters = array();

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.93',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_taxonomy_view;
        CREATE VIEW geozzy_taxonomy_view AS
          SELECT
            geozzy_taxonomyterm.id AS id,
            geozzy_taxonomyterm.idName AS idName,

            {multilang:geozzy_taxonomyterm.name_$lang AS name_$lang,}
            {multilang:geozzy_taxonomyterm.mediumDescription_$lang AS mediumDescription_$lang,}

            geozzy_taxonomyterm.parent AS parent,
            geozzy_taxonomyterm.icon AS icon, fd.name AS iconName, fd.AKey AS iconAKey,

            geozzy_taxonomyterm.taxgroup AS taxgroup,
            geozzy_taxonomygroup.idName AS taxGroupIdName,
            {multilang:geozzy_taxonomygroup.name_$lang AS taxGroupName_$lang,}

            geozzy_taxonomyterm.weight AS weight
          FROM
            ((
            `geozzy_taxonomygroup`
            join `geozzy_taxonomyterm`)
            LEFT JOIN filedata_filedata AS fd ON geozzy_taxonomyterm.icon = fd.id)
          WHERE
            geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id
          ORDER BY
            geozzy_taxonomygroup.id;
      '
    ),
    array(
      'version' => 'geozzy#1.2',
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
