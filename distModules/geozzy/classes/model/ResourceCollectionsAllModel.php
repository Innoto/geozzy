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

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_collectionsall;
        CREATE VIEW geozzy_resource_collectionsall AS
          SELECT
            geozzy_collection.id AS id,
            geozzy_collection.idName AS idName,

            {multilang:geozzy_collection.title_$lang AS title_$lang,}

            {multilang:geozzy_collection.shortDescription_$lang AS shortDescription_$lang,}

            {multilang:geozzy_collection.description_$lang AS description_$lang,}

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
      '
    )
  );

  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
