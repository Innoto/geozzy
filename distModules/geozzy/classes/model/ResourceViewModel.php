<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceViewModel extends Model {

  static $tableName = 'geozzy_resource_view';

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
    'rTypeId' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourcetypeModel',
      'key'=> 'id'
    ),
    'rTypeIdName' => array(
      'type' => 'VARCHAR',
      'size' => 100
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
    'image' => array(
      'type'=>'FOREIGN',
      'vo' => 'FiledataModel',
      'key' => 'id',
      'uploadDir' => '/Resource/'
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
    'urlAlias' => array(
      'type' => 'VARCHAR',
      'size' => 2000,
      'multilang' => true
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
    'find' => " ( UPPER( geozzy_resource_view.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR geozzy_resource_view.id = ? )",
    'nottopic' => ' geozzy_resource_view.id NOT IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',
    'intopic' => ' geozzy_resource_view.id IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',

    'inTopicTaxonomyterm' => ' geozzy_resource_view.id IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.taxonomyterm=? ) ',

    'notintaxonomyterm' => ' geozzy_resource_view.id NOT IN ( select resource from geozzy_resource_taxonomyterm where geozzy_resource_taxonomyterm.taxonomyterm=? )',
    'inRtype' => ' geozzy_resource_view.rTypeId IN (?) ',
    'notInRtype' => ' geozzy_resource_view.rTypeId NOT IN (?) ',
    'ids' => ' geozzy_resource_view.id IN (?) ',
    'inId' => ' geozzy_resource_view.id IN (?) ',
    'idIn' => ' geozzy_resource_view.id IN (?) ',
    'inIdName' => ' geozzy_resource_view.idName IN (?) ',
    'idNameIn' => ' geozzy_resource_view.idName IN (?) ',
    'notInId' => ' geozzy_resource_view.id NOT IN (?) ',
    'updatedfrom' => ' ( geozzy_resource_view.timeCreation >= ? OR geozzy_resource_view.timeLastUpdate >= ? ) ',
    'notInCollectionId' => 'geozzy_resource_view.id NOT IN (SELECT geozzy_collection_resources.resource from geozzy_collection_resources where geozzy_collection_resources.collection=?)'
  );


  var $notCreateDBTable = true;

  var $deploySQL = [
    [
      'version' => 'geozzy#1.9',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_view;

        CREATE VIEW geozzy_resource_view AS

        SELECT
          r.id, r.idName, r.rTypeId, rt.idName as rTypeIdName, user, userUpdate, published,
          {multilang:title_$lang,}
          {multilang:shortDescription_$lang,}
          {multilang:mediumDescription_$lang,}
          {multilang:content_$lang,}
          image, loc, defaultZoom, externalUrl,
          {multilang:GROUP_CONCAT(if(lang="$lang",ua.urlFrom,null)) as "urlAlias_$lang",}
          {multilang:headKeywords_$lang,}
          {multilang:headDescription_$lang,}
          {multilang:headTitle_$lang,}
          timeCreation, timeLastUpdate, timeLastPublish,
          countVisits, r.weight
        FROM
          geozzy_resource AS r, geozzy_resourcetype AS rt, geozzy_url_alias AS ua
        WHERE
          rt.id=r.rTypeId AND ua.resource=r.id AND ua.http=0 AND ua.canonical=TRUE
        GROUP BY
          r.id
      '
    ]
  ];



  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
