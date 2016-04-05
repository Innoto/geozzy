<?php


class RExtAppUserController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtAppUserController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextAppUser(), 'rExtAppUser_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtAppUserController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtAppUserModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }

    // error_log( 'RExtAppUserController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtAppUserController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'user' => array(
        'params' => array( 'type' => 'reserved', 'label' => __( 'User' ) )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

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
    // error_log( 'prefixedFieldNames =' . print_r( $prefixedFieldNames, true ) );

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
    // error_log( "RExtAppUserController: resFormRevalidate()" );

    /*
    // De entrada, se procesan los campos file en Resource
    if( !$form->existErrors() ) {
      if( !$form->processAppUserFields( array( $this->addPrefix( 'file' ) ) ) ) {
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
    // error_log( "RExtAppUserController: resFormProcess()" );


    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtAppUserModel: ' . print_r( $valuesArray, true ) );
      $this->rExtModel = new rextAppUserModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
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
    // error_log( "RExtAppUserController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RExtAppUserController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    if( $rExtData ) {
      $template = new Template();

      $rExtData = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, $rExtData[ $key ] );
        // error_log( $key . ' === ' . print_r( $rExtData[ $key ], true ) );
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
      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextAppUser' );
    }

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo() {
    // error_log( "RExtAppUserController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextAppUser' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtAppUserController
