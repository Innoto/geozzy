<?php
rextAppEspazoNatural::autoIncludes();


class RTypeAppEspazoNaturalController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ) {
    error_log( 'RTypeAppEspazoNaturalController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppEspazoNatural() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppEspazoNaturalController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextAppEspazoNatural';
    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    return( $rTypeFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeAppEspazoNaturalController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
      $this->rExtCtrl->resFormRevalidate( $form );
    }
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppEspazoNaturalController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppEspazoNaturalController: resFormSuccess()" );

    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $this->rExtCtrl->resFormSuccess( $form, $resource );
  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeAppEspazoNaturalController: getViewBlock()" );
    $template = false;

    $template = $resBlock;
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppEspazoNatural' );

    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $rExtBlock = $this->rExtCtrl->getViewBlock( $resBlock );

    if( $rExtBlock ) {
      $template->addToBlock( 'rextAppEspazoNatural', $rExtBlock );
      $template->assign( 'rExtBlockNames', array( 'rextAppEspazoNatural' ) );
    }
    else {
      $template->assign( 'rextAppEspazoNatural', false );
      $template->assign( 'rExtBlockNames', false );
    }

    return $template;
  }

} // class RTypeAppEspazoNaturalController
