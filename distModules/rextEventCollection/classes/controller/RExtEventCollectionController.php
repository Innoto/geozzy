<?php


class RExtEventCollectionController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtEventCollectionController::__construct' );

    global $C_LANG;
    $this->actLang = $C_LANG;

    parent::__construct( $defRTypeCtrl, new rextEventCollection(), 'rExtEventCollection_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtEventCollectionController: getRExtData( $resId )" );
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
        array('filters' => array('collection' => $elemId)) );

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

    $filterRtype = array();
    while( $res = $rtypeArray->fetch() ){
      array_push( $filterRtype, $res->getter('id') );
    }

    $elemList = $resourceModel->listItems(
      array(
        'filters' => array( 'inRtype' => $filterRtype ),
        'affectsDependences' => array('RExtUrlModel')
      )
    );

    $resControl = new ResourceController();

    $resOptions = array();
    while( $res = $elemList->fetch() ) {

      $thumbSettings = array(
        'profile' => 'squareCut',
        'imageId' => $res->getter( 'image' ),
        'imageName' => $res->getter( 'image' ).'.jpg'
      );
      $resDataExtArray = $res->getterDependence('id', 'RExtUrlModel');
      if( $resDataExt = $resDataExtArray[0] ){
        $thumbSettings['url'] = $resDataExt->getter('url');
      }
      $elOpt = array(
        'value' => $res->getter( 'id' ),
        'text' => $res->getter( 'title', LANG_DEFAULT ),
        'data-image' => $resControl->getResourceThumbnail( $thumbSettings )
      );

      $resOptions[ $res->getter( 'id' ) ] = $elOpt;
    }

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'rextEventCollectionView' => array(
        'params' => array( 'label' => __( 'EventCollection view' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'rextEventCollectionView' )
        )
      ),
      'events' => array(
        'params' => array(
          'label' => __( 'Eventos' ),
          'type' => 'select', 'id' => 'collEvents',
          'class' => 'cgmMForm-order',
          'multiple' => true,
          'options'=> $resOptions
        )
      ),
      'addEvents' => array(
        'params' => array( 'id' => 'addEvents', 'type' => 'button', 'value' => __( 'Add Event' ))
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
    $templates['full']->addClientScript('js/rextEventCollection.js', 'rextEventCollection');

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
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
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

      if ($resourceCollectionsCount>0){// existe coleccion

        $eventCol = false;
        while($resCol = $resourceCollections->fetch()){
          $typecol = $collection->listItems(array('filters' => array('id' => $resCol->getter('collection'))))->fetch();

          if($typecol->getter('collectionType')==='event'){
            $eventCol = $typecol;
          }
        }
        $elemId = false;
        if ($eventCol){
          $elemId = $eventCol->getter('id');
        }
      }
      else{ // creamos a coleccion
        if ($newResources){
          $collection->setter('collectionType', 'event');
          $collection->save();
          $elemId = $collection->getter('id');
          $resourceCollection = new ResourceCollectionsModel(array('resource'=>$resource->getter('id'), 'collection' => $collection->getter('id')));
          $resourceCollection->save();
        }
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
    Datos y template por defecto de la extension
   */
  public function getViewBlockInfo() {
    // error_log( "RExtEventCollectionController: getViewBlockInfo()" );

    $rExtViewBlockInfo = parent::getViewBlockInfo();

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      /* Cargamos los bloques de colecciones */
      $collectionArrayInfo = $this->defResCtrl->getCollectionBlockInfo( $resData[ 'id' ] );

      $multimediaArray = false;
      $collectionArray = false;
      if ($collectionArrayInfo){
        foreach ($collectionArrayInfo as $key => $collectionInfo){
          if ($collectionInfo['col']['collectionType'] == 'event'){ // colecciones multimedia
              $eventArray[$key] = $collectionInfo;
          }
        }

        if ($eventArray){
          $arrayEventBlock = $this->defResCtrl->goOverCollections( $eventArray, $collectionType = 'event' );
          if ($arrayEventBlock){
            foreach( $arrayEventBlock as $eventBlock ) {
              $template->addToFragment( 'eventBlock', $eventBlock );
            }
          }
        }
      }

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextEventCollection' );
      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }
    return $rExtViewBlockInfo;
  }

} // class RExtEventCollectionController
