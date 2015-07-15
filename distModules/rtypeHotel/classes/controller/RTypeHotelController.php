<?php
rextAccommodation::autoIncludes();


class RTypeHotelController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rExts = false;

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeHotelController::__construct' );

    $this->defResCtrl = $defResCtrl;
    //error_log( 'this->defResCtrl '.print_r( $this->defResCtrl, true ) );

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
    $this->accomCtrl = new RExtAccommodationController( $this );
    $rExtFieldNames = $this->accomCtrl->manipulateForm( $form );
    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( $form ) {
    error_log( "RTypeHotelController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->accomCtrl = new RExtAccommodationController( $this );
      $this->accomCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( $form, $resource ) {
    error_log( "RTypeHotelController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->accomCtrl = new RExtAccommodationController( $this );
      $this->accomCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( $form, $resource ) {
    error_log( "RTypeHotelController: resFormSuccess()" );

    $this->accomCtrl = new RExtAccommodationController( $this );
    $this->accomCtrl->resFormSuccess( $form, $resource );
  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */







  /**
    Visualizamos el Recurso
  */
  public function getViewBlock( $resObj, $resBlock ) {
    error_log( "RTypeHotelController: getViewBlock()" );
    $template = false;

    $this->accomCtrl = new RExtAccommodationController( $this );
    $accomBlock = $this->accomCtrl->getViewBlock( $resObj, $resBlock );

    if( $accomBlock ) {
      $template = $resBlock;
      $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeHotel' );

      $template->addToBlock( 'rextAccommodation', $accomBlock );

      $template->assign( 'rExtBlockNames', array( 'rextAccommodation' ) );
      $template->assign( 'rExtFieldNames', false );
    }

    return $template;
  } // function getViewBlock( $resObj )

} // class RTypeHotelController
