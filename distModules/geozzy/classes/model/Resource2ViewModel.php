<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class Resource2ViewModel extends Model {

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
    'userName' => array(
      'type' => 'VARCHAR',
      'size' => 255
    ),
    'userSurname' => array(
      'type' => 'VARCHAR',
      'size' => 255
    ),
    'userEmail' => array(
      'type' => 'VARCHAR',
      'size' => 255,
      'unique' => true
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
    ),
    'termIdList' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'topicIdList' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'collIdList' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
    'relatedModels' => array(
      'type' => 'VARCHAR',
      'size' => 250
    ),
  );


  static $extraFilters = array(
    'find' => " ( UPPER( r.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR r.id = ? )",
    'nottopic' => ' r.id NOT IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',
    'intopic' => ' r.id IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',

    'inTopicTaxonomyterm' => ' r.id IN ( select resource from geozzy_resource_topic where geozzy_resource_topic.taxonomyterm=? ) ',

    'notintaxonomyterm' => ' r.id NOT IN ( select resource from geozzy_resource_taxonomyterm where geozzy_resource_taxonomyterm.taxonomyterm=? )',
    'inRtype' => ' r.rTypeId IN (?) ',
    'notInRtype' => ' r.rTypeId NOT IN (?) ',
    'inRtypeIdName' => ' rt.idName IN (?) ',
    'notInRtypeIdName' => ' rt.idName NOT IN (?) ',
    'ids' => ' r.id IN (?) ',
    'inId' => ' r.id IN (?) ',
    'idIn' => ' r.id IN (?) ',
    'inIdName' => ' rt.idName IN (?) ',
    'idNameIn' => ' rt.idName IN (?) ',
    'notInId' => ' r.id NOT IN (?) ',
    'updatedfrom' => ' ( r.timeCreation >= ? OR r.timeLastUpdate >= ? ) ',
    'createdfrom' => ' ( r.timeCreation >= ? ) ',
    'lastUpdatefrom' => ' ( r.timeLastUpdate >= ? ) ',
    'notInCollectionId' => 'r.id NOT IN (SELECT geozzy_collection_resources.resource from geozzy_collection_resources where geozzy_collection_resources.collection=?)',

    'createdAfter' => ' ( r.timeCreation > ? ) ',
    'createdBefore' => ' ( r.timeCreation < ? ) ',

    //'termIdListInSet' => ' FIND_IN_SET( ?, geozzy_resource_view.termIdList ) ',
    'rTypeIdName' => 'rt.idName = ?',
    'idName' => 'r.idName = ?',
  );


  var $notCreateDBTable = true;

  var $deploySQL = array();

  public function customSelectListItems( $extraArrayParam ) {

    $consulta = 'SELECT
      r.id, r.idName, r.rTypeId, rt.idName AS rTypeIdName,
      r.user, u.email AS userEmail, u.name AS userName, u.surname AS userSurname,
      r.userUpdate, r.published,
      {multilang:r.title_$lang,}
      {multilang:r.shortDescription_$lang,}
      {multilang:r.mediumDescription_$lang,}
      {multilang:r.content_$lang,}
      r.image, fd.name AS imageName, fd.AKey AS imageAKey,
      r.loc, r.defaultZoom, r.externalUrl,
      {multilang:GROUP_CONCAT( DISTINCT if(lang="$lang",ua.urlFrom,null)) AS "urlAlias_$lang",}
      {multilang:r.headKeywords_$lang,}
      {multilang:r.headDescription_$lang,}
      {multilang:r.headTitle_$lang,}
      r.timeCreation, r.timeLastUpdate, r.timeLastPublish,
      r.countVisits, r.weight,
      GROUP_CONCAT( DISTINCT rTax.taxonomyterm ORDER BY rTax.weight, rTax.id ) AS termIdList,
      GROUP_CONCAT( DISTINCT rTopic.topic ORDER BY rTopic.weight, rTopic.id ) AS topicIdList,
      GROUP_CONCAT( DISTINCT rColl.collection ORDER BY rColl.weight, rColl.id ) AS collIdList,
      rt.relatedModels AS relatedModels
    FROM
      (((((((
      `geozzy_resource` `r`
      JOIN `geozzy_resourcetype` `rt` )
      LEFT JOIN `user_user` `u` ON (`u`.`id` = `r`.`user`) )
      LEFT JOIN `geozzy_url_alias` `ua` ON (
        `ua`.`resource` = `r`.`id` AND `ua`.`http` = 0 AND `ua`.`canonical` = 1 ) )
      LEFT JOIN `filedata_filedata` AS `fd` ON (`r`.`image` = `fd`.`id`) )
      LEFT JOIN `geozzy_resource_taxonomyterm` `rTax` ON (`r`.`id` = `rTax`.`resource`) )
      LEFT JOIN `geozzy_resource_topic` `rTopic` ON (`r`.`id` = `rTopic`.`resource`) )
      LEFT JOIN `geozzy_resource_collections` `rColl` ON (`r`.`id` = `rColl`.`resource`) )
      WHERE
          '. $extraArrayParam['strWhere'] . ' AND `rt`.`id` = `r`.`rTypeId`
      GROUP BY `r`.`id`
      '. $extraArrayParam['strOrderBy'] . '
      '. $extraArrayParam['strRange'] . '
    ';
    return $consulta;
  }

  /*public function customSelectListCount( $extraArrayParam ) {
      return "SELECT count(*) from my_model $extraArrayParam['strSQL']";
    }*/


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
    $rextModel = false;
    $cacheQuery = true;

    eval( '$rextControl = new '.$rextModelName.'();');
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
    $cacheQuery = true;

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
    $cacheQuery = true;

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
