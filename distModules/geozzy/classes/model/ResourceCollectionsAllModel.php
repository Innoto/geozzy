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
    'description' => array(
      'type' => 'TEXT',
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
    'imageName' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'imageAKey' => [
      'type' => 'VARCHAR',
      'size' => 16
    ],
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

  static $extraFilters = array(
    'collectionTypeIn' => ' geozzy_resource_collectionsall.collectionType IN (?) ',
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'geozzy#1.92',
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

            geozzy_collection.image AS image, fd.name AS imageName, fd.aKey AS imageAKey,

            geozzy_resource_collections.resource AS resourceMain,
            geozzy_resource_collections.weight AS weightMain,
            geozzy_collection_resources.resource AS resourceSon,
            geozzy_collection_resources.weight AS weightSon
          FROM
            (((
            `geozzy_resource_collections`
            join `geozzy_collection_resources`)
            join `geozzy_collection`)
            LEFT JOIN filedata_filedata AS fd ON
              geozzy_collection.image = fd.id)
          WHERE geozzy_collection_resources.collection = geozzy_collection.id
            AND geozzy_resource_collections.collection = geozzy_collection.id
          ORDER BY geozzy_resource_collections.weight, geozzy_collection_resources.weight;
      '
    ),
    array(
      'version' => 'geozzy#1.7',
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
    ),
    array(
      'version' => 'geozzy#1.0',
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_collectionsall;
        CREATE VIEW geozzy_resource_collectionsall AS
          SELECT
            geozzy_collection.id AS id,
            geozzy_collection.idName AS idName,

            {multilang:geozzy_collection.title_$lang AS title_$lang,}

            {multilang:geozzy_collection.shortDescription_$lang AS shortDescription_$lang,}

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
