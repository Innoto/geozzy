<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class RExtUrlResourceViewModel extends Model {

  static $tableName = 'geozzy_rext_url_resource_view';

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
    'imageName' => [
      'type' => 'VARCHAR',
      'size' => 250
    ],
    'imageAKey' => [
      'type' => 'VARCHAR',
      'size' => 16
    ],
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
    'rextUrlUrl' => array(
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
    'find' => " ( UPPER( geozzy_rext_url_resource_view.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR geozzy_rext_url_resource_view.id = ? )",
    'nottopic' => ' geozzy_rext_url_resource_view.id NOT IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',
    'intopic' => ' geozzy_rext_url_resource_view.id IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',

    'inTopicTaxonomyterm' => ' geozzy_rext_url_resource_view.id IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.taxonomyterm=? ) ',

    'notintaxonomyterm' => ' geozzy_rext_url_resource_view.id NOT IN ( select resource from geozzy_resource_taxonomyterm where geozzy_resource_taxonomyterm.taxonomyterm=? )',
    'inRtype' => ' geozzy_rext_url_resource_view.rTypeId IN (?) ',
    'notInRtype' => ' geozzy_rext_url_resource_view.rTypeId NOT IN (?) ',
    'inRtypeIdName' => ' geozzy_rext_url_resource_view.rTypeIdName IN (?) ',
    'notInRtypeIdName' => ' geozzy_rext_url_resource_view.rTypeIdName NOT IN (?) ',
    'ids' => ' geozzy_rext_url_resource_view.id IN (?) ',
    'inId' => ' geozzy_rext_url_resource_view.id IN (?) ',
    'idIn' => ' geozzy_rext_url_resource_view.id IN (?) ',
    'inIdName' => ' geozzy_rext_url_resource_view.idName IN (?) ',
    'idNameIn' => ' geozzy_rext_url_resource_view.idName IN (?) ',
    'notInId' => ' geozzy_rext_url_resource_view.id NOT IN (?) ',
    'updatedfrom' => ' ( geozzy_rext_url_resource_view.timeCreation >= ? OR geozzy_rext_url_resource_view.timeLastUpdate >= ? ) ',
    'notInCollectionId' => 'geozzy_rext_url_resource_view.id NOT IN (SELECT geozzy_collection_resources.resource from geozzy_collection_resources where geozzy_collection_resources.collection=?)'
  );


  var $notCreateDBTable = true;

  var $deploySQL = array(
    array(
      'execOrder' => 2 ,
      'version' => 'geozzy#1.94',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_rext_url_resource_view;
        CREATE VIEW geozzy_rext_url_resource_view AS
          SELECT
            rv.id, rv.idName, rv.rTypeId, rv.rTypeIdName, rv.user, rv.userUpdate, rv.published,
            {multilang:rv.title_$lang,}
            {multilang:rv.shortDescription_$lang,}
            {multilang:rv.mediumDescription_$lang,}
            {multilang:rv.content_$lang,}
            rv.image, rv.imageName, rv.imageAKey,
            rv.loc, rv.defaultZoom, rv.externalUrl, ru.url as rextUrlUrl,
            {multilang:rv.urlAlias_$lang,}
            {multilang:rv.headKeywords_$lang,}
            {multilang:rv.headDescription_$lang,}
            {multilang:rv.headTitle_$lang,}
            rv.timeCreation, rv.timeLastUpdate, rv.timeLastPublish,
            rv.countVisits, rv.weight
          FROM
            (
            `geozzy_resource_view` `rv`
            LEFT JOIN geozzy_resource_rext_url AS ru ON rv.id = ru.resource)
      '
    )
  );



  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
