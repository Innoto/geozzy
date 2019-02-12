<?php
geozzy::load( 'controller/RExtController.php' );

class RExtRoutesController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){

    parent::__construct( $defRTypeCtrl, new rextRoutes(), 'rExtRoutes_' );
    global $rextRoutes_difficulty;
    $rextRoutes_difficulty = array(
      '0' => '--',
      '1' => __('Very Low'),
      '2' => __(' Low'),
      '3' => __('Medium'),
      '4' => __(' High'),
      '5' => __('Extreme')
    );
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

    // @todo Esto ten que controlar os idiomas

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RoutesModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'affectsDependences' => array( 'FiledataModel' ), 'cache' => $this->cacheQuery ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
      if( $termsGroupedIdName !== false ) {
        foreach( $this->taxonomies as $tax ) {
          if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
            $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
          }
        }
      }

      $fileDep = $rExtObj->getterDependence( 'routeFile' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $rExtData[ 'routeFile' ] = $fileModel->getAllData( 'onlydata' );
        }
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

    $rExtFieldNames = array();

    global $rextRoutes_difficulty;

    $rextRoutes_difficultyWithNumbers = [];
    foreach( $rextRoutes_difficulty as $dfKey => $df ) {
      if( $dfKey == 0 ) {
        $rextRoutes_difficultyWithNumbers[$dfKey] = $df;
      }
      else {
        $rextRoutes_difficultyWithNumbers[$dfKey] = $dfKey.'-'.$df;
      }
    }

    $fieldsInfo = array(
      'circular' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => __('Circular itinerary') ))
      ),
      'durationMinutes' => array(
        'params' => array( 'label' => __( 'Route duration (minutes)' ) ),
        'rules' => array( 'digits' => true )
      ),
      'slopeUp' => array(
        'params' => array( 'label' => __( 'Vertical rise level' ) ),
        'rules' => array( 'digits' => true )
      ),
      'slopeDown' => array(
        'params' => array( 'label' => __( 'Vertical descent level' ) ),
        'rules' => array( 'digits' => true )
      ),
      'travelDistance' => array(
        'params' => array( 'label' => __( 'Travel distance (meters)' ) ),
        'rules' => array( 'digits' => true )
      ),
      'difficultyEnvironment' => array(
        'params' => array( 'label' => __( 'Natural environment difficulty' ), 'type' => 'select',
          'options' => $rextRoutes_difficultyWithNumbers
        )
      ),
      'difficultyItinerary' => array(
        'params' => array( 'label' => __( 'Itinerary difficulty' ), 'type' => 'select',
          'options' => $rextRoutes_difficultyWithNumbers
        )
      ),
      'difficultyDisplacement' => array(
        'params' => array( 'label' => __( 'Displacement difficulty' ) , 'type' => 'select',
          'options' => $rextRoutes_difficultyWithNumbers
        )
      ),
      'difficultyEffort' => array(
        'params' => array( 'label' => __( 'Effort level' ), 'type' => 'select',
          'options' => $rextRoutes_difficultyWithNumbers
        )
      ),
      'difficultyGlobal' => array(
        'params' => array( 'label' => __( 'Global difficulty' ), 'type' => 'select',
          'options' => $rextRoutes_difficultyWithNumbers
        )
      ),
      'routeStart' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Route start' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'routeEnd' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Route end' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),

      'locStartLat' => array(
        'params' => array( 'label' => __( 'Startroute Latitude' ) ),
        'rules' => array( 'number' => true )
      ),
      'locStartLon' => array(
        'params' => array( 'label' => __( 'Startroute Longitude' ) ),
        'rules' => array( 'number' => true )
      ),

      'locEndLat' => array(
        'params' => array( 'label' => __( 'Endroute Latitude' ) ),
        'rules' => array( 'number' => true )
      ),
      'locEndLon' => array(
        'params' => array( 'label' => __( 'Endroute Longitude' ) ),
        'rules' => array( 'number' => true )
      ),

      'routeFile' => array(
        'params' => array( 'label' => __( 'Route file' ), 'type' => 'file',
        'placeholder' => __( 'File' ), 'destDir' => RoutesModel::$cols['routeFile']['uploadDir'] ),
        'rules' => array( 'maxfilesize' => '5242880', 'required' => 'true', 'accept' => ',application/octet-stream,application/xml,application\/gpx,application\/gpx\+xml,application\/vnd.google\-earth\.kml\+xml' )
      )



    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

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


    }

    $form->loadArrayValues( $valuesArray );
    //var_dump($valuesArray);exit;
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

    $form->setSuccess( 'onFileUpload', 'adminRextRoutesJs.fileUpload' );

    $form->saveToSession();

    return( $rExtFieldNames );
  }


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

    //$templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'rextRoutes' );

    $templates['full']->addClientScript('js/view/routeView.js', 'rextRoutes');
    $templates['full']->addClientScript('js/collection/RouteCollection.js', 'rextRoutes');
    $templates['full']->addClientScript('js/model/RouteModel.js', 'rextRoutes');


    $templates['full']->addClientScript('js/adminRextRoutes.js', 'rextRoutes');


    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );

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
    if( !$form->existErrors() ) {
      //$numericFields = array( 'averagePrice', 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );


//
//
//
//

      //Route end LOCATION
      if( isset( $valuesArray[ 'locEndLat' ] ) && isset( $valuesArray[ 'locEndLon' ] ) ) {
        Cogumelo::load( 'coreModel/DBUtils.php' );
        $valuesArray[ 'locEnd' ] = DBUtils::encodeGeometry(
          array(
            'type' => 'POINT',
            'data'=> array( $valuesArray[ 'locEndLat' ], $valuesArray[ 'locEndLon' ] )
          )
        );
        unset( $valuesArray[ 'locEndLat' ] );
        unset( $valuesArray[ 'locEndLon' ] );
      }

      //Route start LOCATION
      if( isset( $valuesArray[ 'locStartLat' ] ) && isset( $valuesArray[ 'locStartLon' ] ) ) {
        Cogumelo::load( 'coreModel/DBUtils.php' );
        $valuesArray[ 'locStart' ] = DBUtils::encodeGeometry(
          array(
            'type' => 'POINT',
            'data'=> array( $valuesArray[ 'locStartLat' ], $valuesArray[ 'locStartLon' ] )
          )
        );
        unset( $valuesArray[ 'locStartLat' ] );
        unset( $valuesArray[ 'locStartLon' ] );
      }

//
//
//
//



      $this->rExtModel = new RoutesModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    $fileField = $this->addPrefix( 'routeFile' );
    if( !$form->existErrors() && $form->isFieldDefined( $fileField ) ) {
      //var_dump($rExtModel);exit;

      $this->defResCtrl->setFormFiledata( $form, $fileField, 'routeFile', $this->rExtModel );
      $this->rExtModel->save();
    }


    if( !$form->existErrors() ) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
      }
    }

    if( !$form->existErrors() ) {
      $saveResult = $this->rExtModel->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
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
       // TODO: esto será un campo da BBDD
       $rExtViewBlockInfo['data'] = $this->defResCtrl->getTranslatedData( $rExtViewBlockInfo['data'] );

       $resData = $this->defResCtrl->getResourceData();
       $rExtViewBlockInfo['data']['resourceId'] = $resData['id'];

       $hours = floor($rExtViewBlockInfo['data']['durationMinutes'] / 60);
       $minutes = ($rExtViewBlockInfo['data']['durationMinutes'] % 60);
       $rExtViewBlockInfo['data']['durationHours'] = date('G', mktime(0,$rExtViewBlockInfo['data']['durationMinutes']));
       $rExtViewBlockInfo['data']['durationMinutes'] = date('i', mktime(0,$rExtViewBlockInfo['data']['durationMinutes']));
       $rExtViewBlockInfo['data']['travelDistanceKm'] = $rExtViewBlockInfo['data']['travelDistance']/1000;

       global $rextRoutes_difficulty;
       if( !empty( $rExtViewBlockInfo['data']['difficultyEnvironment'] ) ) {
         $rExtViewBlockInfo['data']['difficultyEnvironmentText'] = __($rextRoutes_difficulty[$rExtViewBlockInfo['data']['difficultyEnvironment']]);
       }
       if( !empty( $rExtViewBlockInfo['data']['difficultyItinerary'] ) ) {
         $rExtViewBlockInfo['data']['difficultyItineraryText'] = __($rextRoutes_difficulty[$rExtViewBlockInfo['data']['difficultyItinerary']]);
       }
       if( !empty( $rExtViewBlockInfo['data']['difficultyDisplacement'] ) ) {
         $rExtViewBlockInfo['data']['difficultyDisplacementText'] = __($rextRoutes_difficulty[$rExtViewBlockInfo['data']['difficultyDisplacement']]);
       }
       if( !empty( $rExtViewBlockInfo['data']['difficultyEffort'] ) ) {
         $rExtViewBlockInfo['data']['difficultyEffortText'] = __($rextRoutes_difficulty[$rExtViewBlockInfo['data']['difficultyEffort']]);
       }
       if( !empty( $rExtViewBlockInfo['data']['difficultyGlobal'] ) ) {
         $rExtViewBlockInfo['data']['difficultyGlobalText'] = __($rextRoutes_difficulty[$rExtViewBlockInfo['data']['difficultyGlobal']]);
       }

       $rExtViewBlockInfo['template']['full'] = new Template();
       $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
       $rExtViewBlockInfo['template']['full']->addClientScript('js/rextRoutes.js', 'rextRoutes');
       $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextRoutes' );

       $rExtViewBlockInfo['template']['partialBtnDownload'] = new Template();
       $rExtViewBlockInfo['template']['partialBtnDownload']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
       $rExtViewBlockInfo['template']['partialBtnDownload']->setTpl( 'rExtBtnDownloadViewBlock.tpl', 'rextRoutes' );

       $rExtViewBlockInfo['template']['partialBasicInfo'] = new Template();
       $rExtViewBlockInfo['template']['partialBasicInfo']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
       $rExtViewBlockInfo['template']['partialBasicInfo']->addClientScript('js/rextRoutes.js', 'rextRoutes');
       $rExtViewBlockInfo['template']['partialBasicInfo']->setTpl( 'rExtBasicInfoViewBlock.tpl', 'rextRoutes' );
     }

     return $rExtViewBlockInfo;
   }


} // class RExtRoutesController
