<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceTaxonomyAllModel extends Model {

  static $tableName = 'geozzy_resource_taxonomyall';

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
    'parent' => array(
      'type' => 'INT',
    ),
    'weight' => array(
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
    'idNameTaxgroup' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'idResTaxTerm' => array(
      'type' => 'INT'
    ),
    'weightResTaxTerm' => array(
      'type' => 'INT'
    )
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_resource_taxonomyall.id IN (?) ',
    'resourceIn' => ' geozzy_resource_taxonomyall.resource IN (?) ',
    'resourceNotIn' => ' geozzy_resource_taxonomyall.resource NOT IN (?) ',
    'idNameTaxgroupIn' => ' geozzy_resource_taxonomyall.idNameTaxgroup IN (?) ',
    'idNameIn' => ' geozzy_resource_taxonomyall.idName IN (?) ',
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.93',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_taxonomyall;
        CREATE VIEW geozzy_resource_taxonomyall AS
          SELECT
            geozzy_taxonomyterm.id AS id,
            geozzy_taxonomyterm.idName AS idName,

            {multilang:geozzy_taxonomyterm.name_$lang AS name_$lang,}

            geozzy_taxonomyterm.parent AS parent,
            geozzy_taxonomyterm.weight AS weight,

            geozzy_taxonomyterm.icon AS icon, fd.name AS iconName, fd.AKey AS iconAKey,

            geozzy_taxonomyterm.taxgroup AS taxgroup,
            geozzy_taxonomygroup.idName AS idNameTaxgroup,
            geozzy_resource_taxonomyterm.resource AS resource,
            geozzy_resource_taxonomyterm.id AS idResTaxTerm,
            geozzy_resource_taxonomyterm.weight AS weightResTaxTerm
          FROM
            (((
            `geozzy_resource_taxonomyterm`
            join `geozzy_taxonomygroup`)
            join `geozzy_taxonomyterm`)
            LEFT JOIN filedata_filedata AS fd ON geozzy_taxonomyterm.icon = fd.id)
          WHERE geozzy_resource_taxonomyterm.taxonomyterm = geozzy_taxonomyterm.id
            AND geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id
          ORDER BY geozzy_resource_taxonomyterm.resource;
      '
    ),
    array(
      'version' => 'geozzy#1.0',
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_taxonomyall;
        CREATE VIEW geozzy_resource_taxonomyall AS
          SELECT
            geozzy_taxonomyterm.id AS id,
            geozzy_taxonomyterm.idName AS idName,

            {multilang:geozzy_taxonomyterm.name_$lang AS name_$lang,}

            geozzy_taxonomyterm.parent AS parent,
            geozzy_taxonomyterm.weight AS weight,
            geozzy_taxonomyterm.icon AS icon,
            geozzy_taxonomyterm.taxgroup AS taxgroup,
            geozzy_taxonomygroup.idName AS idNameTaxgroup,
            geozzy_resource_taxonomyterm.resource AS resource,
            geozzy_resource_taxonomyterm.id AS idResTaxTerm,
            geozzy_resource_taxonomyterm.weight AS weightResTaxTerm
          FROM `geozzy_resource_taxonomyterm`
            JOIN `geozzy_taxonomygroup`
            JOIN `geozzy_taxonomyterm`
          WHERE geozzy_resource_taxonomyterm.taxonomyterm = geozzy_taxonomyterm.id
            AND geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id
          ORDER BY geozzy_resource_taxonomyterm.resource;
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
