<?php


class RTypeAppFestaPopularController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeAppFestaPopularController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppFestaPopular() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppFestaPopularController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeAppFestaPopularController: resFormRevalidate()" );

  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppFestaPopularController: resFormProcess()" );

  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppFestaPopularController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeAppFestaPopularController: getViewBlock()" );
    $template = false;

    $template = $resBlock;

    return $template;
  }

} // class RTypeAppFestaPopularController
