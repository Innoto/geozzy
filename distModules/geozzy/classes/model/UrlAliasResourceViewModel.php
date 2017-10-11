<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class UrlAliasResourceViewModel extends Model {

  var $notCreateDBTable = true; // Es una vista

  static $tableName = 'geozzy_url_alias_resource_view';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'resource' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'resourceIdName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'lang' => array(
      'type' => 'CHAR',
      'size' => 4
    ),
    'urlFrom' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    ),
    'rTypeId' => array(
      'type' => 'INT'
    ),
    'rTypeIdName' => array(
      'type' => 'VARCHAR',
      'size' => 45
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'DATETIME'
    ),
  );

  static $extraFilters = array(
    'resourceIn' => ' geozzy_url_alias_resource_view.resource IN (?) ',
    'rTypeIdIn' => ' geozzy_url_alias_resource_view.rTypeId IN (?) ',
    'rTypeIdNotIn' => ' geozzy_url_alias_resource_view.rTypeId NOT IN (?) ',
    'rTypeIdNameIn' => ' geozzy_url_alias_resource_view.rTypeIdName IN (?) ',
    'rTypeIdNameNotIn' => ' geozzy_url_alias_resource_view.rTypeIdName NOT IN (?) ',
    'resourceIdNameIn' => ' geozzy_url_alias_resource_view.resourceIdName IN (?) ',
  );



  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.6',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_url_alias_resource_view;

        CREATE VIEW geozzy_url_alias_resource_view AS

          SELECT
            ua.id, ua.resource, r.idName AS resourceIdName, ua.lang, ua.urlFrom, ua.weight,
            r.rTypeId AS rTypeId, rt.idname AS rTypeIdName,
            r.timeCreation, r.timeLastUpdate
          FROM
            geozzy_url_alias AS ua, geozzy_resource AS r, geozzy_resourcetype AS rt
          WHERE
            ua.resource=r.id AND r.rTypeId=rt.id AND
            ua.http=0 AND ua.canonical=TRUE AND r.published=TRUE
          ;
      '
    ),
    array(
      'version' => 'geozzy#1.5',
      // 'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_url_alias_resource_view;

        CREATE VIEW geozzy_url_alias_resource_view AS

          SELECT
            ua.id, ua.resource, ua.lang, ua.urlFrom, ua.weight,
            r.rTypeId AS rTypeId, rt.idname AS rTypeIdName,
            r.timeCreation, r.timeLastUpdate
          FROM
            geozzy_url_alias AS ua, geozzy_resource AS r, geozzy_resourcetype AS rt
          WHERE
            ua.resource=r.id AND r.rTypeId=rt.id AND
            ua.http=0 AND ua.canonical=TRUE AND r.published=TRUE
          ;
      '
    ),
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
