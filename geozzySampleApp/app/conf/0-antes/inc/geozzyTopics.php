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


class geozzyTopicsTableExtras {
  public function ProfesoresInscripcion( $urlParamsList,  $tablaAntigua, $tribunal) {

    $selectOptions = false;
    include( Cogumelo::GetSetupValue('setup:appBasePath').'/conf/inc/appFormSelects.php' );
    $this->selectOptions = $formSelects;

    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('topic:list', 'admin:full');
    $topicId = $urlParamsList['topic'];

    table::autoIncludes();
    $resource = new AppProfesorViewModel();
    $resourcetype =  new ResourcetypeModel();

    $resourcetypelist = $resourcetype->listItems( array( 'filters' => array( 'intopic' => $topicId ) ) )->fetchAll();
    $tiposArray = array();
    foreach ($resourcetypelist as $typeId => $type){
      $tiposArray[$typeId] = $typeId;
    }

    $tabla = new TableController( $resource );

    $tabla->setRowsEachPage(15);
    // set id search reference.
    $tabla->setSearchRefId('find');

    $delete = $useraccesscontrol->checkPermissions( array('resource:delete'), 'admin:full');
    if($delete){
      $tabla->setActionMethod(__('Delete'), 'delete', 'deleteResource( $rowId )');
    }

    // set list Count methods in controller
    $tabla->setListMethodAlias('listItems');
    $tabla->setCountMethodAlias('listCount');

    // set Urls
    if( $tribunal === true ){
      $tabla->setEachRowUrl('"/tribunal#resource/show/id/".$rowId');
      $tabla->setNewItemUrl('/admin#resource/create');
    }
    else{
      $tabla->setEachRowUrl('"/admin#resource/edit/id/".$rowId."/topic/'.$topicId.'"');
      $tabla->setNewItemUrl('/admin#resource/create');
    }
    // Filtrar por temática
    $userSession = $useraccesscontrol->getSessiondata();
    if($userSession && in_array('resource:mylist', $userSession['permissions'])){
      $filters = array( 'intopic'=> $topicId, 'inRtype'=>$tiposArray, 'user' => $userSession['data']['id'] );
    }else{
      $filters =  array('intopic'=> $topicId, 'inRtype'=>$tiposArray );
    }

    $tabla->setDefaultFilters($filters);

    // Nome das columnas
    $tabla->setCol ('id', 'ID' );
    $tabla->setColClasses( 'id', 'hidden-xs' ); // hide id in mobile version
    // $tabla->setCol('title_convocatoria', __('Convocatoria'));
    // $tabla->setCol('fechaPresentado', __('Fecha presentado'));
    $tabla->setCol( 'hashId', __('Exp') );
    $tabla->setCol( 'dni', __('DNI/NIE') );
    $tabla->setCol( 'apellidos', __('Apellidos') );
    $tabla->setCol( 'nombre', __('Nombre') );
    $tabla->setCol( 'termProfesorCurso', __('Edición') );
    $tabla->colRule( 'termProfesorCurso', '/^(\d+-\d+)\s.+$/', '$1', true );
    $tabla->setCol( 'termProfesorUniversidad', __('Universidad') );
    $tabla->setCol( 'provinciaCentro', __('Provincia') );
    foreach( $this->selectOptions['provincia'] as $idProvincia => $provincia ) {
      $tabla->colRule( 'provinciaCentro', '/^'.$idProvincia.'$/', $provincia );
    }
    $tabla->setCol( 'notaMaster', __('Nota máster') );
    $tabla->setCol( 'termProfesorAreaConocimiento', __('Área') );
    $tabla->setCol( 'notaTitulacion', __('Nota título') );
    $tabla->setCol( 'nivelIngles', __('Nivel inglés') );
    foreach( $this->selectOptions['nivelIngles'] as $idNivelIngles => $nivelIngles ) {
      $tabla->colRule( 'nivelIngles', '/^'.$idNivelIngles.'$/', $nivelIngles );
    }

    // Topic have taxonomy states
    $topicmodel =  new TopicModel();
    $topic = $topicmodel->listItems(array("filters" => array("id" => $topicId)))->fetch();

    if( $topic->getter('taxgroup') ) {

      $taxonomyterms = (new TaxonomytermModel())->listItems(array("filters" => array("taxgroup" =>  $topic->getter('taxgroup') )))->fetchAll();
      $taxonomygroup = (new TaxonomygroupModel())->listItems(array("filters" => array("id" =>  $topic->getter('taxgroup') )))->fetch();

      $taxonomygroupName = $taxonomygroup->getter('name', Cogumelo::getSetupValue( 'lang:default' ));

      $tabla->setCol('topicTaxonomyterm', $taxonomygroupName );
      // $tabla->setColClasses('topicTaxonomyterm', 'hidden-sm'); // hide in medium screen version

      // Contido especial
      if( is_array($taxonomyterms) && count($taxonomyterms) > 0 ) {
        // separador
        $tabla->setActionSeparator();
        $filterStates = array();
        foreach($taxonomyterms as $term) {
          $tabla->colRule('topicTaxonomyterm', '#'.$term->getter('id').'#', $term->getter('name', Cogumelo::getSetupValue( 'lang:default' ) ));
          $tabla->setActionMethod(
            $taxonomygroupName. ' ('.$term->getter('name', Cogumelo::getSetupValue( 'lang:default' )). ')' ,
            'changeTaxonomytermTo'.$term->getter('idName'),
            'updateTopicTaxonomy( $rowId, '.$topicId.', '.$term->getter('id').')'
          );
          $filterStates[$term->getter('id')] = $term->getter('name', Cogumelo::getSetupValue( 'lang:default' ));
        }

        // filtro extra de estado
        $filterStates['*'] = __('All');
        $tabla->setExtraFilter( 'inTopicTaxonomyterm',  'combo', __('Estado'), $filterStates, '*' );
      }
    }

    $tabla->setCol( 'entrevista', __('Entrevista') );
    $tabla->colRule( 'entrevista', '#1#', __('Seleccionado') );
    $tabla->colRule( 'entrevista', '#0#', __('No seleccionado') );
    $tabla->setCol( 'notaPreseleccionBase', __('Nota pre.') );


    // Filtro Convocatoria
    $filterConvocatoria['*'] = __('All');
    $topicFilter = (new ResourceTopicModel())->listItems(
      array(
        'affectsDependences' => array('TopicModel', 'ResourceModel'),
        'filters' => array( 'TopicModel.idName' =>  'ProfesoresConvocatoria' ),
        'joinType' => 'INNER'
      )
    );
    while( $rt = $topicFilter->fetch() ) {
    if( sizeof( $resourceInFilterTopic = $rt->getterDependence('resource','ResourceModel') )>0 ) {
        $filterConvocatoria[ $resourceInFilterTopic[0]->getter('id')] = $resourceInFilterTopic[0]->getter('title');
      }
    }
    $tabla->setTabs('inConvocatoria', $filterConvocatoria , '*');


    // $tabla->setColToExport( 'fechaPresentado', __('Fecha presentado') );
    $tabla->setColToExport( 'email', __('E-mail') );
    $tabla->setColToExport( 'pasaporte', __('Pasaporte') );
    $tabla->setColToExport( 'direccion', __('Dirección') );
    $tabla->setColToExport( 'ciudad', __('Ciudad') );
    $tabla->setColToExport( 'provincia', __('Provincia') );
    foreach( $this->selectOptions['provincia'] as $idProvincia => $provincia ) {
      $tabla->colExportRule( 'provincia', '/^'.$idProvincia.'$/', $provincia );
    }
    $tabla->setColToExport( 'cp', __('Código Postal') );
    $tabla->setColToExport( 'termProfesorPais', __('Nacionalidad') );
    $tabla->setColToExport( 'termProfesorSexo', __('Sexo') );
    $tabla->setColToExport( 'telefono1', __('Teléfono 1') );

    $tabla->setColToExport( 'otraUniversidad', __('Otra univesidad') );
    $tabla->setColToExport( 'termProfesorEspecialidad', __('Especialidad') );
    $tabla->setColToExport( 'otraEspecialidad', __('Otra especialidad') );
    $tabla->setColToExport( 'termProfesorTipoCentro', __('Tipo de centro') );
    $tabla->setColToExport( 'poblacionCentro', __('Población del centro') );
    $tabla->setColToExport( 'correoCentro', __('E-mail del centro') );

    $tabla->setColToExport( 'titulacion', __('Titulación') );
    $tabla->setColToExport( 'universidadTitulacion', __('Universidad dónde estudió') );

    $tabla->setColToExport( 'tituloIngles', __('Título de Inglés') );
    foreach( $this->selectOptions['tituloIngles'] as $idTituloIngles => $tituloIngles ) {
      $tabla->colExportRule( 'tituloIngles', '/^'.$idTituloIngles.'$/', $tituloIngles );
    }

    $tabla->setColToExport( 'tematicaDefensa', __('Temática de la defensa') );
    $tabla->setColToExport( 'observaciones', __('Observaciones') );

    $tabla->setColToExport( 'notaPreseleccionPonderada', __('Nota preseleccionado ponderada') );

    // $tabla->setColToExport( 'certificadoMaster', __('Certificado máster') );
    // $tabla->setColToExport( 'noCertificado', __('No certificado') );
    // $tabla->setColToExport( 'certificadoTitulo', __('Certificado título') );
    // $tabla->setColToExport( 'certificadoIngles', __('Certificado Inglés') );

    return $tabla;
  }

  public function ProfesoresEntrevista( $urlParamsList,  $tabla, $tribunal) {

    $tabla->__construct( new AppEntrevistaViewModel() );

    $tabla->unsetCol( 'rTypeId' );
    $tabla->unsetCol( 'published' );
    $tabla->unsetCol( 'title_es' );

    $tabla->setCol( 'idEntrevistado', 'ID Entrevistado' );
    $tabla->colRule( 'idEntrevistado', '/^(0+|\s*)$/', '-' );
    $tabla->setCol( 'nombreEntrevistado', 'Entrevistado' );
    $tabla->colRule( 'nombreEntrevistado', '/^[\s]*$/', '-' );
    $tabla->setCol( 'termProfesorDia', 'Dia' );
    $tabla->setCol( 'termProfesorTurno', 'Turno' );
    $tabla->setCol( 'termProfesorHora', 'Hora' );
    $tabla->setCol( 'termProfesorTribunal', 'Tribunal' );

    $tabla->setColClasses( 'id', 'hidden-xs' );

    return $tabla;
  }
}
