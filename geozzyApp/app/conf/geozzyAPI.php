<?php

define( 'GEOZZY_API_ACTIVE', true);
define( 'GEOZZY_API_CACHE_QUERY', false );


global $GEOZZY_API_DOC_URLS;

$GEOZZY_API_DOC_URLS = array(
	// Geozzy Core
  array(
    'path' => '/admin/adminCategories.json',
    'description' => 'Admin Categories'
  ),
  array(
    'path' => '/admin/adminCategoryterms.json',
    'description' => 'Admin CategoryTerms'
  ),
  array(
    'path' => '/resources.json',
    'description' => 'Core Resource'
  ),
  array(
    'path' => '/resourceTypes.json',
    'description' => 'Resource Types'
  ), 
  array(
    'path' => '/categoryList.json',
    'description' => 'Category List'
  ),
  array(
    'path' => '/categoryTerms.json',
    'description' => 'CategoryTerms by category'
  ),
  array(
    'path' => '/topicList.json',
    'description' => 'Topics'
  )  
);