<?php
interface RTypeViewInterface {
  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck();

  /**
   * Defino el formulario de edición y creo su Bloque con su TPL
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Template
   **/
  // public function getFormBlock( $formName, $urlAction, $valuesArray = false );

  /**
   * Action del formulario de edición
   */
  // public function actionResourceForm();

  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId = false );
}



class RTypeViewCore extends View {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rTypeCtrl = null;
  public $rTypeName = 'RTypeNameUnknown';

  public function __construct( ResourceController $defResCtrl, Module $rTypeModule ) {
    error_log( 'RTypeViewCore: __construct() para '.$rTypeModule->name.' - '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

    $this->defResCtrl = $defResCtrl;
    $rTypeName = $this->rTypeName = $rTypeModule->name;
    $this->rTypeModule = $rTypeModule;

    parent::__construct();

    $rTypeCtrlClassName = 'RT'.mb_strcut( $rTypeName, 2 ).'Controller';
    $rTypeName::load( 'controller/'.$rTypeCtrlClassName.'.php' );
    $this->rTypeCtrl = new $rTypeCtrlClassName( $defResCtrl );
  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {
    error_log( 'RTypeViewCore: accessCheck() para '.$this->rTypeName );

    return true;
  }

  // /**
  //  * Defino el formulario de edición y creo su Bloque con su TPL
  //  *
  //  * @param $formName string Nombre del form
  //  * @param $urlAction string URL del action
  //  * @param $valuesArray array Opcional: Valores de los campos del form
  //  *
  //  * @return Obj-Template
  //  **/
  // public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
  //   error_log( 'RTypeViewCore: getFormBlock() para '.$this->rTypeName );

  //   $form = $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );

  //   $this->template->assign( 'formOpen', $form->getHtmpOpen() );

  //   $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

  //   $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

  //   $this->template->assign( 'formClose', $form->getHtmlClose() );
  //   $this->template->assign( 'formValidations', $form->getScriptCode() );

  //   $this->template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

  //   return( $this->template );
  // } // function getFormBlock()

  // /**
  //  * Action del formulario de edición
  //  */
  // public function actionResourceForm() {
  //   error_log( 'RTypeViewCore: actionResourceForm() para '.$this->rTypeName );

  //   // Se construye el formulario con sus datos y se realizan las validaciones que contiene
  //   $form = $this->defResCtrl->resFormLoad();

  //   if( !$form->existErrors() ) {
  //     // Validaciones extra previas a usar los datos del recurso base
  //     $this->defResCtrl->resFormRevalidate( $form );
  //   }

  //   // Opcional: Validaciones extra previas de elementos externos al recurso base

  //   if( !$form->existErrors() ) {
  //     // Creación-Edición-Borrado de los elementos del recurso base
  //     $resource = $this->defResCtrl->resFormProcess( $form );
  //   }

  //   // Opcional: Creación-Edición-Borrado de los elementos externos al recurso base

  //   if( !$form->existErrors()) {
  //     // Volvemos a guardar el recurso por si ha sido alterado por alguno de los procesos previos
  //     $saveResult = $resource->save();
  //     if( $saveResult === false ) {
  //       $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
  //     }
  //   }

  //   // Enviamos el OK-ERROR a la BBDD y al formulario
  //   $this->defResCtrl->resFormSuccess( $form, $resource );
  // } // function actionResourceForm()

  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    error_log( 'RTypeViewCore: getViewBlockInfo('.$resId.') para '.$this->rTypeName );

    $resViewBlockInfo = $this->rTypeCtrl->getViewBlockInfo( $resId );

    return $resViewBlockInfo;
  }

} // class RTypeViewCore extends View
