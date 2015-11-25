<?php


class RExtAccommodationController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtAccommodationController::__construct' );

    $this->numericFields = array( 'singleRooms', 'doubleRooms', 'familyRooms', 'beds', 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextAccommodation(), 'rExtAccommodation_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtAccommodationController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new AccommodationModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
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
    }

    // error_log( 'RExtAccommodationController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtAccommodationController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'reservationURL' => array(
        'params' => array( 'label' => __( 'Hotel reservation URL' ) ),
        'rules' => array( 'maxlength' => 2000, 'url' => true )
      ),
      'reservationPhone' => array(
        'params' => array( 'label' => __( 'Hotel reservation phone' ) ),
        'rules' => array( 'maxlength' => 20 )
      ),
      'reservationEmail' => array(
        'params' => array( 'label' => __( 'Hotel reservation email' ) ),
        'rules' => array( 'maxlength' => 255, 'email' => true)
      ),
      'singleRooms' => array(
        'params' => array( 'label' => __( 'Hotel single rooms' ) ),
        'rules' => array( 'digits' => true )
      ),
      'doubleRooms' => array(
        'params' => array( 'label' => __( 'Hotel double rooms' ) ),
        'rules' => array( 'digits' => true )
      ),
      'familyRooms' => array(
        'params' => array( 'label' => __( 'Hotel family rooms' ) ),
        'rules' => array( 'digits' => true )
      ),
      'beds' => array(
        'params' => array( 'label' => __( 'Hotel beds' ) ),
        'rules' => array( 'digits' => true )
      ),
      'averagePrice' => array(
        'params' => array( 'label' => __( 'Hotel average price' ) ),
        'rules' => array( 'digits' => true )
      ),
      'accommodationType' => array(
        'params' => array( 'label' => __( 'Accommodation type' ), 'type' => 'select',  'multiple' => true, 'class' => 'cgmMForm-order',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationType' )
        )
      ),
      'accommodationCategory' => array(
        'params' => array( 'label' => __( 'Accommodation category' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationCategory' )
        )
      ),
      'accommodationServices' => array(
        'params' => array( 'label' => __( 'Accommodation services' ), 'type' => 'select', 'multiple' => true,
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationServices' )
        )
      ),
      'accommodationFacilities' => array(
        'params' => array( 'label' => __( 'Accommodation facilities' ), 'type' => 'select', 'multiple' => true,
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationFacilities' )
        )
      ),
      'accommodationBrand' => array(
        'params' => array( 'label' => __( 'Accommodation brand' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationBrand' )
        )
      ),
      'accommodationUsers' => array(
        'params' => array( 'label' => __( 'Accommodation users profile' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationUsers' )
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




  public function getFormBlockInfo( FormController $form ) {
    error_log( "RExtAccommodationController: getFormBlockInfo()" );

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
    // error_log( "RExtAccommodationController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtAccommodationController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW rExtAccommodation: ' . print_r( $valuesArray, true ) );
      $rExtModel = new AccommodationModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
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
      $saveResult = $rExtModel->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtAccommodationController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso (extensión Accommodation)
   */
  public function getViewBlock( Template $resBlock ) {
    error_log( "RExtAccommodationController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    if( $rExtData ) {
      $template = new Template();
      $rExtData = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, ($value) ? $value : '' );
        // error_log( $key . ' === ' . print_r( $value, true ) );
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

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextAccommodation' );
    }

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo() {
    error_log( "RExtAccommodationController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextAccommodation' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtAccommodationController
