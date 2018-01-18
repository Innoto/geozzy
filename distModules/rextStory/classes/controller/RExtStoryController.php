<?php
geozzy::load( 'controller/RExtController.php' );

class RExtStoryController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextStory(), 'rExtStory_' );
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
    if( $termsGroupedIdName !== false && $this->taxonomies) {
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
      array('filters' => array('resource' => $resId), 'cache' => $this->cacheQuery) );

    $elemId = false;
    $eventCol = false;
    while($resCol = $resourceCollectionsList->fetch()){
      $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection')), 'cache' => $this->cacheQuery))->fetch();
      if($typecol->getter('collectionType')==='steps'){
        $elemId = $typecol->getter('id');
      }
    }

    if ($elemId){
      $collectionResourcesModel = new CollectionResourcesModel();
      $collectionResourceList = $collectionResourcesModel->listItems(
        array('filters' => array('collection' => $elemId), 'order' => array('weight' => 1), 'cache' => $this->cacheQuery)
      );

      $resIds = array();
      while($res = $collectionResourceList->fetch()){
        $resIds[] = $res->getter('resource');
      }

      $rExtData['steps'] = $resIds;
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

    $filter = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$filterRTypeParent.':pasos' );

    $resourceModel = new ResourceModel();
    $rtypeControl = new ResourcetypeModel();

    $rtypeArray = $rtypeControl->listItems(array( 'filters' => array( 'idNameExists' => $filter ), 'cache' => $this->cacheQuery));
    $rtypeArraySize = $rtypeControl->listCount(array( 'filters' => array( 'idNameExists' => $filter ), 'cache' => $this->cacheQuery));

    $varConditions = '';
    $i = 0;
    while( $res = $rtypeArray->fetch() ){
      if($i+1 !== $rtypeArraySize){
        $varConditions = $varConditions . $res->getter('id').',';
      }
      else{
        $varConditions = $varConditions . $res->getter('id');
      }
      $i = $i+1;
    }

    $collectionRtypeResources = new CollectionTypeResourcesModel();
    $collectionRtypeResourcesList = $collectionRtypeResources->listItems(
      array('filters'=>array('conditionsRtypenotInCollection' => $varConditions), 'cache' => $this->cacheQuery)
    );
    $resOptions = array();
    while($res = $collectionRtypeResourcesList->fetch()){
      $resOptions[] = array(
        'value' => $res->getter( 'id' ),
        'text' => $res->getter( 'title', Cogumelo::getSetupValue('lang:default') )
      );
    }

    $rExtFieldNames = array();

/* resourceCollection class*/
    $fieldsInfo = array(
      'steps' => array(
        'params' => array(
          'label' => __( 'Steps' ),
          'type' => 'select', 'id' => 'collSteps',
          'class' => 'cgmMForm-order',
          'multiple' => true,
          'options'=> $resOptions
        )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Validaciones extra
    $form->removeValidationRule( 'rExtStory_steps', 'inArray' );

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
     $templates['full']->addClientScript('js/rextStoryAdmin.js', 'rextStory');

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
    // error_log( "RExtStoryController: resFormProcess()" );

    $valuesArray = $form->getValuesArray();

    if( !$form->existErrors() ) {
      if ($this->taxonomies){
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
            $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
          }
        }
      }

    //  $newResources = $form->getFieldValue( 'rExtStory_steps' );

      $collection = new CollectionModel( );
      $resourceCollectionsModel = new ResourceCollectionsModel();

      // buscamos las colecciones de ese recurso
      $resourceCollectionsCount = $resourceCollectionsModel->listCount(
        array('filters' => array( 'resource' => $resource->getter('id')), 'cache' => $this->cacheQuery ) );
      $resourceCollections = $resourceCollectionsModel->listItems(
        array('filters' => array( 'resource' => $resource->getter('id')), 'cache' => $this->cacheQuery ) );

      $elemId = false;
      if ($resourceCollectionsCount>0){// existe coleccion
        while($resCol = $resourceCollections->fetch()){
          $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection')), 'cache' => $this->cacheQuery))->fetch();

          if($typecol->getter('collectionType')==='steps'){
            $elemId = $typecol->getter('id');
            break;
          }
        }
      }

      if (!$elemId){ // creamos a coleccion
        $collection->setter('collectionType', 'steps');
        $collection->save();
        $elemId = $collection->getter('id');
        $resourceCollection = new ResourceCollectionsModel(array('resource'=>$resource->getter('id'), 'collection' => $elemId));
        $resourceCollection->save();
      }
    }

    // Procesamos o listado de recursos asociados
    if( !$form->existErrors()) {
      $oldResources = false;
      $newResources = false;

      $collectionResources = new CollectionResourcesModel();
      $collectionResourcesList = $collectionResources->listItems(array('filters' => array('collection'=>$elemId), 'order' => array('weight' => 1), 'cache' => $this->cacheQuery));
      if($collectionResourcesList){
        while($colResource = $collectionResourcesList->fetch()){
          $newResources[] = $colResource->getter('resource');
        }
      }

      // Si estamos editando, repasamos y borramos recursos sobrantes
      if( isset($elemId) ) {

        $CollectionResourcesModel = new CollectionResourcesModel();

        $collectionResourceList = $CollectionResourcesModel->listItems(
          array('filters' => array('collection' => $elemId), 'cache' => $this->cacheQuery) );

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
    // error_log( "RExtStoryController: getViewBlockInfo( $resId )" );
/*
    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    // Obtenemos el rtype de los microeventos
    $rtypeModel = new ResourcetypeModel();
    $rtypeEventId = $rtypeModel->listItems(array('filters' => array('idNameExists' => 'rtypeStoryStep'), 'cache' => $this->cacheQuery))->fetch()->getter('id');

    if( $rExtViewBlockInfo['data'] ) {

      $resId = $this->defResCtrl->resObj->getter('id');

      if(isset($rExtViewBlockInfo['data']['steps'])){

        $eventIdList = $rExtViewBlockInfo['data']['steps'];

        $eventIdsArray = $eventIdsArrayFinal =array();
        foreach( $eventIdList as $eventId){
          $eventIdsArray[] = $eventId;
        }

        $eventModel =  new EventModel();
        $eventList = $eventModel->listItems( array( 'filters' => array( 'inId' => $eventIdsArray), 'order' => array( 'initDate' => 1 ), 'cache' => $this->cacheQuery ));

        // Establecemos locale para obtener las fechas en el idioma actual
        global $C_LANG;
        setlocale (LC_TIME, Cogumelo::getSetupValue( 'lang:available:'.$C_LANG.':i18n' ));

        // Cargamos los datos de la extensión
        while( $event = $eventList->fetch() ){

          $eventInfo = $event->getAllData('onlydata');

          $initDate = new DateTime($eventInfo['initDate']);
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

          $eventCollection[$event->getter('resource')]['event']['formatedDate']['initDate'] = $initDate->format('Ymd');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['j'] = $initDate->format('j');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['l'] = strftime('%A', $initDate->format('U'));
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['m'] = $initDate->format('m');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['F'] = strftime('%B', $initDate->format('U'));
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['Y'] = $initDate->format('Y');
          $eventCollection[$event->getter('resource')]['event']['formatedDate']['time'] = $initDate->format('H:i');
        }

        // Cargamos los datos básicos de recurso que necesitamos
        $resourceModel =  new ResourceModel();
        $resourceList = $resourceModel->listItems( array( 'filters' => array( 'inId' => $eventIdsArrayFinal), 'cache' => $this->cacheQuery ));
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
        // Cargamos todos los términos de la taxonomía de visualización de eventCollection
        $options = array();
        $taxViewList = $taxViewModel->listItems( array( 'filters' => array( 'taxGroupIdName' => 'rextStoryView' ), 'cache' => $this->cacheQuery));
        while( $taxView = $taxViewList->fetch() ){
          $options[ $taxView->getter( 'id' ) ]['idName'] = $taxView->getter( 'idName' );
        }

        foreach ($options as $eventView){
          $templates[$eventView['idName']] = new Template();
          $templates[$eventView['idName']]->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
          $templates[$eventView['idName']]->setTpl( 'rExt'.$eventView['idName'].'Block.tpl', 'rextStory' );
        }

        // Asignamos al template en uso el template asociado al término actual
        foreach ($rExtViewBlockInfo['data']['rextStoryView'] as $eventViewTerm){
          $eventViewSelectedTerm = $eventViewTerm;
        }



      $templates['full'] = $templates[$eventViewSelectedTerm['idName']];
      $rExtViewBlockInfo['template'] = $templates;
    }
    return $rExtViewBlockInfo;
    */
  }

} // class RExtStoryController
