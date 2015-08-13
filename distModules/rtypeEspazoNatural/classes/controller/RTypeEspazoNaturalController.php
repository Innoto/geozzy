<?php


class RTypeEspazoNaturalController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeEspazoNaturalController::__construct' );

    parent::__construct( $defResCtrl, new rtypeEspazoNatural() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeEspazoNaturalController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeEspazoNaturalController: resFormRevalidate()" );

  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeEspazoNaturalController: resFormProcess()" );

  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeEspazoNaturalController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    // error_log( "RTypeEspazoNaturalController: getViewBlock()" );
    $template = false;

    $template = $resBlock;

    return $template;
  }

} // class RTypeEspazoNaturalController
