<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class ExplorerSearchModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'explorerSearch#1',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_explorer_search_index;
        CREATE VIEW geozzy_explorer_search_index AS
          SELECT
            geozzy_resource.id as id,
            {multilang:geozzy_resource.title_$lang as title_$lang,}

            {multilang: geozzy_resource.shortDescription_$lang as shortDescription_$lang, }
            {multilang: geozzy_resource.mediumDescription_$lang as mediumDescription_$lang, }
            {multilang: geozzy_resource.content_$lang as content_$lang, }

            {multilang:group_concat(geozzy_taxonomy_view.name_$lang) as termsName_$lang,}
            group_concat(geozzy_resource_taxonomyterm.taxonomyterm) as terms
          FROM geozzy_resource
          LEFT JOIN geozzy_resource_taxonomyterm
          ON geozzy_resource.id = geozzy_resource_taxonomyterm.resource

          LEFT JOIN geozzy_taxonomy_view
          ON geozzy_resource_taxonomyterm.id = geozzy_taxonomy_view.id

          WHERE geozzy_resource.published = 1
          group by geozzy_resource.id;
      '
    )
  );



  static $tableName = 'geozzy_explorer_search_index';
  static $cols = array(
    'id' => array(
      'type' => 'INT',
      'primarykey' => true
    ),
    'title' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    )
  );

  static $extraFilters = array(
    'ids' => ' geozzy_explorer_search_index.id IN (?)',
    'findFull{multilang}' => ' (
        UPPER( geozzy_explorer_search_index.title_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_explorer_search_index.shortDescription_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_explorer_search_index.mediumDescription_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_explorer_search_index.content_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' ) OR
        UPPER( geozzy_explorer_search_index.termsName_$lang )  LIKE CONCAT( \'%\', UPPER(?), \'%\' )
    )
    ',

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
