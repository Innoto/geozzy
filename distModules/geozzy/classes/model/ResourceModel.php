<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');


class ResourceModel extends Model
{
  static $tableName = 'geozzy_resource';

  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
      'autoincrement' => true
    ),
    'rTypeId' => array(
      'type'=>'FOREIGN',
      'vo' => 'ResourceTypeModel',
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
      'key' => 'id'
    ),
    'loc' => array(
      'type' => 'GEOMETRY'
    ),
    'defaultZoom' => array(
      'type' => 'INT'
    ),
    'headKeywords' => array(
      'type' => 'VARCHAR',
      'size' => 150
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
    'averageVotes' => array(
      'type' => 'FLOAT'
    )
  );

  static $extraFilters = array(
    'find' => "UPPER(title)  LIKE CONCAT('%',UPPER(?),'%')",
    'nottopic' => "id NOT IN (select resource from geozzy_resource_topic where topic=?)",
    'notintaxonomyterm' => "id NOT IN (select resource from geozzy_resource_taxonomyterm where taxonomyterm=?)"
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


  public function dependencesByResourcetype( $rtypeName ) {
    $dependences = false;

    geozzy::load('model/ResourcetypeModel.php');
    $rtypeModel = new ResourcetypeModel();
    $rtypeList = $rtypeModel->listItems( array('filters'=>array('idName'=> $rtypeName) ) );
    if( $rtype = $rtypeList->fetch() ) {
      $dep = json_decode( $rtype->getter('relatedModels') );
      if( count( $dep ) > 0 ) {
        $dependences = $dep;
      }
    }

    return $dependences;
  }
}
