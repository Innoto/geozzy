<?php


class RTypeHotelController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rExts = false;

  public function __construct( $defResCtrl = null ){
    error_log( 'RTypeHotelController::__construct' );

    $this->defResCtrl = $defResCtrl;

    $this->rTypeModule = new rtypeHotel();
    if( property_exists( $this->rTypeModule, 'rext' ) && is_array( $this->rTypeModule->rext )
      && count( $this->rTypeModule->rext ) > 0 )
    {
      $this->rExts = $this->rTypeModule->rext;
    }
  }

  public function getRTypeValues( $resId ) {
    error_log( "RTypeHotelController: getRTypeValues()" );
    $valuesArray = false;

    if( $resId && is_integer( $resId ) ) {
      $valuesArray = false;
    }

    return $valuesArray;
  }


  /**
    Defino el formulario
   **/
  public function manipulateForm( $form ) {
    error_log( "RTypeHotelController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextAccommodation';
    rextAccommodation::autoIncludes();
    $this->accomCtrl = new RExtAccommodationController( $this );
    $rExtFieldNames = $this->accomCtrl->manipulateForm( $form );
    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );
    error_log( 'rextAccommodation FieldNames: '.print_r( $rExtFieldNames, true ) );


    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );


    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( $form ) {
    error_log( "RTypeHotelController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( $form, $resource ) {
    error_log( "RTypeHotelController: resFormProcess()" );

    /*
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
    */

  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( $form, $resource ) {
    error_log( "RTypeHotelController: resFormSuccess()" );

  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */



} // class RTypeHotelController
