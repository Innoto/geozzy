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
    'type' => array(
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
    'timeCreation' => array(
      'type' => 'TIMESTAMP'
    ),
    'timeLastUpdate' => array(
      'type' => 'TIMESTAMP'
    ),
    'timeLastPublish' => array(
      'type' => 'TIMESTAMP'
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


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }


  /**
  * create relation between resource and topic
  *
  * @return boolean
  */
  function createTopicRelation($topicId, $resourceId) {

    $this->dataFacade->transactionStart();

    //Cogumelo::debug( 'Called create on '.get_called_class().' with "'.$this->getFirstPrimarykeyId().'" = '. $this->getter( $this->getFirstPrimarykeyId() ) );
    $resourcetopic =  new ResourceTopicModel(array("resource" => $resourceId, "topic" => $topicId));
    $resourcetopic->save();


    $this->dataFacade->transactionEnd();

    return true;
  }

   /**
  * create relation between resource and starred taxonomy
  *
  * @return boolean
  */
  function createTaxonomytermRelation($starredId, $resourceId) {

    $this->dataFacade->transactionStart();

    //Cogumelo::debug( 'Called create on '.get_called_class().' with "'.$this->getFirstPrimarykeyId().'" = '. $this->getter( $this->getFirstPrimarykeyId() ) );
    $resourcetopic =  new ResourceTaxonomytermModel(array("resource" => $resourceId, "taxonomyterm" => $starredId));
    $resourcetopic->save();


    $this->dataFacade->transactionEnd();

    return true;
  }


}
