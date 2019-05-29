<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class PoisExplorerModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rextPoiCollection#6',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_pois_explorer_index;
        CREATE VIEW geozzy_pois_explorer_index AS
        SELECT
          geozzy_resource_collections.resource as resourceMain,
          geozzy_resource_collections.collection as collection,
          geozzy_collection_resources.resource as id,
          geozzy_resourcetype.idName as rType,
          {multilang:geozzy_resource.title_$lang as title_$lang,}
          geozzy_resource.image as image,
          {multilang:geozzy_resource.shortDescription_$lang as shortDescription_$lang,}
          {multilang:geozzy_resource.mediumDescription_$lang as mediumDescription_$lang,}
          {multilang:geozzy_resource.content_$lang as content_$lang,}
          {multilang:GROUP_CONCAT( DISTINCT if(lang="$lang",geozzy_url_alias.urlFrom,null)) AS urlAlias_$lang,}
          group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms,
          geozzy_resource.loc as loc,
          IF( geozzy_resourcetype.idName != "rtypePoi", True, False ) as isNormalResource
        FROM geozzy_resource_collections
        LEFT JOIN geozzy_collection
        ON geozzy_resource_collections.collection = geozzy_collection.id
        LEFT JOIN geozzy_collection_resources
        ON geozzy_collection.id = geozzy_collection_resources.collection
        LEFT JOIN geozzy_resource
        ON geozzy_collection_resources.resource = geozzy_resource.id
        LEFT JOIN geozzy_resource_taxonomyterm
        ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource
        LEFT JOIN geozzy_resourcetype
        ON geozzy_resource.rTypeId = geozzy_resourcetype.id
        LEFT JOIN geozzy_url_alias
        ON ( geozzy_url_alias.resource = geozzy_resource.id AND geozzy_url_alias.http = 0 AND geozzy_url_alias.canonical = 1 )

        WHERE
          geozzy_resource.published = 1 AND
          geozzy_collection.collectionType = "poi"
        group by geozzy_collection_resources.id;

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
    'rType' => array(
      'type'=>'VARCHAR'
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'mediumDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'content' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'urlAlias' => array(
      'type' => 'VARCHAR',
      'size' => 2000,
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
    ),
    'isNormalResource' => array(
      'type'=>'BOOLEAN'
    )
  );

  static $extraFilters = array(
    'ids' => ' geozzy_pois_explorer_index.id IN (?)'

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
