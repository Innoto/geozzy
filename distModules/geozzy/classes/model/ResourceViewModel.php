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
    'inRtypeIdName' => ' geozzy_resource_view.rTypeIdName IN (?) ',
    'notInRtypeIdName' => ' geozzy_resource_view.rTypeIdName NOT IN (?) ',
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

  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.94',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_resource_view;
        CREATE VIEW geozzy_resource_view AS
          SELECT
            r.id, r.idName, r.rTypeId, rt.idName AS rTypeIdName, r.user, r.userUpdate, r.published,
            {multilang:r.title_$lang,}
            {multilang:r.shortDescription_$lang,}
            {multilang:r.mediumDescription_$lang,}
            {multilang:r.content_$lang,}
            r.image, fd.name AS imageName, fd.AKey AS imageAKey,
            r.loc, r.defaultZoom, r.externalUrl,
            {multilang:GROUP_CONCAT(if(lang="$lang",ua.urlFrom,null)) AS "urlAlias_$lang",}
            {multilang:r.headKeywords_$lang,}
            {multilang:r.headDescription_$lang,}
            {multilang:r.headTitle_$lang,}
            r.timeCreation, r.timeLastUpdate, r.timeLastPublish,
            r.countVisits, r.weight
          FROM
            (((
            `geozzy_resource` `r`
            join `geozzy_resourcetype` `rt`)
            LEFT JOIN `geozzy_url_alias` `ua` ON
              ( `ua`.`resource` = `r`.`id`
              and `ua`.`http` = 0
              and `ua`.`canonical` = 1 ) )
            LEFT JOIN filedata_filedata AS fd ON r.image = fd.id)
          WHERE
            rt.id=r.rTypeId
          GROUP BY
            r.id
      '
    )
  );



  public function getRextModels() {

    $rextModelArray = array();

    geozzy::load('model/ResourcetypeModel.php');

    $relatedModels =  $this->dependencesByResourcetypeId( $this->getter('rTypeId') );

    if( $relatedModels ) {
      foreach( $relatedModels as $relModel ) {
        $rextModelArray[$relModel] = $this->getRextModel( $relModel );
      }

    }

    return $rextModelArray;
  }

  public function getRextModel( $rextModelName ) {
    eval( '$rextControl = new '.$rextModelName.'();');
    $rextModel = false;
    if(is_object($rextControl)){
      $rextList = $rextControl->listItems( array( 'filters'=> array( 'resource' => $this->getter('id') ) ) );
      if(is_object($rextList)){
        $rextModel = $rextList->fetch(); // false if doesn't exist
      }
    }

    return $rextModel;
  }

  public function dependencesByResourcetype( $rtypeName ) {
    $dependences = false;

    geozzy::load( 'model/ResourcetypeModel.php' );
    $rtypeModel = new ResourcetypeModel();
    $rtypeList = $rtypeModel->listItems( array( 'filters' => array( 'idName' => $rtypeName ) ) );
    if( $rtype = $rtypeList->fetch() ) {
      $dep = json_decode( $rtype->getter('relatedModels') );
      if( count( $dep ) > 0 ) {
        $dependences = $dep;
      }
    }

    return $dependences;
  }

  public function dependencesByResourcetypeId( $rtypeId ) {
    $dependences = false;

    geozzy::load( 'model/ResourcetypeModel.php' );
    $rtypeModel = new ResourcetypeModel();
    $rtypeList = $rtypeModel->listItems( array( 'filters' => array( 'id' => $rtypeId ) ) );
    if( $rtype = $rtypeList->fetch() ) {
      $dep = json_decode( $rtype->getter('relatedModels') );
      if( count( $dep ) > 0 ) {
        $dependences = $dep;
      }
    }

    return $dependences;
  }

}
