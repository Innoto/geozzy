<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ResourceIndexModel extends Model
{
  var $notCreateDBTable = true;
  var $rcSQL = "CREATE VIEW geozzy_resource_index AS
                SELECT geozzy_resource.id as id, geozzy_resource.rTypeId as rTypeId, geozzy_resource.taxonomyterm as taxonomyterm,  geozzy_resource.topic as topic from geozzy_resource
                  LEFT JOIN geozzy_resource_taxonomyterm ON geozzy_resource_taxonomyterm.resource = geozzy_resource.id
                  LEFT JOIN geozzy_resource_topic ON geozzy_resource_topic.resource = geozzy_resource.id;
              ";

  static $tableName = 'geozzy_resource_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    )
  );

  public static $extraFilters = array(
  //  'taxonomyterm' => 'taxonomyterm in (?)',
//    'topic' => 'topic in (?)'
  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
