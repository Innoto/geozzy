<?php
geozzy::load( 'controller/RExtController.php' );

class RExtEventCollectionController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextEventCollection(), 'rExtEventCollection_' );
  }


  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
    $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
    if( $termsGroupedIdName !== false ) {
      foreach( $this->taxonomies as $tax ) {
        if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
          if( !$rExtData ) {
            $rExtData = array();
          }
          $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
        }
      }
    }

    $collection = new CollectionModel( );
    $resourceCollectionsModel = new ResourceCollectionsModel();
    $resourceCollectionsList = $resourceCollectionsModel->listItems(
      array('filters' => array('resource' => $resId)) );

    $elemId = false;
    $eventCol = false;
    while($resCol = $resourceCollectionsList->fetch()){
      $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection'))))->fetch();
      if($typecol->getter('collectionType')==='event'){
        $elemId = $typecol->getter('id');
      }
    }

    if ($elemId){
      $collectionResourcesModel = new CollectionResourcesModel();
      $collectionResourceList = $collectionResourcesModel->listItems(
        array('filters' => array('collection' => $elemId), 'order' => array('weight' => 1))
      );

      $resIds = array();
      while($res = $collectionResourceList->fetch()){
        $resIds[] = $res->getter('resource');
      }

      $rExtData['events'] = $resIds;
    }

    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    $form_values = $form->getValuesArray();
    $filterRTypeParent = $form_values['rTypeIdName'];

    $filter = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$filterRTypeParent.':eventos' );

    $resourceModel = new ResourceModel();
    $rtypeControl = new ResourcetypeModel();

    $rtypeArray = $rtypeControl->listItems(array( 'filters' => array( 'idNameExists' => $filter )));
    $rtypeArraySize = $rtypeControl->listCount(array( 'filters' => array( 'idNameExists' => $filter )));

    if($this->defResCtrl->resObj!=false){
      $resId = $this->defResCtrl->resObj->getter('id');
    }
    else{
      $resId='null';
    }

    $varConditions = '';
    $i = 1;
    while( $res = $rtypeArray->fetch() ){
      if($i === $rtypeArraySize){
        $varConditions = $varConditions . $res->getter('id').';'.$resId.';'.'event';
      }
      else{
        $varConditions = $varConditions . $res->getter('id').',';
      }
      $i = $i+1;
    }

    $collectionRtypeResources = new CollectionTypeResourcesModel();
    $collectionRtypeResourcesList = $collectionRtypeResources->listItems(
      array('filters'=>array('conditionsRtypeCollection' => $varConditions))
    );
    $resOptions = array();
    while($res = $collectionRtypeResourcesList->fetch()){
      $resOptions[] = array(
        'value' => $res->getter( 'id' ),
        'text' => $res->getter( 'title', Cogumelo::getSetupValue('lang:default') )
      );
    }

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'rextEventCollectionView' => array(
        'params' => array( 'label' => __( 'Event collection view' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'rextEventCollectionView' )
        )
      ),
      'rextEventCollectionFilter' => array(
        'params' => array( 'label' => __( 'Event collection filter' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'rextEventCollectionFilter' )
        )
      ),
      'events' => array(
        'params' => array(
          'id' => 'collEvents',
          'class' => 'cgmMForm-order',
          'label' => __( 'Events' ),
          'type' => 'select',
          'data-col-type' => 'event',
          'multiple' => true, 'options'=> $resOptions
        )
      ),
      'addEvents' => array(
        'params' => array(
          'id' => 'addEvents', 'type' => 'button', 'value' => __( 'Add Event' )
        )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Validaciones extra
    $form->removeValidationRule( 'rExtEventCollection_events', 'inArray' );


    // Si es una edicion, añadimos el ID y cargamos los datos
     $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );

     if( $valuesArray ) {
       $valuesArray = $this->prefixArrayKeys( $valuesArray );
       $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );

       // Limpiando la informacion de terms para el form
       if( $this->taxonomies ) {
         foreach( $this->taxonomies as $tax ) {
           $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
           if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
             $taxFieldValues = array();
             foreach( $valuesArray[ $taxFieldName ] as $value ) {
               $taxFieldValues[] = ( is_array( $value ) ) ? $value[ 'id' ] : $value;
             }
             $valuesArray[ $taxFieldName ] = $taxFieldValues;
           }
         }
       }
       $form->loadArrayValues( $valuesArray );
     }

    // Add RExt info to form
    foreach( $fieldsInfo as $fieldName => $info ) {
      if( isset( $info[ 'translate' ] ) && $info[ 'translate' ] ) {
        $rExtFieldNames = array_merge( $rExtFieldNames, $form->multilangFieldNames( $fieldName ) );
      }
      else {
        $rExtFieldNames[] = $fieldName;
      }
    }

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form ) {

    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );
    $templates['full']->addClientScript('js/rextEventCollectionAdmin.js', 'rextEventCollection');

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtEventCollectionController: resFormProcess()" );

    $valuesArray = $form->getValuesArray();

    if( !$form->existErrors() ) {

      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
      }

      $newResources = $form->getFieldValue( 'rExtEventCollection_events' );

      $collection = new CollectionModel( );
      $resourceCollectionsModel = new ResourceCollectionsModel();

      // buscamos las colecciones de ese recurso
      $resourceCollectionsCount = $resourceCollectionsModel->listCount(
        array('filters' => array( 'resource' => $resource->getter('id')) ) );
      $resourceCollections = $resourceCollectionsModel->listItems(
        array('filters' => array( 'resource' => $resource->getter('id')) ) );

      $elemId = false;
      if ($resourceCollectionsCount>0){// existe coleccion
        while($resCol = $resourceCollections->fetch()){
          $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection'))))->fetch();

          if($typecol->getter('collectionType')==='event'){
            $elemId = $typecol->getter('id');
            break;
          }
        }
      }

      if (!$elemId && $newResources){ // creamos a coleccion
        $collection->setter('collectionType', 'event');
        $collection->save();
        $elemId = $collection->getter('id');
        $resourceCollection = new ResourceCollectionsModel(array('resource'=>$resource->getter('id'), 'collection' => $collection->getter('id')));
        $resourceCollection->save();
      }
    }

    // Procesamos o listado de recursos asociados
    if( !$form->existErrors()) {
      $oldResources = false;

      if( $newResources !== false && !is_array($newResources) ) {
        $newResources = array($newResources);
      }

      // Si estamos editando, repasamos y borramos recursos sobrantes
      if( isset($elemId) ) {

        $CollectionResourcesModel = new CollectionResourcesModel();

        $collectionResourceList = $CollectionResourcesModel->listItems(
          array('filters' => array('collection' => $elemId)) );

        if( $collectionResourceList ) {

          // estaban asignados antes
          $oldResources = array();
          while( $oldResource = $collectionResourceList->fetch() ){

            $oldResources[ $oldResource->getter('resource') ] = $oldResource->getter('id');
            if( $newResources === false || !in_array( $oldResource->getter('resource'), $newResources ) ) {
              $oldResource->delete(); // desasignar
            }
          }
        }
      }

      $affectsDependences = false;
      // Creamos-Editamos todas las relaciones con los recursos
      if( $newResources !== false ) {
        $affectsDependences = true;
        $weight = 0;
        foreach( $newResources as $resource ) {
          $weight++;
          if( $oldResources === false || !isset( $oldResources[ $resource ] ) ) {
            $collection->setterDependence( 'id',
              new CollectionResourcesModel( array( 'weight' => $weight,
                'collection' => $elemId, 'resource' => $resource)) );
          }
          else {
            $collection->setterDependence( 'id',
              new CollectionResourcesModel( array( 'id' => $oldResources[ $resource ],
                'weight' => $weight, 'collection' => $elemId, 'resource' => $resource))
            );
          }
        }
      }
      $collection->save( array( 'affectsDependences' => $affectsDependences ));
    }
  }


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )


  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "RExtEventCollectionController: getViewBlockInfo( $resId )" );

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    // Obtenemos el rtype de los microeventos
    $rtypeModel = new ResourcetypeModel();
    $rtypeEventId = $rtypeModel->listItems(array('filters' => array('idNameExists' => 'rtypeEvent')))->fetch()->getter('id');

    if( $rExtViewBlockInfo['data'] ) {

      $resId = $this->defResCtrl->resObj->getter('id');

      if(isset($rExtViewBlockInfo['data']['events'])){

        $eventIdList = $rExtViewBlockInfo['data']['events'];

        $eventIdsArray = $eventIdsArrayFinal =array();
        foreach( $eventIdList as $eventId){
          $eventIdsArray[] = $eventId;
        }

        foreach ($rExtViewBlockInfo['data']['rextEventCollectionFilter'] as $eventFilterTerm){
          $eventFilterSelectedTerm = $eventFilterTerm;
        }

        $eventModel =  new EventModel();
        $eventList = $eventModel->listItems( array( 'filters' => array( 'inId' => $eventIdsArray), 'order' => array( 'initDateFirst' => 1 ) ));

        /* Establecemos locale para obtener las fechas en el idioma actual */
        global $C_LANG;
        setlocale (LC_TIME, Cogumelo::getSetupValue( 'lang:available:'.$C_LANG.':i18n' ));

        /* Cargamos los datos de la extensión */
        while( $event = $eventList->fetch() ){

          $eventInfo = $event->getAllData('onlydata');

          $initDate = new DateTime($eventInfo['initDateFirst']);
          $eventDate = $initDate->format('Y').$initDate->format('m').$initDate->format('d');
          $today = date('Ymd');
          if ($eventFilterSelectedTerm['idName'] == 'nextEvents' && strcmp($eventDate,$today)<0){
            continue;
          }
          $eventIdsArrayFinal[] = $event->getter('resource');
          $eventCollection[$event->getter('resource')]['event'] = $eventInfo;

          $relatedResourceAlias = $this->defResCtrl->getUrlAlias($eventInfo['relatedResource']);
          if (isset($eventInfo['relatedResource']) && $eventInfo['relatedResource']!=0){
            $eventCollection[$event->getter('resource')]['event']['relatedResource'] = $relatedResourceAlias;
          }

          $eventCollection[$event->getter('resource')]['event']['formatedDate']['initDateFirst'] = $initDate->format('Ymd');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['j'] = $initDate->format('j');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['l'] = strftime('%A', $initDate->format('U'));
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['m'] = $initDate->format('m');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['F'] = strftime('%B', $initDate->format('U'));
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['Y'] = $initDate->format('Y');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['time'] = $initDate->format('H:i');
        }

        /* Cargamos los datos básicos de recurso que necesitamos */
        $resourceModel =  new ResourceModel();
        $resourceList = $resourceModel->listItems( array( 'filters' => array( 'inId' => $eventIdsArrayFinal) ));
        while( $resource = $resourceList->fetch() ){
          if ($resource->getter('rTypeId') != $rtypeEventId){ // No es un microevento
            $eventCollection[$resource->getter('id')]['resource']['urlAlias'] = $this->defResCtrl->getUrlAlias($resource->getter('id'));
          }
          $eventCollection[$resource->getter('id')]['resource']['title'] = $resource->getter('title');
          $eventCollection[$resource->getter('id')]['resource']['mediumDescription'] = $resource->getter('mediumDescription');
          $eventCollection[$resource->getter('id')]['resource']['image'] = $resource->getter('image');
        }
        $rExtViewBlockInfo['data']['events'] = $eventCollection;
      }

        $taxViewModel =  new TaxonomyViewModel();
        /* Cargamos todos los términos de la taxonomía de visualización de eventCollection*/
        $options = array();
        $taxViewList = $taxViewModel->listItems( array( 'filters' => array( 'taxGroupIdName' => 'rextEventCollectionView' )));
        while( $taxView = $taxViewList->fetch() ){
          $options[ $taxView->getter( 'id' ) ]['idName'] = $taxView->getter( 'idName' );
        }

        foreach ($options as $eventView){
          $templates[$eventView['idName']] = new Template();
          $templates[$eventView['idName']]->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
          $templates[$eventView['idName']]->setTpl( 'rExt'.$eventView['idName'].'Block.tpl', 'rextEventCollection' );
        }

        /* Asignamos al template en uso el template asociado al término actual */
        foreach ($rExtViewBlockInfo['data']['rextEventCollectionView'] as $eventViewTerm){
          $eventViewSelectedTerm = $eventViewTerm;
        }



      $templates['full'] = $templates[$eventViewSelectedTerm['idName']];
      $rExtViewBlockInfo['template'] = $templates;
    }
    return $rExtViewBlockInfo;
  }

} // class RExtEventCollectionController
