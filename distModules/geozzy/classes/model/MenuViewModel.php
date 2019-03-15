<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class MenuViewModel extends Model {

  static $tableName = 'geozzy_menu_taxonomy_resource';

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
    'iconName' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'iconAKey' => array(
      'type' => 'VARCHAR',
      'size' => 16
    ),
    'urlAliasRes' => array(
      'type' => 'VARCHAR',
      'size' => 2000,
      'multilang' => true
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    )
  );

  var $notCreateDBTable = true;
/*
  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.98',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_menu_taxonomy_resource;
        CREATE VIEW geozzy_menu_taxonomy_resource AS
          SELECT
            geozzy_taxonomyterm.id AS id,
            geozzy_taxonomyterm.idName AS idName,

            {multilang:geozzy_taxonomyterm.name_$lang AS name_$lang,}

            geozzy_taxonomyterm.parent AS parent,
            geozzy_taxonomyterm.weight AS weight,

            geozzy_taxonomyterm.icon AS icon, fd.name AS iconName, fd.AKey AS iconAKey,

            {multilang:GROUP_CONCAT( if( ua.lang = "$lang", ua.urlFrom, null ) ) AS "urlAliasRes_$lang",}

            geozzy_resource_taxonomyterm.resource AS resource
          FROM
            ( geozzy_taxonomygroup, geozzy_taxonomyterm )
          LEFT JOIN filedata_filedata AS fd ON geozzy_taxonomyterm.icon = fd.id
        	LEFT JOIN geozzy_resource_taxonomyterm ON geozzy_resource_taxonomyterm.taxonomyterm = geozzy_taxonomyterm.id
          LEFT JOIN geozzy_resource AS r ON r.id = geozzy_resource_taxonomyterm.resource
        	LEFT JOIN geozzy_url_alias AS ua ON ( ua.resource = geozzy_resource_taxonomyterm.resource AND ua.http = 0 AND ua.canonical = 1 AND r.published = 1 )
          WHERE
            geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id AND geozzy_taxonomygroup.idName = "menu"
          GROUP BY
            geozzy_taxonomyterm.id
      '
    )
  );
*/


  public function customSelectListItems( $extraArrayParam ) {
    return '
        SELECT
          geozzy_taxonomyterm.id AS id,
          geozzy_taxonomyterm.idName AS idName,
          {multilang:geozzy_taxonomyterm.name_$lang AS name_$lang,}
          geozzy_taxonomyterm.parent AS parent,
          geozzy_taxonomyterm.weight AS weight,
          geozzy_taxonomyterm.icon AS icon, fd.name AS iconName, fd.AKey AS iconAKey,
          {multilang:GROUP_CONCAT( if( ua.lang = "$lang", ua.urlFrom, null ) ) AS "urlAliasRes_$lang",}
          geozzy_resource_taxonomyterm.resource AS resource
        FROM
          ( geozzy_taxonomygroup, geozzy_taxonomyterm )
        LEFT JOIN filedata_filedata AS fd ON geozzy_taxonomyterm.icon = fd.id
        LEFT JOIN geozzy_resource_taxonomyterm ON geozzy_resource_taxonomyterm.taxonomyterm = geozzy_taxonomyterm.id
        LEFT JOIN geozzy_resource AS r ON r.id = geozzy_resource_taxonomyterm.resource
        LEFT JOIN geozzy_url_alias AS ua ON ( ua.resource = geozzy_resource_taxonomyterm.resource AND ua.http = 0 AND ua.canonical = 1 AND r.published = 1 )
        WHERE
          '. $extraArrayParam['strWhere'] . '
          AND geozzy_taxonomyterm.taxgroup = geozzy_taxonomygroup.id AND geozzy_taxonomygroup.idName = "menu"
        GROUP BY
          geozzy_taxonomyterm.id
        '. $extraArrayParam['strOrderBy'] . '
        '. $extraArrayParam['strRange'] . '
    ';
  }

  static $extraFilters = array(
    'id' => ' geozzy_taxonomyterm.id = ? ',
    'idName' => ' geozzy_taxonomyterm.idName = ? ',
    'parent' => 'geozzy_taxonomyterm.parent = ?',
    'weight' => 'geozzy_taxonomyterm.weight = ?',
    'resource' => 'geozzy_resource_taxonomyterm.resource = ?'
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }



}
