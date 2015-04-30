<?php

define( 'GEOZZY_API_ACTIVE', true);
define( 'GEOZZY_API_URL_DIR', 'api');
define( 'GEOZZY_API_CACHE_QUERY', false );


global $GEOZZY_API_DOC_URLS;

$GEOZZY_API_DOC_URLS = array(
	// Geozzy Core
  array(
    'path' => '/core',
    'description' => 'Geozzy Core'
  ),
  array(
    'path' => '/admin',
    'description' => 'Geozzy Admin'
  )//,  
  /*array(
    'path' => '/core',
    'description' => 'Geozzy explorer'
  ),
  array(
    'path' => '/core',
    'description' => 'Participation module'
  )*/
);