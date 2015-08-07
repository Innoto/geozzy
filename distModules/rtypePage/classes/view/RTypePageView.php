<?php
Cogumelo::load('coreView/View.php');
rtypePage::load('controller/RTypePageController.php');



class RTypePageView extends View
{

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir );

    $this->defResCtrl = $defResCtrl;
    $this->rTypeCtrl = new RTypePageController( $defResCtrl );
  }



  /**
    Defino un formulario con su TPL como Bloque
   */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    error_log( "RTypePageView: getFormBlock()" );

    $form = $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );

    $this->template->assign( 'formOpen', $form->getHtmpOpen() );

    $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function getFormBlock()



  /**
    Proceso formulario
   */
  public function actionResourceForm() {
    error_log( "RTypePageView: actionResourceForm()" );

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $this->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validaciones extra previas a usar los datos del recurso base
      $this->defResCtrl->resFormRevalidate( $form );
    }

    // Opcional: Validaciones extra previas de elementos externos al recurso base

    if( !$form->existErrors() ) {
      // Creación-Edición-Borrado de los elementos del recurso base
      $resource = $this->defResCtrl->resFormProcess( $form );
    }

    // Opcional: Creación-Edición-Borrado de los elementos externos al recurso base

    if( !$form->existErrors()) {
      // Volvemos a guardar el recurso por si ha sido alterado por alguno de los procesos previos
      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->defResCtrl->resFormSuccess( $form, $resource );
  } // function actionResourceForm()


} // class RTypePageView
