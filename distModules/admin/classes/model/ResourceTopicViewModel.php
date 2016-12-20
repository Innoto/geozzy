<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceTopicViewModel extends Model {

  static $tableName = 'resource_topic_view';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'topicTaxonomyterm' => array(
      'type' => 'INT'
    ),
    'idName' => array(
      'type' => 'VARCHAR',
      'size' => 100
    ),
    'rTypeId' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourcetypeModel',
      'key'=> 'id'
    ),
    'user' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'userUpdate' => array(
      'type'=>'FOREIGN',
      'vo' => 'UserModel',
      'key'=> 'id'
    ),
    'published' => array(
      'type' => 'BOOLEAN'
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
    'mediumDescription' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),
    'content' => array(
      'type' => 'TEXT',
      'multilang' => true
    ),

    'loc' => array(
      'type' => 'GEOMETRY'
    ),
    'defaultZoom' => array(
      'type' => 'INT'
    ),
    'externalUrl' => array(
      'type' => 'VARCHAR',
      'size' => 2000
    ),
    'headKeywords' => array(
      'type' => 'VARCHAR',
      'size' => 150,
      'multilang' => true
    ),
    'headDescription' => array(
      'type' => 'VARCHAR',
      'size' => 150,
      'multilang' => true
    ),
    'headTitle' => array(
      'type' => 'VARCHAR',
      'size' => 100,
      'multilang' => true
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'DATETIME'
    ),
    'timeLastPublish' => array(
      'type' => 'DATETIME'
    ),
    'countVisits' => array(
      'type' => 'INT'
    ),
    'weight' => array(
      'type' => 'SMALLINT',
      'default' => 0
    )
  );

  static $extraFilters = array(
    'find' => " ( UPPER( geozzy_resource.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR geozzy_resource.id = ? )",
    'nottopic' => ' geozzy_resource.id NOT IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',
    'notintaxonomyterm' => ' geozzy_resource.id NOT IN ( select resource from geozzy_resource_taxonomyterm where geozzy_resource_taxonomyterm.taxonomyterm=? )',
    'inRtype' => ' rTypeId IN (?) ',
    'notInRtype' => ' geozzy_resource.rTypeId NOT IN (?) ',
    'ids' => ' geozzy_resource.id IN (?) ',
    'inId' => ' geozzy_resource.id IN (?) ',
    'idIn' => ' geozzy_resource.id IN (?) ',
    'inIdName' => ' geozzy_resource.idName IN (?) ',
    'idNameIn' => ' geozzy_resource.idName IN (?) ',
    'notInId' => ' geozzy_resource.id NOT IN (?) ',
    'updatedfrom' => ' ( geozzy_resource.timeCreation >= ? OR geozzy_resource.timeLastUpdate >= ? ) ',
    'notInCollectionId' => 'geozzy_resource.id NOT IN (SELECT geozzy_collection_resources.resource from geozzy_collection_resources where geozzy_collection_resources.collection=?)'
  );

  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'admin#1.4',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS resource_topic_view;
        CREATE VIEW resource_topic_view AS
          SELECT
          geozzy_resource_topic.id as id,
          geozzy_resource_topic.taxonomyterm as topicTaxonomyterm,
          geozzy_resource.idName as idName,
          geozzy_resource.rTypeId as  rTypeId,
          geozzy_resource.user as user,
          geozzy_resource.userUpdate as userUpdate,
          geozzy_resource.published as published,
          {multilang:geozzy_resource.title_$lang AS title_$lang,}
          {multilang:geozzy_resource.shortDescription_$lang AS shortDescription_$lang,}
          {multilang:geozzy_resource.mediumDescription_$lang AS mediumDescription_$lang,}
          {multilang:geozzy_resource.content_$lang AS content_$lang,}

          geozzy_resource.image as image,
          geozzy_resource.loc as loc,
          geozzy_resource.defaultZoom as defaultZoom,
          geozzy_resource.externalUrl as externalUrl,

          {multilang:geozzy_resource.headKeywords_$lang AS headKeywords_$lang,}
          {multilang:geozzy_resource.headDescription_$lang AS headDescription_$lang,}
          {multilang:geozzy_resource.headTitle_$lang AS headTitle_$lang,}

          geozzy_resource.timeCreation as timeCreation,

          geozzy_resource.timeLastUpdate as timeLastUpdate,
          geozzy_resource.timeLastPublish as timeLastPublish,
          geozzy_resource.countVisits as countVisits,
          geozzy_resource.weight as weight

          FROM `geozzy_resource`
          INNER JOIN geozzy_resource_topic ON geozzy_resource_topic.resource = geozzy_resource.id ;
      '
    )
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

  public function updateTopicTaxonomy( $id, $taxonomyTermId) {

    $topic = (new ResourceTopicModel())->listItems(array("filters" => array("id" =>  $id )))->fetch();
    $topic->setter('taxonomyterm', $taxonomyTermId );
    $topic->save();
  }
}
