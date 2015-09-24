<?php
rextAppLugar::autoIncludes();


class RTypeAppLugarController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeAppLugarController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppLugar() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppLugarController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextAppLugar';
    $this->rExtCtrl = new RExtAppLugarController( $this );
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeAppLugarController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppLugarController( $this );
      $this->rExtCtrl->resFormRevalidate( $form );
    }
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppLugarController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppLugarController( $this );
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppLugarController: resFormSuccess()" );

    $this->rExtCtrl = new RExtAppLugarController( $this );
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeAppLugarController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppLugar' );

    $this->rExtCtrl = new RExtAppLugarController( $this );
    $rExtBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $rExtBlock ) {
      $template->addToBlock( 'rextAppLugar', $rExtBlock );
      $template->assign( 'rExtBlockNames', array( 'rextAppLugar' ) );
    }
    else {
      $template->assign( 'rextAppLugar', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeAppLugarController
