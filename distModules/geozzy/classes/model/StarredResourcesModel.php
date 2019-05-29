<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class StarredResourcesModel extends Model
{
  var $notCreateDBTable = true;



  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
          DROP VIEW IF EXISTS geozzy_starred_resources;
          CREATE VIEW geozzy_starred_resources AS
            SELECT
              geozzy_resource.id as id,
              geozzy_resource.rTypeId as rTypeId,
              geozzy_resource_taxonomyterm.taxonomyterm as taxonomyterm,
              geozzy_resource_taxonomyterm.weight as weight
            from geozzy_resource , geozzy_resource_taxonomyterm
            where geozzy_resource_taxonomyterm.resource = geozzy_resource.id AND geozzy_resource.published = true;
      '
    )
  );

  static $tableName = 'geozzy_starred_resources';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'rTypeId' => array(
      'type' => 'INT'
    ),
    'taxonomyterm' => array(
      'type' => 'INT'
    ),
    'weight' => array(
      'type' => 'INT'
    )
  );

  static $extraFilters = array(
    'ids' => ' geozzy_starred_resources.id IN (?)'

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
