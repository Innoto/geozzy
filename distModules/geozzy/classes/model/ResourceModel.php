<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceModel extends Model {

  static $tableName = 'geozzy_resource';

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

  var $deploySQL = array(
    array(
      'version' => 'geozzy#1.6',
      'sql'=> '
        ALTER TABLE geozzy_resource
        ADD COLUMN idName VARCHAR(100) NULL AFTER id
      '
    ),
    array(
      'version' => 'geozzy#1.4',
      'sql'=> '
        ALTER TABLE geozzy_resource
        DROP COLUMN averageVotes
      '
    ),
    array(
      'version' => 'geozzy#1.3',
      'sql'=> '
        ALTER TABLE geozzy_resource
        MODIFY COLUMN averageVotes SMALLINT
      '
    )
  );

  static $extraFilters = array(
    'findFull{multilang}' => ' (
        UPPER( geozzy_resource.title_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_resource.shortDescription_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_resource.mediumDescription_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_resource.content_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
    )
    ',
    'find{multilang}' => ' ( UPPER( geozzy_resource.title_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR geozzy_resource.id = ? )',
    //'find' => " ( UPPER( geozzy_resource.title_es )  LIKE CONCAT( '%', UPPER(?), '%' ) OR geozzy_resource.id = ? )",
    'nottopic' => ' geozzy_resource.id NOT IN ( select geozzy_resource_topic.resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',
    'intopic' => '  geozzy_resource.id IN ( select geozzy_resource_topic.resource from geozzy_resource_topic where geozzy_resource_topic.topic=? ) ',

    'inTopicTaxonomyterm' => '  geozzy_resource.id IN ( select geozzy_resource_topic.resource from geozzy_resource_topic where geozzy_resource_topic.taxonomyterm=? ) ',

    'notintaxonomyterm' => ' geozzy_resource.id NOT IN ( select geozzy_resource_taxonomyterm.resource from geozzy_resource_taxonomyterm where geozzy_resource_taxonomyterm.taxonomyterm=? )',
    'inRtype' => ' geozzy_resource.rTypeId IN (?) ',
    'notInRtype' => ' geozzy_resource.rTypeId NOT IN (?) ',
    'ids' => ' geozzy_resource.id IN (?) ',
    'inId' => ' geozzy_resource.id IN (?) ',
    'idIn' => ' geozzy_resource.id IN (?) ',
    'inIdName' => ' geozzy_resource.idName IN (?) ',
    'idNameIn' => ' geozzy_resource.idName IN (?) ',
    'notInId' => ' geozzy_resource.id NOT IN (?) ',
    'updatedfrom' => ' ( geozzy_resource.timeCreation >= ? OR geozzy_resource.timeLastUpdate >= ? ) ',
    'notInCollectionId' => 'geozzy_resource.id NOT IN (SELECT geozzy_collection_resources.resource from geozzy_collection_resources where geozzy_collection_resources.collection=?)',
    'notAsigned' => 'geozzy_resource.id NOT IN (SELECT geozzy_collection_resources.resource from geozzy_collection_resources)',

    'distance2K' => ' geozzy_resource.loc IS NOT NULL AND ST_Distance_Sphere( geozzy_resource.loc, ST_GeomFromText( ? ) ) < 2000 ',
    'idGt' => ' geozzy_resource.id > (?) ',
    'idLt' => ' geozzy_resource.id < (?) '
  );


  public function __construct( $datarray = array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }


  /**
   * Create relation between resource and topic
   *
   * @return boolean
   */
  public function createTopicRelation( $topicId, $resourceId ) {
    //$this->dataFacade->transactionStart();

    //Cogumelo::debug( 'Called create on '.get_called_class().' with "'.$this->getFirstPrimarykeyId().'" = '. $this->getter( $this->getFirstPrimarykeyId() ) );
    $resourcetopic =  new ResourceTopicModel(array("resource" => $resourceId, "topic" => $topicId));
    $resourcetopic->save();
    //$this->dataFacade->transactionEnd();

    return true;
  }


  /**
   * Delete relation between resource and topic
   *
   * @return boolean
   */
  public function deleteTopicRelation( $topicId, $resourceId ) {
    //$this->dataFacade->transactionStart();
    //Cogumelo::debug( 'Called create on '.get_called_class().' with "'.$this->getFirstPrimarykeyId().'" = '. $this->getter( $this->getFirstPrimarykeyId() ) );
    $resourcetopic =  new ResourceTopicModel();
    $resourceRel = $resourcetopic->listItems( array('filters' => array('resource' => $resourceId, 'topic'=> $topicId)))->fetch();

    $deleted = false;
    if ($resourceRel){
      $deleted = $resourceRel->delete();
    }

    if ($deleted){
      return true;
    }
    else{
      return false;
    }
  }

  /**
   * Create relation between resource and starred taxonomy
   *
   * @return boolean
   */
  public function createTaxonomytermRelation( $starredId, $resourceId ) {
    //$this->dataFacade->transactionStart();

    //Cogumelo::debug( 'Called create on '.get_called_class().' with "'.$this->getFirstPrimarykeyId().'" = '. $this->getter( $this->getFirstPrimarykeyId() ) );
    $resourcetopic =  new ResourceTaxonomytermModel(array("resource" => $resourceId, "taxonomyterm" => $starredId));
    $resourcetopic->save();
    //$this->dataFacade->transactionEnd();

    return true;
  }

  /**
   * Create relation between resource and starred taxonomy
   *
   * @return boolean
   */
  public function createCollectionRelation( $collectionId, $resourceId ) {
    //$this->dataFacade->transactionStart();
    $resourcecollection =  new CollectionResourcesModel(array("resource" => $resourceId, "collection" => $collectionId));
    $resourcecollection->save();
    //$this->dataFacade->transactionEnd();

    return true;
  }



  /**
   * Delete item (This method is a mod from Model::delete)
   *
   * @param array $parameters array of filters
   *
   * @return boolean
   */
  public function delete( array $parameters = array() ) {


    Cogumelo::debug( 'Called custom delete on '.get_called_class().' with "'.
      $this->getFirstPrimarykeyId().'" = '. $this->getter( $this->getFirstPrimarykeyId() ) );
    $this->dataFacade->deleteFromKey( $this->getFirstPrimarykeyId(), $this->getter( $this->getFirstPrimarykeyId() )  );



    // Remove resource taxonomy term
    $resourceTaxonomytermControl = new ResourceTaxonomytermModel();
    $resourceTaxonomyTermList = $resourceTaxonomytermControl->listItems( array('filters'=> array('resource'=> $this->getter('id') ) ) );

    while( $resourceTaxonomyTerm = $resourceTaxonomyTermList->fetch()  ) {
      $resourceTaxonomyTerm->delete();
    }


    // Remove resource Topic
    $resourceTopicControl = new ResourceTopicModel();
    $resourceTopicList = $resourceTopicControl->listItems( array('filters'=> array('resource'=> $this->getter('id') ) ) );

    while( $resourceTopic = $resourceTopicList->fetch()  ) {
      $resourceTopic->delete();
    }


    // remove all relation between Resource and COLLECTIONS
    $resourceCollectionsControl = new ResourceCollectionsModel();
    $resourceCollectionsList = $resourceCollectionsControl->listItems( array('filters'=> array('resource'=> $this->getter('id') ) ) );

    $collectionsToRemove = array();

    while( $resourceCollections = $resourceCollectionsList->fetch()  ) {
      $resourceCollections->delete();
    }


    $collectionResourcesModel = new CollectionResourcesModel();
    $CollectionResourcesList = $collectionResourcesModel->listItems( array('filters'=> array('resource'=> $this->getter('id') ) ) );

    while( $CollectionResources = $CollectionResourcesList->fetch()  ) {
      $collectionsToRemove[] = $CollectionResources->getter('collection');
      $CollectionResources->delete();
    }


    // Remove all REXT related models
    $relatedModels = $this->getRextModels();

    foreach( $relatedModels as $relModelIdName => $relModel ) {
      if($relModel) {
        $relModel->delete();
      }
    }

    return true;
  }




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

    $rextList = $rextControl->listItems( array( 'filters'=> array( 'resource' => $this->getter('id') ) ) );

    $rextModel = $rextList->fetch(); // false if doesn't exist

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



  public function updateTopicTaxonomy( $idResource, $idTopic , $taxonomyTermId) {

    $topic = (new ResourceTopicModel())->listItems(
      array("filters" => array("resource" =>  $idResource, "topic" => $idTopic ))
    )->fetch();

    $topic->setter('taxonomyterm', $taxonomyTermId );
    $topic->save();
  }

  public function setPublishedStatus( $idResource, $published) {

    $resource = (new ResourceModel())->listItems(
      array("filters" => array("id" =>  $idResource ))
    )->fetch();

    $resource->setter('published', $published );

    $resource->save();
  }


  public function deleteResource( $resourceId ) {

    $resource = (new ResourceModel())->listItems( array('filters' => array('id' => $resourceId)))->fetch();
    $resource->delete();
  }


}
