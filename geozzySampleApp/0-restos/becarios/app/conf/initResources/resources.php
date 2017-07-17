<?php
$initResources = array();



// PORTADA
$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'home',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Portada'
  ),
  'shortDescription' => array(
    'es' => ''
  ),
  'urlAlias' => array(
    'es' => '/'
  ),
  'viewType' => 'viewAppHome',
  'img' => 'fototest1.jpg'
);


// PAGINAS nice2meetyou
$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'nice2meetyouHome',
  'rType' => 'rtypeNice2meetyouPage',
  'title' => array(
    'es' => 'Página Nice2meetyou Inicio'
  ),
  'shortDescription' => array(
    'es' => 'Página Nice2meetyou Inicio'
  ),
  'urlAlias' => array(
    'es' => '/nice2meetyou'
  ),
  'viewType' => 'viewAppNice2meetyouHome',
  'img' => 'becarios3.jpg'
);

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'nice2meetyouBecarios',
  'rType' => 'rtypeNice2meetyouPage',
  'title' => array(
    'es' => 'Página Nice2meetyou Becarios'
  ),
  'shortDescription' => array(
    'es' => 'Página Nice2meetyou Becarios'
  ),
  'urlAlias' => array(
    'es' => '/nice2meetyou/becarios'
  ),
  'viewType' => 'viewAppNice2meetyouBecarios',
  'img' => 'becarios2.jpg'
);

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'nice2meetyouAvisos',
  'rType' => 'rtypeNice2meetyouPage',
  'title' => array(
    'es' => 'Página Nice2meetyou Avisos'
  ),
  'shortDescription' => array(
    'es' => 'Página Nice2meetyou Avisos'
  ),
  'urlAlias' => array(
    'es' => '/nice2meetyou/avisos'
  ),
  'viewType' => 'viewAppNice2meetyouAvisos',
  'img' => 'becarios1.jpg'
);

// PAGINAS: AVISO LEGAL / PRIVACIDAD / COOKIES
$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'legal',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Aviso Legal'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/legal.html')
  ),
  'urlAlias' => array(
    'es' => '/aviso-legal'
  ),
  'viewType' => 'viewAppGeneric',
  'img' => 'privacidad.jpg'
);

//
$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'privacidad',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Política de Privacidad'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/privacidad.html')
  ),
  'urlAlias' => array(
    'es' => '/politica-privacidad'
  ),
  'viewType' => 'viewAppGeneric',
  'img' => 'privacidad.jpg'
);

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'cookies',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Política de uso de Cookies'
  ),
  'content' => array(
    'es' => file_get_contents(dirname(__FILE__).'/html/cookies.html')
  ),
  'urlAlias' => array(
    'es' => '/politica-cookies'
  ),
  'viewType' => 'viewAppGeneric',
  'img' => 'privacidad.jpg'
);

















