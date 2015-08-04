<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ResourceIndexModel extends Model
{
  var $notCreateDBTable = true;
  var $rcSQL = "CREATE VIEW geozzy_resource_index AS
                SELECT geozzy_resource.id as id, geozzy_resource.rTypeId as rTypeId, geozzy_resource_taxonomyterm.taxonomyterm as taxonomyterm,  geozzy_resource_topic.topic as topic, geozzy_resource.loc as loc from geozzy_resource
                  LEFT JOIN geozzy_resource_taxonomyterm ON geozzy_resource_taxonomyterm.resource = geozzy_resource.id
                  LEFT JOIN geozzy_resource_topic ON geozzy_resource_topic.resource = geozzy_resource.id;
              ";

  static $tableName = 'geozzy_resource_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true,
    )
  );

  public static $extraFilters = array(
  //  'taxonomyterm' => 'taxonomyterm in (?)',
//    'topic' => 'topic in (?)'
      'taxonomyterms' => 'geozzy_resource_index.taxonomyterm IN( ? )',
      'types' => 'geozzy_resource_index.rTypeId IN( ? )',
      'topics' => 'geozzy_resource_index.topic IN( ? )',
      'bounds' => "MBRContains(PolygonFromText( CONCAT('Polygon((',?,'))') ), loc)"
  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
