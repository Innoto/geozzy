<?php
//rextAccommodation::load('model/AccommodationModel.php');


class RExtEatAndDrinkController {

  public $prefix = 'rextEatAndDrink_';

  public $defRTypeCtrl = null;
  public $defResCtrl = null;
  public $rExtModule = null;
  public $taxonomies = false;

  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtEatanddrinkController::__construct' );

    $this->defRTypeCtrl = $defRTypeCtrl;
    $this->defResCtrl = $defRTypeCtrl->defResCtrl;

    $this->rExtModule = new rextEatAndDrink();
    if( property_exists( $this->rExtModule, 'taxonomies' ) && is_array( $this->rExtModule->taxonomies )
      && count( $this->rExtModule->taxonomies ) > 0 )
    {
      $this->taxonomies = $this->rExtModule->taxonomies;
    }
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


  public function getRExtFormValues( $formValuesArray ) {
    error_log( "RExtEatAndDrinkController: getRExtFormValues()" );
    $valuesArray = array();

    $numericFields = array( 'capacity' );

    foreach( $formValuesArray as $key => $value ) {
      $newKey = $this->delPrefix( $key );
      if( $newKey !== $key ) {
        if( $formValuesArray[ $key ] === '' && in_array( $newKey, $numericFields ) ) {
          $valuesArray[ $newKey ] = null;
        }
        else {
          $valuesArray[ $newKey ] = $formValuesArray[ $key ];
        }
      }
    }

    if( count( $valuesArray ) < 1 ) {
      $valuesArray = false;
    }

    error_log( 'RExtEatAndDrinkController: '.print_r( $valuesArray, true ) );
    return $valuesArray;
  }

  public function prefixArrayKeys( $valuesArray ) {
    if( is_array( $valuesArray ) ) {
      $prefixArray = array();
      foreach( $valuesArray as $key => $value ) {
        $prefixArray[ $this->addPrefix( $key ) ] = $value;
      }
    }
    else {
      $prefixArray = $valuesArray;
    }

    return $prefixArray;
  }

  public function addPrefix( $text ) {

    return $this->prefix . $text;
  }

  public function delPrefix( $text ) {
    if( strpos( $text, $this->prefix ) === 0 ) {
      $text = substr( $text, strlen( $this->prefix ) );
    }

    return $text;
  }



  /**
    Defino el formulario
   */
  public function manipulateForm( $form ) {
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
      'capacity' => array(
        'params' => array( 'label' => __( 'Restaurant capacity' ) ),
        'rules' => array( 'digits' => true )
      ),
      'averagePrice' => array(
        'params' => array( 'label' => __( 'Restaurant average price' ) ),
        'rules' => array( 'digits' => true )
      ),
      'eatanddrinkType' => array(
        'params' => array( 'label' => __( 'EatAndDrink Type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkType' )
        )
      ),
      'eatanddrinkCategory' => array(
        'params' => array( 'label' => __( 'EatAndDrink Category' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkCategory' )
        )
      ),
      'eatanddrinkServices' => array(
        'params' => array( 'label' => __( 'EatAndDrink Services' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkServices' )
        )
      ),
      'eatanddrinkFacilities' => array(
        'params' => array( 'label' => __( 'EatAndDrink facilities' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkFacilities' )
        )
      ),
      'eatanddrinkBrand' => array(
        'params' => array( 'label' => __( 'EatAndDrink brand' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkBrand' )
        )
      ),
      'eatanddrinkusers' => array(
        'params' => array( 'label' => __( 'EatAndDrink users profile' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'eatanddrinkUsers' )
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
  public function resFormRevalidate( $form ) {
    error_log( "RExtEatAndDrinkController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( $form, $resource ) {
    error_log( "RExtEatAndDrinkController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray() );

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
  public function resFormSuccess( $form, $resource ) {
    error_log( "RExtEatAndDrinkController: resFormSuccess()" );

  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */






  /**
    Visualizamos el Recurso
  */
  public function getViewBlock( $resObj, $resBlock ) {
    error_log( "RExtEatanddrinkController: getViewBlock()" );
    $template = false;

    $rExtData = $this->getRExtData( $resObj->getter('id') );
    if( $rExtData ) {
      $template = new Template();

      $rExtData = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, $rExtData[ $key ] );
        error_log( $key . ' === ' . print_r( $rExtData[ $key ], true ) );
      }

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextAccommodation' );
    }

    return $template;
  } // function getViewBlock( $resObj )

} // class RExtAccommodationController
