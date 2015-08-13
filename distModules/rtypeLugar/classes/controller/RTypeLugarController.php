<?php


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

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeLugarController: resFormRevalidate()" );

  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeLugarController: resFormProcess()" );

  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeLugarController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    // error_log( "RTypeLugarController: getViewBlock()" );
    $template = false;

    $template = $resBlock;

    return $template;
  }

} // class RTypeLugarController
