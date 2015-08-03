<?php
rextEatAndDrink::autoIncludes();


class RTypeRestaurantController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rExts = false;

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeRestaurantController::__construct' );

    $this->defResCtrl = $defResCtrl;
    //error_log( 'this->defResCtrl '.print_r( $this->defResCtrl, true ) );

    $this->rTypeModule = new rtypeRestaurant();
    if( property_exists( $this->rTypeModule, 'rext' ) && is_array( $this->rTypeModule->rext )
      && count( $this->rTypeModule->rext ) > 0 )
    {
      $this->rExts = $this->rTypeModule->rext;
    }
  }

  public function getRTypeValues( $resId ) {
    error_log( "RTypeRestaurantController: getRTypeValues()" );
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
    error_log( "RTypeRestaurantController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextEatAndDrink';
    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $rExtFieldNames = $this->eatCtrl->manipulateForm( $form );


    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Valadaciones extra
    // $form->setValidationRule( 'restaurantName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( $form ) {
    error_log( "RTypeRestaurantController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( $form, $resource ) {
    error_log( "RTypeRestaurantController: resFormProcess()" );
    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );

      $this->eatCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( $form, $resource ) {
    error_log( "RTypeRestaurantController: resFormSuccess()" );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $this->eatCtrl->resFormSuccess( $form, $resource );
  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */







  /**
    Visualizamos el Recurso
  */
  public function getViewBlock( $resObj, $resBlock ) {
    error_log( "RTypeRestaurantController: getViewBlock()" );
    $template = false;

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $accomBlock = $this->eatCtrl->getViewBlock( $resObj, $resBlock );

    if( $accomBlock ) {
      $template = $resBlock;
      $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeRestaurant' );

      $template->addToBlock( 'rextEatAndDrink', $accomBlock );

      $template->assign( 'rExtBlockNames', array( 'rextEatAndDrink' ) );
      $template->assign( 'rExtFieldNames', false );
    }

    return $template;
  } // function getViewBlock( $resObj )

} // class RTypeRestaurantController
