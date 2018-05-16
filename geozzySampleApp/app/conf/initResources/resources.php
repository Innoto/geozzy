<?php
$initResources = array();



// PORTADA
$initResources[] = array(
  'idName' => 'home',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Portada',
    'gl' => 'Portada'
  ),
  'shortDescription' => array(
    'es' => 'A stunning window to your amazing world',
    'gl' => 'A stunning window to your amazing world'
  ),
  'urlAlias' => array(
    'es' => '/',
    'gl' => '/'
  ),
  'viewType' => 'viewAppHome'
);


$initResources[] = array(
  'idName' => 'legal',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Aviso Legal',
    'gl' => 'Aviso Legal'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/legal_es.html'),
    'gl' => file_get_contents(dirname(__FILE__).'/html/legal_gl.html')
  ),
  'urlAlias' => array(
    'es' => '/aviso-legal',
    'gl' => '/aviso-legal'
  ),
  'viewType' => 'viewAppGeneric',
  'img' => 'privacidad.jpg',
);


$initResources[] = array(
  'idName' => 'privacidad',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Política de Privacidad',
    'gl' => 'Política de Privacidade'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/privacidad_es.html'),
    'gl' => file_get_contents(dirname(__FILE__).'/html/privacidad_gl.html')
  ),
  'urlAlias' => array(
    'es' => '/politica-privacidad',
    'gl' => '/politica-privacidade'
  ),
  'viewType' => 'viewAppGeneric',
  'img' => 'privacidad.jpg',
);


$initResources[] = array(
  'idName' => 'cookies',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Política de uso de Cookies',
    'gl' => 'Política de uso de Cookies'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/cookies_es.html'),
    'gl' => file_get_contents(dirname(__FILE__).'/html/cookies_gl.html')
  ),
  'urlAlias' => array(
    'es' => '/politica-cookies',
    'gl' => '/politica-uso-cookies'
  ),
  'viewType' => 'viewAppGeneric',
  'img' => 'privacidad.jpg',
);
