<?php
rextEatAndDrink::autoIncludes();


class RTypeRestaurantController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeRestaurantController::__construct' );

    parent::__construct( $defResCtrl, new rtypeRestaurant() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
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
  public function resFormRevalidate( FormController $form ) {
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
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
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
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    error_log( "RTypeRestaurantController: resFormSuccess()" );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $this->eatCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    error_log( "RTypeRestaurantController: getViewBlock()" );
    $template = false;

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $accomBlock = $this->eatCtrl->getViewBlock( $resource, $resBlock );

    if( $accomBlock ) {
      $template = $resBlock;
      $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeRestaurant' );

      $template->addToBlock( 'rextEatAndDrink', $accomBlock );

      $template->assign( 'rExtBlockNames', array( 'rextEatAndDrink' ) );
      $template->assign( 'rExtFieldNames', false );
    }

    return $template;
  }

} // class RTypeRestaurantController
