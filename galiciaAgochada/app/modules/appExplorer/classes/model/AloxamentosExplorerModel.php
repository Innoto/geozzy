<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class AloxamentosExplorerModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'appExplorer#1.8',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_aloxamentos_explorer_index;
        CREATE VIEW geozzy_aloxamentos_explorer_index AS
        SELECT
          geozzy_resource.id as id,
          geozzy_resourcetype.idName as rtype,

          {multilang:geozzy_resource.title_$lang as title_$lang,}

          geozzy_resource.image as image,

          {multilang:geozzy_resource.mediumDescription_$lang as mediumDescription_$lang,}


          geozzy_resource.loc as loc,
          geozzy_resource_rext_accommodation.averagePrice as averagePrice,
          geozzy_resource_rext_contact.city as city,
          geozzy_resource.timeCreation as timeCreation,
          geozzy_resource.timeLastUpdate as timeLastUpdate,
          group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
        FROM geozzy_resource
        LEFT JOIN geozzy_resource_taxonomyterm
        ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource
        LEFT JOIN geozzy_resourcetype
        ON geozzy_resource.rTypeId = geozzy_resourcetype.id
        LEFT JOIN geozzy_resource_topic
        ON geozzy_resource.id = geozzy_resource_topic.resource
        LEFT JOIN geozzy_topic
        ON geozzy_resource_topic.topic = geozzy_topic.id
        LEFT JOIN geozzy_resource_rext_accommodation
        ON geozzy_resource.id = geozzy_resource_rext_accommodation.resource
        LEFT JOIN geozzy_resource_rext_contact
        ON geozzy_resource.id = geozzy_resource_rext_contact.resource

        WHERE
          geozzy_resource.published = 1 AND
          geozzy_topic.idName = \'AloxamentoConEncanto\'
        group by geozzy_resource.id;
      '
    )
  );




  static $tableName = 'geozzy_aloxamentos_explorer_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'rtype' => array(
      'type' => 'VARCHAR'
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'mediumDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'image' => array(
      'type' => 'INT'
    ),
    'terms' => array(
      'type'=>'VARCHAR'
    ),
    'loc' => array(
      'type'=>'GEOMETRY'
    ),
    'averagePrice' => array(
      'type' => 'FLOAT'
    ),
    'city' => array(
      'type' => 'VARCHAR'
    ),
    'timeCreation' => array(
      'type' => 'DATETIME'
    ),
    'timeLastUpdate' => array(
      'type' => 'DATETIME'
    )
  );

  static $extraFilters = array(
    'ids' => ' id IN (?)',
    'updatedfrom' => ' ( geozzy_aloxamentos_explorer_index.timeCreation >= ? OR geozzy_aloxamentos_explorer_index.timeLastUpdate >= ? ) '

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
