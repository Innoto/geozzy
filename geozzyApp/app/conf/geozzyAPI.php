<?php

define( 'GEOZZY_API_ACTIVE', true);
define( 'GEOZZY_API_CACHE_QUERY', false );


global $GEOZZY_API_DOC_URLS;

$GEOZZY_API_DOC_URLS = array(
	// Geozzy Core
  array(
    'path' => '/core',
    'description' => 'Core Resource'
  ),
  array(
    'path' => '/admin/categories.json',
    'description' => 'Admin Categories'
  ),
  array(
    'path' => '/admin/categoryterms.json',
    'description' => 'Admin CategoryTerms'
  )

  //,  
  /*array(
    'path' => '/core',
    'description' => 'Geozzy explorer'
  ),
  array(
    'path' => '/core',
    'description' => 'Participation module'
  )*/
);