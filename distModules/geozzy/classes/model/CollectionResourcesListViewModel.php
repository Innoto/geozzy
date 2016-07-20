<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class CollectionResourcesListViewModel extends Model {

  static $tableName = 'geozzy_collection_resourcelist_view';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 240
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 240,
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'size' => 240,
      'multilang' => true
    ),
    'description' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'image' => array(
      'type' => 'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/Collection/'
    ),
    'share' => array(
      'type' => 'TINYINT'
    ),
    'collectionType' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'weight' => array(
      'type' => 'SMALLINT'
    ),
    'resourceMain' => array(
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'weightMain' => array(
      'type' => 'SMALLINT'
    ),
    'resourceSonList' => array(
      'type' => 'VARCHAR'
    )
  );

  static $extraFilters = array(
    'idIn' => ' geozzy_collection_resourcelist_view.id IN (?) ',
    'resourceMainIn' => ' geozzy_collection_resourcelist_view.resourceMain IN (?) ',
    'collectionTypeIn' => ' geozzy_collection_resourcelist_view.collectionType IN (?) ',
    'collectionTypeNotIn' => ' geozzy_collection_resourcelist_view.collectionType NOT IN (?) ',
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_collection_resourcelist_view;
        CREATE VIEW geozzy_collection_resourcelist_view AS
          SELECT
            geozzy_collection.id AS id,
            geozzy_collection.idName AS idName,

            {multilang:geozzy_collection.title_$lang AS title_$lang,}

            {multilang:geozzy_collection.shortDescription_$lang AS shortDescription_$lang,}

            {multilang:geozzy_collection.description_$lang AS description_$lang,}


            geozzy_collection.image AS image,
            geozzy_collection.share AS share,
            geozzy_collection.collectionType AS collectionType,
            geozzy_collection.weight AS weight,
            geozzy_resource_collections.resource AS resourceMain,
            geozzy_resource_collections.weight AS weightMain,
            GROUP_CONCAT( geozzy_collection_resources.resource ORDER BY geozzy_collection_resources.weight ) AS resourceSonList
          FROM `geozzy_resource_collections`
            JOIN `geozzy_collection_resources`
            JOIN `geozzy_collection`
          WHERE geozzy_collection_resources.collection = geozzy_collection.id
            AND geozzy_resource_collections.collection = geozzy_collection.id
          GROUP BY geozzy_collection.id
          ORDER BY geozzy_resource_collections.weight;
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
