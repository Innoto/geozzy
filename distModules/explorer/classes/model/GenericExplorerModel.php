<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class GenericExplorerModel extends Model
{
  var $notCreateDBTable = true;
  var $rcSQL = "CREATE VIEW geozzy_generic_explorer_index AS
                  SELECT geozzy_resource.id as id, group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
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
    'terms' => array(
      'type'=>'VARCHAR'
    )
  );

  static $extraFilters = array();


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
