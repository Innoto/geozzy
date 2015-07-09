<?php


class RExtAccommodationController {

  public $prefix = 'rExtAccommodation_';

  public $defRTypeCtrl = null;
  public $defResCtrl = null;
  public $rExtModule = null;
  public $taxonomies = false;

  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtAccommodationController::__construct' );

    $this->defRTypeCtrl = $defRTypeCtrl;
    $this->defResCtrl = $defRTypeCtrl->defResCtrl;

    $this->rExtModule = new rextAccommodation();
    if( property_exists( $this->rExtModule, 'taxonomies' ) && is_array( $this->rExtModule->taxonomies )
      && count( $this->rExtModule->taxonomies ) > 0 )
    {
      $this->taxonomies = $this->rExtModule->taxonomies;
    }
  }


  public function getRExtValues( $resId ) {
    error_log( "RExtAccommodationController: getRExtValues()" );
    $valuesArray = false;

    if( $resId && is_integer( $resId ) ) {
      $valuesArray = false;
    }

    return $valuesArray;
  }

  public function prefixArrayKeys( $valuesArray ) {
    $prefixArray = array();

    foreach( $valuesArray as $key => $value ) {
      $prefixArray[ $this->prefix . $key ] = $value;
    }

    return $prefixArray;
  }



  /**
    Defino el formulario
  */
  public function manipulateForm( $form ) {
    error_log( "RExtAccommodationController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'beds' => array(
        'params' => array( 'label' => __( 'Hotel beds' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'averagePrice' => array(
        'params' => array( 'label' => __( 'Hotel average price' ) ),
        'rules' => array( 'maxlength' => '100' )
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
    $valuesArray = $this->getRExtValues( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->prefix.'id', array( 'type' => 'reserved', 'value' => null ) );
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
      $valuesArray = $form->getValuesArray();
      $resId = $form->getFieldValue( 'id' );

      if( $resId && is_integer( $resId ) ) {
        $recModel = new ResourceModel();
        $recursosList = $recModel->listItems( array( 'affectsDependences' =>
          array( 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ResourceTaxonomytermModel', 'ExtraDataModel' ),
          'filters' => array( 'id' => $resId, 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1) ) );
        $recurso = $recursosList->fetch();

        $rTypeFilters = array( );
      }

    }

    if( !$form->existErrors() ) {
      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $resource = new ResourceModel( $valuesArray );
      if( $resource === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    if( !$form->existErrors()) {

      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
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
