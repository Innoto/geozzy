<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class PoisExplorerModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextPoiCollection#1.6',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_pois_explorer_index;
        CREATE VIEW geozzy_pois_explorer_index AS
        SELECT
          geozzy_resource_collections.resource as resourceMain,
          geozzy_resource_collections.collection as collection,
          geozzy_collection_resources.resource as id,
          {multilang:geozzy_resource.title_$lang as title_$lang,}
          geozzy_resource.image as image,
          {multilang:geozzy_resource.mediumDescription_$lang as mediumDescription_$lang,}
          group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms,
          geozzy_resource.loc as loc
        FROM geozzy_resource_collections
        LEFT JOIN geozzy_collection
        ON geozzy_resource_collections.collection = geozzy_collection.id
        LEFT JOIN geozzy_collection_resources
        ON geozzy_collection.id = geozzy_collection_resources.collection
        LEFT JOIN geozzy_resource
        ON geozzy_collection_resources.resource = geozzy_resource.id
        LEFT JOIN geozzy_resource_taxonomyterm
        ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource
        WHERE
          geozzy_collection.collectionType = "poi"
        group by geozzy_collection_resources.resource;
      '
    )
  );



  static $tableName = 'geozzy_pois_explorer_index';
  static $cols = array(
    'resourceMain' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'collection' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceCollectionsModel',
      'key' => 'id'
    ),
    'id' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'mediumDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'terms' => array(
      'type'=>'VARCHAR'
    ),
    'image' => array(
      'type' => 'INT'
    ),
    'loc' => array(
      'type'=>'GEOMETRY'
    )
  );

  static $extraFilters = array(
    'ids' => ' id IN (?)'

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
