<?php


class RExtRoutesController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){

    parent::__construct( $defRTypeCtrl, new rextRoutes(), 'rExtRoutes_' );
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
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'affectsDependences' => array( 'FiledataModel' ) ) );
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

/*
    'params' => array( 'label' => __( 'Multimedia file' ), 'type' => 'file', 'id' => 'rExtFileField',
    'placeholder' => __( 'File' ), 'destDir' => CollectionModel::$cols['image']['uploadDir'] ),
    'rules' => array( 'maxfilesize' => '5242880', 'required' => 'true' )
*/
    $fieldsInfo = array(

      'routeFile' => array(
        'params' => array( 'label' => __( 'Route file' ), 'type' => 'file', 'id' => 'rExtFileField',
        'placeholder' => __( 'File' ), 'destDir' => RoutesModel::$cols['routeFile']['uploadDir'] ),
        'rules' => array( 'maxfilesize' => '5242880', 'required' => 'true', 'accept' => ',application/xml,application\/gpx,application\/gpx\+xml,application\/vnd.google\-earth\.kml\+xml' )
      ),
      'durationMinutes' => array(
        'params' => array( 'label' => __( 'Duration of route' ) ),
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
        'params' => array( 'label' => __( 'Travel distance' ) ),
        'rules' => array( 'digits' => true )
      ),
      'difficultyEnvironment' => array(
        'params' => array( 'label' => __( 'Natural environment difficulty' ) ),
        'rules' => array( 'digits' => true )
      ),

      'difficultyItinerary' => array(
        'params' => array( 'label' => __( 'Difficulty of the itinerary' ) ),
        'rules' => array( 'digits' => true )
      ),
      'difficultyDisplacement' => array(
        'params' => array( 'label' => __( 'Difficulty of displacement' ) ),
        'type' => 'INT'
      ),
      'difficultyEffort' => array(
        'params' => array( 'label' => __( 'Effort level' ) ),
        'type' => 'INT'
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
    /**
     * Hay que redefinirlo para meterle el js de inicialización de mapa
     */
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->addClientScript('js/initMap.js', 'geozzy');
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
   //parent::getViewBlockInfo();
  

} // class RExtRoutesController
