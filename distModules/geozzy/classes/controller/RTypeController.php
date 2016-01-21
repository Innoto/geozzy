<?php


interface RTypeInterface {

  // Defino el formulario
  public function manipulateForm( FormController $form );

  // Defino la visualizacion del formulario
  public function manipulateFormTemplate( FormController $form, Template $template );

  public function manipulateAdminFormColumns( Template $formBlock, Template $template, AdminViewResource $adminViewResource, Array $adminColsInfo );

  // Validaciones extra previas a usar los datos del recurso base
  public function resFormRevalidate( FormController $form );

  // Creación-Edición-Borrado de los elementos del recurso base. Iniciar transaction
  public function resFormProcess( FormController $form, ResourceModel $resource );

  // Enviamos el OK-ERROR a la BBDD y al formulario. Finalizar transaction
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  // Visualizamos el Recurso
  public function getViewBlock( Template $resBlock );

  // Preparamos los datos para visualizar el Recurso
  public function getViewBlockInfo();

} // interface RTypeInterface


class RTypeController {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rExts = false;
  public $rTypeName = 'rType';


  public function __construct( $defResCtrl, $rTypeModule ){
    // error_log( 'RTypeController::__construct' );

    $this->defResCtrl = $defResCtrl;
    // error_log( 'this->defResCtrl '.print_r( $this->defResCtrl, true ) );

    $this->rTypeName = $rTypeModule->name;

    $this->rTypeModule = $rTypeModule;
    if( property_exists( $rTypeModule, 'rext' ) && is_array( $rTypeModule->rext )
      && count( $rTypeModule->rext ) > 0 )
    {
      $this->rExts = $rTypeModule->rext;
    }
  }

  public function manipulateFormTemplate( FormController $form, Template $template ) {
    return( false );
  }

  public function manipulateAdminFormColumns( Template $formBlock, Template $template, AdminViewResource $adminViewResource, Array $adminColsInfo ) {
    return( false );
  }

} // class RTypeController
