<?php

/*
  EXAMPLE tpl:
    array(
      'idName' => 'tplApp{CCCCCC}',
      'tpl' => 'rExtViewAltAppCCCCCC.tpl', // rExtViewAltApp{CCCCCC}.tpl si no se indica
      'module' => 'rextView', // rextView si no se indica, false para buscar en App
      'name' => array(
        'en' => 'Use CCCCCC tpl',
        'es' => 'Usar el tpl CCCCCC',
        'gl' => 'Usar o tpl CCCCCC'
      )
    )

  EXAMPLE view:
    array(
      'idName' => 'viewApp{CCCCCC}',
      'view' => 'RExtViewAltAppCCCCCC', // RExtViewAltApp{CCCCCC} si no se indica
      'module' => 'rextView', // rextView si no se indica, false para buscar en App
      'name' => array(
        'en' => 'Use CCCCCC class',
        'es' => 'Usar la clase CCCCCC',
        'gl' => 'Usar a clase CCCCCC'
      )
    )
*/


$rExtViewAppInitialTerms = array();

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppHome',
  'view' => 'PageHome',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => 'Portada',
    'gl' => 'Portada'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppGeneric',
  'view' => 'PageGeneric',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => 'Página',
    'gl' => 'Páxina'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppBlog',
  'view' => 'PageBlog',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => 'Blog',
    'gl' => 'Blogue'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppAbout',
  'view' => 'PageAbout',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => '¿Qué es Proyecta?',
    'gl' => 'Qué é Proyecta?'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppPeople',
  'view' => 'PagePeople',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => 'Colaboradores',
    'gl' => 'Colaboradores'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppHomeEduca',
  'view' => 'PageEduca',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => 'Portada Educa',
    'gl' => 'Portada Educa'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppListEduca',
  'view' => 'PageEducaList',
  'module' => false, // La clase view indicada se encuentra en la App
  'name' => array(
    'es' => 'Listado Educa',
    'gl' => 'Listado Educa'
  )
);
