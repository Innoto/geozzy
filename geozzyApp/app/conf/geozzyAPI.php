<?php

define( 'GEOZZY_API_ACTIVE', true);
define( 'GEOZZY_API_URL_DIR', 'api');
define( 'GEOZZY_API_CACHE_QUERY', false );


global $GEOZZY_API_DOC_URLS;

$GEOZZY_API_DOC_URLS = array(
	// Geozzy Core
  array(
    'path' => '/geozzy',
    'description' => 'Geozzy Core'
  ),
  array(
    'path' => '/geozzy',
    'description' => 'Geozzy Admin'
  ),  
  array(
    'path' => '/geozzy',
    'description' => 'Geozzy explorer'
  ),
  array(
    'path' => '/geozzy',
    'description' => 'Participation module'
  )
);