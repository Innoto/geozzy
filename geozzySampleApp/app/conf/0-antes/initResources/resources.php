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

// QUE ES PROYECTA

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

// BLOG

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'blog',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Blog',
    'gl' => 'Blogue'
  ),
  'shortDescription' => array(
    'es' => 'Blog',
    'gl' => 'Blogue'
  ),
  'urlAlias' => array(
    'es' => '/newblog',
    'gl' => '/newblogue'
  ),
  'viewType' => 'viewAppBlog'
);

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'educa',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Educa',
    'gl' => 'Educa'
  ),
  'urlAlias' => array(
    'es' => '/recursos-educativos',
    'gl' => '/recursos-educativos'
  ),
  'viewType' => 'viewAppHomeEduca'
);

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'educaList',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Educa Listado',
    'gl' => 'Educa Listado'
  ),
  'urlAlias' => array(
    'es' => '/recursos-educativos-list',
    'gl' => '/recursos-educativos-list'
  ),
  'viewType' => 'viewAppListEduca'
);

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'colaboradores',
  'rType' => 'rtypePage',
  'title' => array(
    'es' => 'Colaboradores',
    'gl' => 'Colaboradores'
  ),
  'urlAlias' => array(
    'es' => '/colaboradores',
    'gl' => '/colaboradores'
  ),
  'viewType' => 'viewAppPeople'
);

// PORTADAS DE CADA TIPO DE PARTICIPACION

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'inspiratics',
  'rType' => 'rtypeAppPageStatus',
  'title' => array(
    'es' => 'InspiraTICs',
    'gl' => 'InspiraTICs'
  ),
  'shortDescription' => array(
    'es' => 'InspiraTICs: Un espacio inspirador en el que compartir inquietudes didácticas y experiencias reales',
    'gl' => 'InspiraTICs: Un espazo inspirador no que compartir inquietudes didácticas e experiencias reais'
  ),
  // 'viewType' => 'viewAppInspiratics',
  'urlAlias' => array(
    'es' => '/inspiratics',
    'gl' => '/inspiratics'
  ),
  'img' => 'fototest2.jpg',
  'terms' => array()
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



// INSPIRATICS

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'rType' => 'rtypeAppInspiratics',
  'title' => array(
    'es' => 'Primer InspiraTICs',
    'gl' => 'Primeiro InspiraTICs'
  ),
  'shortDescription' => array(
    'es' => 'Primer InspiraTICs: Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'gl' => 'Primeiro InspiraTICs: Curabitur sit amet sem at sem ornare mollis. Curabitur fermentum.'
  ),
  'urlAlias' => array(
    'es' => '/inspiratics/Primer',
    'gl' => '/inspiratics/Primeiro'
  ),
  'img' => 'fototest5.jpg',
  'terms' => array(
    'appInspiraticsHeader' => 'stream',
    'appInspiraticsStatus' => 'directo'
  )
);



// Concursos Proyecta D+I

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'rType' => 'rtypeAppContest',
  'title' => array(
    'es' => 'Primer concurso Proyecta D+I',
    'gl' => 'Primeiro concurso Proyecta D+I'
  ),
  'shortDescription' => array(
    'es' => 'Primer concurso Proyecta D+I: Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'gl' => 'Primeiro concurso Proyecta D+I: Curabitur sit amet sem at sem ornare mollis. Curabitur fermentum.'
  ),
  'urlAlias' => array(
    'es' => '/concurso/PrimerDI',
    'gl' => '/concurso/PrimeiroDI'
  ),
  'img' => 'fototest9.jpg'
);



// Proyecta Innovación

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'rType' => 'rtypeAppContest',
  'title' => array(
    'es' => 'Primer proyecta Innovación',
    'gl' => 'Primeiro proyecta Innovación'
  ),
  'shortDescription' => array(
    'es' => 'Primer proyecta Innovación: Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    'gl' => 'Primeiro proyecta Innovación: Curabitur sit amet sem at sem ornare mollis. Curabitur fermentum.'
  ),
  'urlAlias' => array(
    'es' => '/concurso/PrimerInnova',
    'gl' => '/concurso/PrimeiroInnova'
  ),
  'img' => 'fototest13.jpg',
  'terms' => array(
    'appContestType' => 'PrInnovacion',
    'appContestStatus' => 'abierto'
  )

);



// CAMPAÑAS

$initResources[] = array(
  'version' =>'Cogumelo#1.0',
  'executeOnGenerateModelToo' => true,
  'idName' => 'TestCampaign',
  'rType' => 'rtypeAppCampaign',
  'title' => array(
    'es' => 'Test de la Campaña',
    'gl' => 'Test da Campaña'
  ),
  'shortDescription' => array(
    'es' => 'Probando la Campaña',
    'gl' => 'Probando a Campaña'
  ),
  'urlAlias' => array(
    'es' => '/testcampana',
    'gl' => '/testcampana'
  ),
  'img' => 'fototest17.jpg'
);
