<?php


class RExtEatAndDrinkController extends RExtController implements RExtInterface {


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtEatanddrinkController::__construct' );

    parent::__construct( $defRTypeCtrl, new rextEatAndDrink(), 'rextEatAndDrink_' );
  }


  public function getRExtData( $resId ) {
    error_log( "ResourceController: getRExtData( $resId )" );
    $rExtData = false;

    $rExtModel = new EatAndDrinkModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      error_log( 'ResourceController: getRExtData = '.print_r( $rExtData, true ) );

      // Cargo los datos de destacados con los que está asociado el recurso
      $taxTerms = $this->defResCtrl->getResTerms( $resId );
      if( $taxTerms ) {
        foreach( $this->taxonomies as $tax ) {
          // TODO: Separar los terms por taxonomia
          $rExtData[ $tax['idName'] ] = $taxTerms;
        }
      }



    }

    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    error_log( "RExtEatAndDrinkController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'reservationURL' => array(
        'params' => array( 'label' => __( 'Restaurant reservation URL' ) ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'reservationPhone' => array(
        'params' => array( 'label' => __( 'Restaurant reservation phone' ) ),
        'rules' => array( 'maxlength' => 200 )
      ),
      'averagePrice' => array(
        'params' => array( 'label' => __( 'Restaurant average price' ) ),
        'rules' => array( 'digits' => true )
      ),
      'eatanddrinkType' => array(
        'params' => array( 'label' => __( 'EatAndDrink type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkType' )
        )
      ),
      'eatanddrinkSpecialities' => array(
        'params' => array( 'label' => __( 'EatAndDrink specialities' ), 'type' => 'select', 'multiple' => true,
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkSpecialities' )
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

    $form->setField( 'RExtEatAndDrinkController', array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );
    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    error_log( "RExtEatAndDrinkController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    error_log( "RExtEatAndDrinkController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $numericFields = array( 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $rExtModel = new EatAndDrinkModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    foreach( $this->taxonomies as $tax ) {
      $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
      if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
        $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
      }
    }

    if( !$form->existErrors()) {
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
    error_log( "RExtEatAndDrinkController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    error_log( "RExtEatanddrinkController: getViewBlock()" );
    $template = false;
    $resCtrl = new ResourceController();
    $rExtData = $this->getRExtData( $resource->getter('id') );
    if( $rExtData ) {
      $template = new Template();

      $rExtData = $this->prefixArrayKeys( $rExtData );

      foreach( $rExtData as $key => $value ) {
        // FALTA CAMBHIAR CONSULTA Y CONSTRUIR RESPUESTA
        if ($key == 'rextEatAndDrink_eatanddrinkType'){
          $taxList[$key] = $resCtrl->getTermsGrouped( $value );
          print_r($taxList);
        }

        $template->assign( $key, $rExtData[ $key ] );
        error_log( $key . ' === ' . print_r( $rExtData[ $key ], true ) );
      }

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextEatAndDrink' );
    }

    return $template;
  }

} // class RExtEatAndDrinkController
