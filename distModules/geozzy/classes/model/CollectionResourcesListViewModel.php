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
            geozzy_collection.title_es AS title_es,
            geozzy_collection.title_gl AS title_gl,
            geozzy_collection.title_en AS title_en,
            geozzy_collection.shortDescription_es AS shortDescription_es,
            geozzy_collection.shortDescription_gl AS shortDescription_gl,
            geozzy_collection.shortDescription_en AS shortDescription_en,
            geozzy_collection.description_es AS description_es,
            geozzy_collection.description_gl AS description_gl,
            geozzy_collection.description_en AS description_en,
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
