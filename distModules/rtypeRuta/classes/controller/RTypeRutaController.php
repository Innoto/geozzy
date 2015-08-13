<?php


class RTypeRutaController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeRutaController::__construct' );

    parent::__construct( $defResCtrl, new rtypeRuta() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeRutaController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeRutaController: resFormRevalidate()" );

  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeRutaController: resFormProcess()" );

  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeRutaController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    // error_log( "RTypeRutaController: getViewBlock()" );
    $template = false;

    $template = $resBlock;

    return $template;
  }

} // class RTypeRutaController
