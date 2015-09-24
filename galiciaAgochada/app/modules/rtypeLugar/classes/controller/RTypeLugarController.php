<?php
rextLugar::autoIncludes();


class RTypeLugarController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeLugarController::__construct' );

    parent::__construct( $defResCtrl, new rtypeLugar() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeLugarController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextLugar';
    $this->rExtCtrl = new RExtLugarController( $this );
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeLugarController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtLugarController( $this );
      $this->rExtCtrl->resFormRevalidate( $form );
    }
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeLugarController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtLugarController( $this );
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeLugarController: resFormSuccess()" );

    $this->rExtCtrl = new RExtLugarController( $this );
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeLugarController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeLugar' );

    $this->rExtCtrl = new RExtLugarController( $this );
    $rExtBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $rExtBlock ) {
      $template->addToBlock( 'rextLugar', $rExtBlock );
      $template->assign( 'rExtBlockNames', array( 'rextLugar' ) );
    }
    else {
      $template->assign( 'rextLugar', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeLugarController
