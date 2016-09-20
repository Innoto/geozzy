<?php
Cogumelo::load('coreModel/VO.php');
Cogumelo::load('coreModel/Model.php');




class AllstoriesViewModel extends Model
{
  var $notCreateDBTable = true;

  var $deploySQL = array(
    // All Times
    array(
      'version' => 'rtypeStory#1.0',
      'executeOnGenerateModelToo' => true,
      'sql'=> '
        DROP VIEW IF EXISTS geozzy_allstories_index;
        CREATE VIEW geozzy_allstories_index AS
					SELECT
						storyStep.id,
						
						storyStep.rTypeId,
						storyStep.user,
						storyStep.published,
						
						{multilang: storyStep.title_$lang, }
						{multilang: storyStep.shortDescription_$lang, }
						{multilang: storyStep.mediumDescription_$lang, }
						{multilang: storyStep.content_$lang, }
						
						storyStep.image,
						storyStep.loc,
						storyStep.defaultZoom,
						storyStep.externalUrl,
						
						
						{multilang: storyStep.headKeywords_$lang, }
						{multilang: storyStep.headDescription_$lang, }
						{multilang: storyStep.headTitle_$lang, }
						
						storyStep.timeCreation,
						storyStep.timeLastUpdate,
						storyStep.timeLastPublish,
						storyStep.countVisits,
						
						geozzy_resource_rext_storystep.storystepResource as relatedResource,
						geozzy_resource_rext_storystep.storystepLegend as legend,
						
						geozzy_collection_resources.weight as weight,
						story.idName as storyName
					FROM geozzy_resource as storyStep

					RIGHT JOIN geozzy_resource_rext_storystep
					ON geozzy_resource_rext_storystep.resource = storyStep.id

					RIGHT JOIN geozzy_collection_resources
					ON geozzy_collection_resources.resource = storyStep.id

					RIGHT JOIN geozzy_collection
					ON geozzy_collection.id = geozzy_collection_resources.collection

					RIGHT JOIN geozzy_resource_collections
					ON geozzy_collection.id = geozzy_resource_collections.collection

					RIGHT JOIN geozzy_resource as story
					ON geozzy_resource_collections.resource = story.id

					where storyStep.published = 1
					ORDER BY weight;

      '
    )
  );



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
    'shortDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'mediumDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'content' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),



    'headKeywords' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'headDescription' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    'headTitle' => array(
      'type' => 'VARCHAR',
      'multilang' => true
    ),
    
/*
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
    )*/
  );

  static $extraFilters = array(
    'ids' => ' id IN (?)',
    'updatedfrom' => ' timeLastUpdate > FROM_UNIXTIME(?) '

  );


  function __construct( $datarray= array(), $otherRelObj = false ) {
    parent::__construct( $datarray, $otherRelObj );
  }

}
