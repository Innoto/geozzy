<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class GenericExplorerModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'explorer#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_generic_explorer_index;
        CREATE VIEW geozzy_generic_explorer_index AS
          SELECT
            geozzy_resource.id as id,
            {multilang:geozzy_resource.title_$lang as title_$lang,}
            geozzy_resource.image as image,
            {multilang: geozzy_resource.shortDescription_$lang as shortDescription_$lang, }
            geozzy_resource.loc as loc,
            geozzy_resource.timeLastUpdate as timeLastUpdate,
            group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
          FROM geozzy_resource
          LEFT JOIN geozzy_resource_taxonomyterm
          ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource

          WHERE geozzy_resource.published = 1
          group by geozzy_resource.id;
      '
    )
  );



  static $tableName = 'geozzy_generic_explorer_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'image' => array(
      'type' => 'INT'
    ),
    'terms' => array(
      'type'=>'VARCHAR'
    ),
    'loc' => array(
      'type'=>'GEOMETRY'
    ),
    'timeLastUpdate' => array(
      'type'=>'GEOMETRY'
    )
  );

  static $extraFilters = array(
    'ids' => ' geozzy_generic_explorer_index.id IN (?)',
    'updatedfrom' => ' geozzy_generic_explorer_index.timeLastUpdate > FROM_UNIXTIME(?) '

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
