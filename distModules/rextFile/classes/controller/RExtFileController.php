<?php


class RExtFileController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtFileController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextFile(), 'rExtFile_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtFileController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtFileModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'affectsDependences' => array( 'FiledataModel' ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
      error_log( "RExtFileController: getRExtData - getAllData:" . print_r( $rExtData, true ) );

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
      if( $termsGroupedIdName !== false ) {
        foreach( $this->taxonomies as $tax ) {
          if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
            $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
          }
        }
      }

      // Cargo los datos de fichero dentro de los del recurso
      $fileDep = $rExtObj->getterDependence( 'file' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $rExtData[ 'file' ] = $fileModel->getAllData( 'onlydata' );
        }
      }
    }

    error_log( 'RExtFileController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    error_log( "RExtFileController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'author' => array(
        'params' => array( 'label' => __( 'Author' ) ),
        'rules' => array( 'maxlength' => '500' )
      ),
      'file' => array(
        'params' => array( 'label' => __( 'Multimedia file' ), 'type' => 'file', 'id' => 'rExtFileField',
        'placeholder' => __( 'File' ), 'destDir' => CollectionModel::$cols['image']['uploadDir'] ,
        'rules' => array( 'maxfilesize' => '2097152', 'required' => 'true' )
        )
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
    getFormBlockInfo
  */
  public function getFormBlockInfo( FormController $form ) {

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false
    );

    $prefixedFieldNames = $this->prefixArray( $form->getFieldValue( $this->addPrefix( 'FieldNames' ) ) );
    error_log( 'prefixedFieldNames =' . print_r( $prefixedFieldNames, true ) );

    $formBlockInfo['dataForm'] = array(
      'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
      'formFields' => $form->getHtmlFieldsAndGroups(),
    );

    if( $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->getRExtData();
    }

    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }





  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    error_log( "RExtFileController: resFormRevalidate()" );

    /*
    // De entrada, se procesan los campos file en Resource
    if( !$form->existErrors() ) {
      if( !$form->processFileFields( array( $this->addPrefix( 'file' ) ) ) ) {
        $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea '.
          'necesario subirlos otra vez.', 'formError' );
      }
    }
    */
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    error_log( "RExtFileController: resFormProcess()" );


    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      unset( $valuesArray[ 'file' ] );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtFileModel: ' . print_r( $valuesArray, true ) );
      $this->rExtModel = new RExtFileModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
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
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtFileController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RExtFileController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    if( $rExtData ) {
      $template = new Template();

      $rExtData = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, $rExtData[ $key ] );
        error_log( $key . ' === ' . print_r( $rExtData[ $key ], true ) );
      }

      // Vacio campos numericos NULL
      if( $this->numericFields ) {
        foreach( $this->numericFields as $fieldName ) {
          $fieldName = $this->addPrefix( $fieldName );
          if( !isset( $rExtData[ $fieldName ] ) || !$rExtData[ $fieldName ] ) {
            $template->assign( $fieldName, '##NULL-VACIO##' );
          }
        }
      }

      // Procesamos as taxonomías asociadas para mostralas en CSV
      if( $this->taxonomies ) {
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          $taxFieldValue = '';

          if( isset( $rExtData[ $taxFieldName ] ) ) {
            $terms = array();
            foreach( $rExtData[ $taxFieldName ] as $termInfo ) {
              $terms[] = $termInfo['name_es'].' ('.$termInfo['id'].')';
            }
            $taxFieldValue = implode( ', ', $terms );
          }
          $template->assign( $taxFieldName, $taxFieldValue );
        }
      }

      /*
      // Cargo los datos de file
      $fileField = $this->addPrefix( 'file' );
      if( $rExtData[ $fileField ] !== false ) {
        $fileData = $rExtData[ $fileField ];
        error_log( 'fileData: ' . print_r( $fileData, true ) );
        $titleImage = isset( $fileData[ 'title' ] ) ? $fileData[ 'title' ] : '';
        $template->assign( $fileField, '<img src="/cgmlImg/' . $fileData[ 'id' ] . '"
          alt="' . $titleImage . '" title="' . $titleImage . '"></img>' );
      }
      else {
        $template->assign( $fileField, '<p>'.__('None').'</p>' );
      }
      */

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextFile' );
    }

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo() {
    error_log( "RExtFileController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextFile' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtFileController
