<?php


interface RTypeInterface {

  // Defino el formulario
  public function manipulateForm( FormController $form );

  // Defino la visualizacion del formulario
  public function manipulateFormTemplate( FormController $form, Template $template );

  // Validaciones extra previas a usar los datos del recurso base
  public function resFormRevalidate( FormController $form );

  // Creación-Edición-Borrado de los elementos del recurso base. Iniciar transaction
  public function resFormProcess( FormController $form, ResourceModel $resource );

  // Enviamos el OK-ERROR a la BBDD y al formulario. Finalizar transaction
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  // Visualizamos el Recurso
  public function getViewBlock( Template $resBlock );

} // interface RTypeInterface


class RTypeController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rExts = false;

  public function __construct( $defResCtrl, $rTypeModule ){
    error_log( 'RTypeController::__construct' );

    $this->defResCtrl = $defResCtrl;
    // error_log( 'this->defResCtrl '.print_r( $this->defResCtrl, true ) );

    $this->rTypeModule = $rTypeModule;
    if( property_exists( $this->rTypeModule, 'rext' ) && is_array( $this->rTypeModule->rext )
      && count( $this->rTypeModule->rext ) > 0 )
    {
      $this->rExts = $this->rTypeModule->rext;
    }
  }

  public function manipulateFormTemplate( FormController $form, Template $template ) {
    return( $template );
  }


} // class RTypeController
