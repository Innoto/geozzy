<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class XantaresExplorerModel extends Model
{
  var $notCreateDBTable = true;
  var $rcSQL = "
                DROP VIEW IF EXISTS geozzy_xantares_explorer_index;
                CREATE VIEW geozzy_xantares_explorer_index AS
                  SELECT
                    geozzy_resource.id as id,
                    geozzy_resourcetype.idName as rtype,
                    geozzy_resource.title_en as title_en,
                    geozzy_resource.title_es as title_es,
                    geozzy_resource.title_gl as title_gl,
                    geozzy_resource.image as image,
                    geozzy_resource.shortDescription_es as shortDescription_es,
                    geozzy_resource.shortDescription_en as shortDescription_en,
                    geozzy_resource.shortDescription_gl as shortDescription_gl,
                    geozzy_resource.loc as loc,
                    geozzy_resource.timeLastUpdate as timeLastUpdate,
                    group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
                  FROM geozzy_resource
                  LEFT JOIN geozzy_resource_taxonomyterm
                  ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource
                  LEFT JOIN geozzy_resourcetype
        				  ON geozzy_resource.rTypeId = geozzy_resourcetype.id

                  WHERE
                    geozzy_resource.published = 1 AND
                    (
                      geozzy_resourcetype.idName = 'rtypeAppRestaurant' OR
                      geozzy_resourcetype.idName = 'rtypeAppFestaPopular' OR
                      geozzy_resourcetype.idName = 'rtypeHotel'
                    )
                  group by geozzy_resource.id;
              ";

  static $tableName = 'geozzy_xantares_explorer_index';
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
    'shortDescription' => array(
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
    'timeLastUpdate' => array(
      'type'=>'GEOMETRY'
    )
  );

  static $extraFilters = array(
    'ids' => ' id IN (?)',
    'updatedfrom' => ' timeLastUpdate > FROM_UNIXTIME(?) '

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
