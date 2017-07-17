<?php

global $geozzyTopicsInfo;

$geozzyTopicsInfo = [];

$geozzyTopicsInfo['ProfesoresConvocatoria'] = array(
  'name' => array(
    'es' => 'Convocatoria'
  ),
  'resourceTypes' => array(
    'rtypeAppConvocatoria' => [ 'weight' => 10 ]
  ),
  'weight' => 10
);

$geozzyTopicsInfo['ProfesoresInscripcion'] = array(
  'name' => array(
    'es' => 'Profesores Inscripción'
  ),
  'resourceTypes' => array(
    'rtypeAppProfesor' => [ 'weight' => 10 ]
  ),
  'defaultTaxonomytermIdName' => 'borrador',
  'taxonomies' => array(
    'idName'=> 'estados_profesores',
    'name' => array(
      'es' => 'Estado'
    ),
    'editable' => 1,
    'nestable' => 0,
    'sortable' => 1,
    'initialTerms' => array(
      array(
        'idName'=>'borrador',
        'name' => array(
          'es' => 'Borrador'
        ),
        'weight' => 10
      ),
      array(
        'idName'=>'enviado',
        'name' => array(
          'es' => 'Enviado'
        ),
        'weight' => 20
      ),
      array(
        'idName'=>'preseleccionado',
        'name' => array(
          'es' => 'Preseleccionado'
        ),
        'weight' => 30
      ),
      array(
        'idName'=>'seleccionado',
        'name' => array(
          'es' => 'Seleccionado'
        ),
        'weight' => 40
      )
    ),
  ),
  'weight' => 20
);

$geozzyTopicsInfo['ProfesoresEntrevista'] = array(
  'name' => array(
    'es' => 'Entrevistas'
  ),
  'resourceTypes' => array(
    'rtypeAppEntrevista' => [ 'weight' => 10 ]
  ),
  'weight' => 30
);

