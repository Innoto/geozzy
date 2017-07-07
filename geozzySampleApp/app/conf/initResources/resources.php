<?php
$initResources = array();



// PORTADA
$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
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
  'viewType' => 'viewAppHome',
  'img' => 'fototest1.jpg'
);


// About
$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'about',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => '¿Qué es Proyecta?',
    'gl' => 'Qué é Proyecta?'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/about_es.html'),
    'gl' => file_get_contents(dirname(__FILE__).'/html/about_gl.html')
  ),
  'urlAlias' => array(
    'es' => '/conocenos',
    'gl' => '/conocenos'
  ),
  'viewType' => 'viewAppAbout',
  'img' => 'que_es_proyecta.jpg'
);


$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
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
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
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
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
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


