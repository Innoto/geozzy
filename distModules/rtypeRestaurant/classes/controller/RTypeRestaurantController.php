<?php
rextEatAndDrink::autoIncludes();
rextContact::autoIncludes();

class RTypeRestaurantController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeRestaurantController::__construct' );

    parent::__construct( $defResCtrl, new rtypeRestaurant() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeRestaurantController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextEatAndDrink';
    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $rExtFieldNames = $this->eatCtrl->manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión contacto
    $rTypeExtNames[] = 'rextContact';
    $this->contactCtrl = new RExtContactController( $this );
    $rExtFieldNames = $this->contactCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    $form->setFieldParam( 'externalUrl', 'label', __( 'Restaurant home URL' ) );

    // Valadaciones extra
    // $form->setValidationRule( 'restaurantName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeRestaurantController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormRevalidate( $form );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeRestaurantController: resFormProcess()" );
    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormProcess( $form, $resource );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeRestaurantController: resFormSuccess()" );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $this->eatCtrl->resFormSuccess( $form, $resource );

    $this->contactCtrl = new RExtContactController( $this );
    $this->contactCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeRestaurantController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeRestaurant' );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $eatBlock = $this->eatCtrl->getViewBlock( $resBlock );

    $this->contactCtrl = new RExtContactController( $this );
    $contactBlock = $this->contactCtrl->getViewBlock( $resBlock );

    if( $eatBlock ) {
      $template->addToBlock( 'rextEatAndDrink', $eatBlock );
      $template->assign( 'rExtBlockNames', array( 'rextEatAndDrink' ) );
    }
    else {
      $template->assign( 'rextEatAndDrink', false );
      $template->assign( 'rExtBlockNames', false );
    }

    if( $contactBlock ) {
      $template->addToBlock( 'rextContact', $contactBlock );
      $template->assign( 'rExtContactBlockNames', array( 'rextContact' ) );
    }
    else {
      $template->assign( 'rextContact', false );
      $template->assign( 'rExtContactBlockNames', false );
    }


    return $template;
  }

} // class RTypeRestaurantController
