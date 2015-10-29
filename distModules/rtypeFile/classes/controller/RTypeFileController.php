<?php
rextFile::autoIncludes();


class RTypeFileController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeFileController::__construct' );

    parent::__construct( $defResCtrl, new rtypeFile() );
  }


  private function newRExtContr() {

    return new RExtFileController( $this );
  }

  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeFileController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextFile';
    $this->rExtCtrl = $this->newRExtContr();
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

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
    // error_log( "RTypeFileController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormFileAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeFileController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeFileController: resFormSuccess()" );

    $this->rExtCtrl = $this->newRExtContr();
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeFileController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeFile' );

    $this->rExtCtrl = $this->newRExtContr();
    $urlBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $urlBlock ) {
      $template->addToBlock( 'rextFile', $urlBlock );
      $template->assign( 'rExtBlockNames', array( 'rextFile' ) );
    }
    else {
      $template->assign( 'rextFile', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeFileController
