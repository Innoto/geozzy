<?php

geozzy::load( 'controller/RExtController.php' );

class RExtPoiCollectionController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    global $C_LANG;
    $this->actLang = $C_LANG;

    parent::__construct( $defRTypeCtrl, new rextPoiCollection(), 'rExtPoiCollection_' );
  }


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
    $resourceCollectionsList = $resourceCollectionsModel->listItems([
      'filters' => [ 'resource' => $resId ],
      'cache' => $this->cacheQuery
    ]);

    $elemId = false;
    $poiCol = false;
    while( $resCol = $resourceCollectionsList->fetch() ) {
      $typecol = $collection->listItems([
        'filters' => [ 'id' => $resCol->getter('collection') ],
        'cache' => $this->cacheQuery
      ])->fetch();

      if($typecol->getter('collectionType')==='poi'){
        $elemId = $typecol->getter('id');
      }
    }

    if( $elemId ) {
      $collectionResourcesModel = new CollectionResourcesModel();
      $collectionResourceCount = $collectionResourcesModel->listCount([
        'filters' => [ 'collection' => $elemId ],
        'cache' => $this->cacheQuery
      ]);
      $collectionResourceList = $collectionResourcesModel->listItems([
        'filters' => [ 'collection' => $elemId ],
        'order' => [ 'weight' => 1 ],
        'cache' => $this->cacheQuery
      ]);
      if($collectionResourceCount>0) {
        $resIds = array();
        while($res = $collectionResourceList->fetch()){
          $resIds[] = $res->getter('resource');
        }
        $rExtData['pois'] = $resIds;
      }

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

    $filter = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$filterRTypeParent.':poi:listOptions' );
    if( !$filter || count($filter) === 0 ){
      $filter = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:poi:listOptions' );
    }

    $resourceModel = new ResourceModel();
    $rtypeControl = new ResourcetypeModel();

    if($this->defResCtrl->resObj!=false){
      $resId = $this->defResCtrl->resObj->getter('id');
    }
    else{
      $resId='null';
    }


    $rtypeArray = $rtypeControl->listItems( [ 'filters' => [ 'idNameExists' => $filter ], 'cache' => $this->cacheQuery ] );
    $rtypeArraySize = $rtypeControl->listCount( [ 'filters' => [ 'idNameExists' => $filter ], 'cache' => $this->cacheQuery ] );

    $varConditions = '';
    $i = 1;
    while( $res = $rtypeArray->fetch() ){
      if($i === $rtypeArraySize){
        $varConditions = $varConditions . $res->getter('id').';'.$resId.';'.'poi';
      }
      else{
        $varConditions = $varConditions . $res->getter('id').',';
      }
      $i = $i+1;
    }

    $collectionRtypeResources = new CollectionTypeResourcesModel();
    $collectionRtypeResourcesList = $collectionRtypeResources->listItems([
      'filters' => [ 'conditionsRtypeCollection' => $varConditions ],
      'cache' => $this->cacheQuery
    ]);
    $resOptions = array();
    while($res = $collectionRtypeResourcesList->fetch()){
      $resOptions[] = array(
        'value' => $res->getter( 'id' ),
        'text' => $res->getter( 'title', Cogumelo::getSetupValue('lang:default') )
      );
    }

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'pois' => array(
        'params' => array(
          'label' => __( 'Puntos de interés' ),
          'type' => 'select', 'id' => 'collPois',
          'class' => 'cgmMForm-order',
          'multiple' => true,
          'options'=> $resOptions
        )
      ),
      'addPoi' => array(
        'params' => array( 'id' => 'addPois', 'type' => 'button', 'value' => __( 'Add POI' ))
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Validaciones extra
    //$form->removeValidationRule( 'pois', 'inArray' );
    $form->removeValidationRule( 'rExtPoiCollection_pois', 'inArray' );

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
    $templates['full']->addClientScript('js/rextPoiCollection.js', 'rextPoiCollection');

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
    // error_log( "RExtPoiCollectionController: resFormProcess()" );
    $valuesArray = $form->getValuesArray();

    if( !$form->existErrors() ) {
      if($this->taxonomies){
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
            $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
          }
        }
      }

      $newResources = $form->getFieldValue( 'rExtPoiCollection_pois' );


      $collection = new CollectionModel( );
      $resourceCollectionsModel = new ResourceCollectionsModel();

      // buscamos las colecciones de ese recurso
      $resourceCollectionsCount = $resourceCollectionsModel->listCount([
        'filters' => [ 'resource' => $resource->getter('id') ],
        'cache' => $this->cacheQuery
      ]);
      $resourceCollections = $resourceCollectionsModel->listItems([
        'filters' => [ 'resource' => $resource->getter('id') ],
        'cache' => $this->cacheQuery
      ]);

      $elemId = false;
      if ($resourceCollectionsCount>0){// existe coleccion
        while($resCol = $resourceCollections->fetch()){
          $typecol = $collection->listItems([
            'filters' => [ 'id' => $resCol->getter('collection') ],
            'cache' => $this->cacheQuery
          ])->fetch();

          if($typecol->getter('collectionType')==='poi'){
            $elemId = $typecol->getter('id');
            break;
          }
        }
      }

      if (!$elemId && $newResources){ // creamos a coleccion
        $collection->setter('collectionType', 'poi');
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

        $collectionResourceList = $CollectionResourcesModel->listItems([
          'filters' => [ 'collection' => $elemId ], 'cache' => $this->cacheQuery
        ]);

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

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();
      $resData = $this->defResCtrl->getResourceData();
      $rExtViewBlockInfo['data']['id'] = $resData['id'];
      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      //biMetrics::autoIncludes();
      //explorer::autoIncludes();




      $template->addClientScript('yarn/backbone.obscura/backbone.obscura.js', 'vendor');
      $template->addClientScript('yarn/tiny_map_utilities/smart_infowindow/smart_infowindow.js', 'vendor');
      $template->addClientScript('yarn/tiny_map_utilities/smart_infowindow/vendor/jQueryRotate.js', 'vendor');


      $template->addClientScript('js/router/ExplorerRouter.js', 'explorer');
      $template->addClientScript('js/model/ExplorerResourceMinimalModel.js', 'explorer');
      $template->addClientScript('js/model/ExplorerResourcePartialModel.js', 'explorer');
      $template->addClientScript('js/collection/ExplorerResourceMinimalCollection.js', 'explorer');
      $template->addClientScript('js/collection/ExplorerResourcePartialCollection.js', 'explorer');
      $template->addClientScript('js/view/Templates.js', 'explorer');
      $template->addClientScript('js/view/ExplorerMapView.js', 'explorer');
      $template->addClientScript('js/view/ExplorerMapInfoBubbleView.js', 'explorer');

      $template->addClientScript('js/view/utils/twoLinesIntersection.js', 'explorer');
      $template->addClientScript('js/view/utils/RotateMapArrow.js', 'explorer');

      $template->addClientScript('js/Explorer.js', 'explorer');



      $template->addClientScript('js/TimeDebuger.js', 'common');

      $template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
      $template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');

      rextPoiCollection::autoIncludes();
      $template->addClientScript('js/view/ExplorerPoisPanoramaView.js', 'rextPoiCollection');
      $template->addClientScript('js/poiCollectionExplorer.js', 'rextPoiCollection');


      $template->setTpl( 'rExtViewBlock.tpl', 'rextPoiCollection' );
      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtPoiCollectionController
