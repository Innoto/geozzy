<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceCollectionsAllModel extends Model {

  static $tableName = 'geozzy_resource_collectionsall';

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
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'collectionType' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'weight' => array(
      'type' => 'INT',
    ),
    'image' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id'
    ),
    'resourceMain' => array(
      'type' => 'INT',
    ),
    'weightMain' => array(
      'type' => 'INT'
    ),
    'resourceSon' => array(
      'type' => 'FOREIGN',
      'vo' => 'ResourceModel',
      'key' => 'id'
    ),
    'weightSon' => array(
      'type' => 'INT'
    )
  );

  static $extraFilters = array();

  var $notCreateDBTable = true;

  var $rcSQL = '
    DROP VIEW IF EXISTS geozzy_resource_collectionsall;
    CREATE VIEW geozzy_resource_collectionsall AS
      SELECT
        geozzy_collection.id AS id,
        geozzy_collection.idName AS idName,
        geozzy_collection.title_es AS title_es,
        geozzy_collection.title_gl AS title_gl,
        geozzy_collection.title_en AS title_en,
        geozzy_collection.shortDescription_es AS shortDescription_es,
        geozzy_collection.shortDescription_gl AS shortDescription_gl,
        geozzy_collection.shortDescription_en AS shortDescription_en,
        geozzy_collection.collectionType AS collectionType,
        geozzy_collection.weight AS weight,
        geozzy_collection.image AS image,
        geozzy_resource_collections.resource AS resourceMain,
        geozzy_resource_collections.weight AS weightMain,
        geozzy_collection_resources.resource AS resourceSon,
        geozzy_collection_resources.weight AS weightSon
      FROM `geozzy_resource_collections`
        JOIN `geozzy_collection_resources`
        JOIN `geozzy_collection`
      WHERE geozzy_collection_resources.collection = geozzy_collection.id
        AND geozzy_resource_collections.collection = geozzy_collection.id
      ORDER BY geozzy_resource_collections.weight, geozzy_collection_resources.weight;
  ';


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
