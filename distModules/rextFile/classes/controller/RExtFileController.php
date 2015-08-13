<?php


class RExtFileController extends RExtController implements RExtInterface {


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtFileController::__construct' );

    parent::__construct( $defRTypeCtrl, new rextFile(), 'rExtFile_' );
  }


  public function getRExtData( $resId ) {
    error_log( "RExtFileController: getRExtData( $resId )" );
    $rExtData = false;

    $rExtModel = new RExtFileModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'id' => $resId ),
      'affectsDependences' => array( 'RExtFileModel' ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      if( $this->taxonomies ) {
        $taxTerms = $this->defResCtrl->getResTerms( $resId );
        foreach( $this->taxonomies as $tax ) {
          // TODO: Separar los terms por taxonomia
          $rExtData[ $tax['idName'] ] = $taxTerms;
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
      'file' => array(
        'params' => array( 'label' => __( 'File' ), 'type' => 'file', 'id' => 'fileRext',
        'placeholder' => __( 'File' ), 'destDir' => '/rext_file' ),
        'rules' => array( 'maxfilesize' => '100000' )
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

    $form->setField( 'rExtFileFieldNames', array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );
    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



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

    if( !$form->existErrors() && $form->isFieldDefined( 'file' ) ) {
      $this->setFormFiledata( $form, 'file', 'file', $resource );
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
    error_log( "RExtFileController: resFormSuccess()" );
  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    // error_log( "RExtFileController: getViewBlock()" );
    $template = false;

    $rExtData = $this->getRExtData( $resource->getter('id') );
    if( $rExtData ) {
      $template = new Template();

      $rExtData = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, $rExtData[ $key ] );
        error_log( $key . ' === ' . print_r( $rExtData[ $key ], true ) );
      }

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextFile' );
    }

    return $template;
  }

} // class RExtFileController
