<?php
//rextAccommodation::load('model/AccommodationModel.php');


class RExtAccommodationController {

  public $prefix = 'rExtAccommodation_';

  public $defRTypeCtrl = null;
  public $defResCtrl = null;
  public $rExtModule = null;
  public $taxonomies = false;

  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtAccommodationController::__construct' );

    $this->defRTypeCtrl = $defRTypeCtrl;
    $this->defResCtrl = $defRTypeCtrl->defResCtrl;

    $this->rExtModule = new rextAccommodation();
    if( property_exists( $this->rExtModule, 'taxonomies' ) && is_array( $this->rExtModule->taxonomies )
      && count( $this->rExtModule->taxonomies ) > 0 )
    {
      $this->taxonomies = $this->rExtModule->taxonomies;
    }
  }


  public function getRExtData( $resId ) {
    // error_log( "ResourceController: getRExtData()" );
    $rExtData = false;

    $rExtModel = new AccommodationModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'id' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

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
    error_log( "RExtAccommodationController: getRExtFormValues()" );
    $valuesArray = array();

    $numericFields = array( 'singleRooms', 'doubleRooms', 'familyRooms', 'beds', 'averagePrice' );

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

    error_log( 'RExtAccommodationController: '.print_r( $valuesArray, true ) );
    return $valuesArray;
  }

  public function prefixArrayKeys( $valuesArray ) {
    $prefixArray = array();

    foreach( $valuesArray as $key => $value ) {
      $prefixArray[ $this->addPrefix( $key ) ] = $value;
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
    error_log( "RExtAccommodationController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'reservationURL' => array(
        'params' => array( 'label' => __( 'Hotel reservation URL' ) ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'reservationPhone' => array(
        'params' => array( 'label' => __( 'Hotel reservation phone' ) ),
        'rules' => array( 'maxlength' => 200 )
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
        'params' => array( 'label' => __( 'Accommodation type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationType' )
        )
      ),
      'accommodationCategory' => array(
        'params' => array( 'label' => __( 'Accommodation category' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationCategory' )
        )
      ),
      'accommodationServices' => array(
        'params' => array( 'label' => __( 'Accommodation services' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationServices' )
        )
      ),
      'accommodationFacilities' => array(
        'params' => array( 'label' => __( 'Accommodation facilities' ), 'type' => 'select',
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

    $form->setField( 'rExtAccommodationFieldNames', array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );
    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( $form ) {
    error_log( "RExtAccommodationController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( $form, $resource ) {
    error_log( "RExtAccommodationController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray() );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $rExtModel = new AccommodationModel( $valuesArray );
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
    error_log( "RExtAccommodationController: resFormSuccess()" );

  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */



} // class RExtAccommodationController
