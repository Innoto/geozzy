<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class CollectionTypeResourcesModel extends Model {

  static $tableName = 'geozzy_collectiontype_resources';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'rTypeId' => array(
      'type' => 'INT'
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'collectionType' => array(
      'type' => 'VARCHAR',
      'size' => 20
    ),
    'collection' => array(
      'type' => 'INT'
    ),
    'weight' => array(
      'type' => 'INT',
    ),
    'parentResource' => array(
      'type' => 'INT',
    )
  );

  static $extraFilters = array(
    // Filtro para traer todos os recursos dos rtypes permitidos nunha coleccion ou os recursos nunha coleccion do tipo especificado e o recurso especificado
    // variable: "19,24;34;event" -> "rtype1, rtype2 ... rtypeN; idResource; collectionType"
    'conditionsRtypeCollection' => " FIND_IN_SET(geozzy_collectiontype_resources.rTypeId, SUBSTRING_INDEX(?,';',1)) or (
    	geozzy_collectiontype_resources.parentResource=SUBSTRING_INDEX(SUBSTRING_INDEX(?,';',2),';',-1)
    	and
    	geozzy_collectiontype_resources.collectionType=SUBSTRING_INDEX(?,';',-1)
    );",
    'conditionsRtypenotInCollection' => " geozzy_collectiontype_resources.rTypeId IN (?) and geozzy_collectiontype_resources.parentResource IS NULL;"
);

  var $notCreateDBTable = true;

//            {multilang:geozzy_resource.title_$lang AS title_$lang,}

  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.97',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_collectiontype_resources;
        CREATE VIEW geozzy_collectiontype_resources AS
          SELECT
            geozzy_resource.id AS id,
            geozzy_resource.rTypeId as rTypeId,
            {multilang:geozzy_resource.title_$lang AS title_$lang,}
            geozzy_collection.collectionType as collectionType,
            geozzy_collection_resources.collection as collection,
            geozzy_collection_resources.weight AS weight,
            geozzy_parent_resource.id as parentResource
          FROM `geozzy_resource`
            LEFT JOIN `geozzy_collection_resources`
            ON geozzy_resource.id = geozzy_collection_resources.resource
            LEFT JOIN `geozzy_collection`
            ON geozzy_collection.id = geozzy_collection_resources.collection
            LEFT JOIN `geozzy_resource_collections`
            ON geozzy_resource_collections.collection = geozzy_collection.id
            LEFT JOIN `geozzy_resource` as geozzy_parent_resource
            ON geozzy_resource_collections.resource = geozzy_parent_resource.id
            GROUP BY id
            ORDER BY geozzy_collection_resources.weight;
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }
}
