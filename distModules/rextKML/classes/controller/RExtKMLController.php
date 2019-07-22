<?php
geozzy::load( 'controller/RExtController.php' );

class RExtKMLController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtKMLController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextKML(), 'rextKML_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtKMLController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtKMLModel();
    $rExtList = $rExtModel->listItems([
      'filters' => [ 'resource' => $resId ],
      'affectsDependences' => [ 'FiledataModel' ],
      'cache' => $this->cacheQuery
    ]);
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
      // error_log( "RExtKMLController: getRExtData - getAllData:" . print_r( $rExtData, true ) );


      // Cargo los datos de fichero dentro de los del recurso
      $fileDep = $rExtObj->getterDependence( 'file' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $rExtData[ 'file' ] = $fileModel->getAllData( 'onlydata' );
        }
      }
    }

    // error_log( 'RExtKMLController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtKMLController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = [
      'file' => [
        'params' => [ 'label' => __( 'KML file' ), 'type' => 'file', 'id' => 'rextKMLField',
          'placeholder' => __( 'File' ), 'destDir' => RExtKMLModel::$cols['file']['uploadDir']
        ],
        'rules' => [
          'maxfilesize' => '5242880',
          'accept' => ',application/xml,application/vnd.google\-earth.kml\+xml,application/vnd.google\-earth.kmz,application/zip'
        ]
      ]
    ];



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

    $rExtFieldNames[] = 'FieldNames';
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

     $templates['full']->addClientScript('js/adminRextKML.js', 'rextKML');
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
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtKMLController: resFormProcess()" );


    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      unset( $valuesArray[ 'file' ] );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtKMLModel: ' . print_r( $valuesArray, true ) );
      $this->rExtModel = new RExtKMLModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
      else {
        $saveResult = $this->rExtModel->save();
        if( $saveResult === false ) {
          $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
        }
      }
    }


    // Guardo los datos de file
    $fileField = $this->addPrefix( 'file' );
    if( !$form->existErrors() && $form->isFieldDefined( $fileField ) ) {
      $this->defResCtrl->setFormFiledata( $form, $fileField, 'file', $this->rExtModel );
      $this->rExtModel->save();
    }

    if( !$form->existErrors() ) {
      $this->rExtModel->save();
    }

    if( $this->taxonomies ) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
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
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "RExtKMLController: getViewBlockInfo( $resId )" );

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextKML' );

      $rExtViewBlockInfo['template']['full']->addClientScript('js/rextKMLView.js', 'rextKML');
    }

    return $rExtViewBlockInfo;
  }

} // class RExtKMLController
