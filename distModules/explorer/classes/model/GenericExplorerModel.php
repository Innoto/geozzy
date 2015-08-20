<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class GenericExplorerModel extends Model
{
  var $notCreateDBTable = true;
  var $rcSQL = "
                DROP VIEW IF EXISTS geozzy_generic_explorer_index;
                CREATE VIEW geozzy_generic_explorer_index AS
                  SELECT
                    geozzy_resource.id as id,
                    geozzy_resource.title_en as title_en,
                    geozzy_resource.title_es as title_es,
                    geozzy_resource.title_gl as title_gl,
                    geozzy_resource.image as image,
                    geozzy_resource.loc as loc,
                    group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
                  FROM geozzy_resource
                  LEFT JOIN geozzy_resource_taxonomyterm
                  ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource

                  WHERE geozzy_resource.published = 1
                  group by geozzy_resource.id;
              ";

  static $tableName = 'geozzy_generic_explorer_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'title' => array(
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
    )
  );

  static $extraFilters = array();


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
