<?php
Cogumelo::load('coreView/View.php');
geozzy::load('controller/ResourceController.php');



class ResourceView extends View {

  public $defResCtrl = null;
  public $rTypeCtrl = null;

  public function __construct( $defResCtrl = false ){
    // error_log( 'ResourceView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct();

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();

    $this->defResCtrl = ( $defResCtrl ) ? $defResCtrl : new ResourceController();
  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

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
  //   error_log( __CLASS__.': getFormBlock(,,): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

  //   $form = $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );

  //   $template = $this->defResCtrl->formToTemplate( $form );

  //   return( $template );
  // } // function getFormBlock()







  /**
   * Defino el formulario de edición y creo su Bloque con su TPL
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $successArray array Opcional: Respuestas del form
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Template
   **/
  public function getFormBlockInfo( $formName, $urlAction, $successArray = false, $valuesArray = false ) {
    // error_log( __CLASS__.': getFormBlockInfo(,,,): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    $formBlockInfo = $this->defResCtrl->getFormBlockInfo( $formName, $urlAction, $successArray, $valuesArray );
    return $formBlockInfo;
  }






  /**
   * Action del formulario de edición
   */
  public function actionResourceForm() {
    // error_log( "ResourceView: actionResourceForm()" );
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $this->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $this->actionResourceFormProcess( $form );
    }else{
      // error_log( "entro: actionResourceForm()" );
      $form->addFormError('Error en el formulario', 'formErrors');
    }
    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->actionResourceFormSuccess( $form, $resource );
  } // function actionResourceForm()



  /**
   * Process del Action del formulario
   */
  public function actionResourceFormProcess( $form ) {
    // error_log( "ResourceView: actionResourceFormProcess()" );
    $resource = null;


    if( !$form->existErrors() ) {
      // Validaciones extra previas a usar los datos del recurso base
      $this->defResCtrl->resFormRevalidate( $form );
    }

    if( !$form->existErrors() ) {
      $this->rTypeCtrl = $this->defResCtrl->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );
    }

    // Validaciones extra previas de elementos externos al recurso base
    if( $this->rTypeCtrl && !$form->existErrors() ) {
      $this->rTypeCtrl->resFormRevalidate( $form );
    }


    if( !$form->existErrors() ) {
      // Creación-Edición-Borrado de los elementos del recurso base
      $resource = $this->defResCtrl->resFormProcess( $form );
    }

    // Creación-Edición-Borrado de los elementos externos al recurso base
    if( $this->rTypeCtrl && !$form->existErrors() ) {
      $this->rTypeCtrl->resFormProcess( $form, $resource );
    }


    if( !$form->existErrors()) {
      // Volvemos a guardar el recurso por si ha sido alterado por alguno de los procesos previos
      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    // Preparaciones del Success de los elementos externos al recurso base
    if( $this->rTypeCtrl && !$form->existErrors() ) {
      $this->rTypeCtrl->resFormSuccess( $form, $resource );
    }


    return $resource;
  } // function actionResourceFormProcess()



  /**
   * Success del Action del formulario
   */
  public function actionResourceFormSuccess( $form, $resource, $disableResponse = false ) {
    // error_log( "ResourceView: actionResourceFormSuccess()" );

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->defResCtrl->resFormSuccess( $form, $resource );

    if( !$disableResponse ) {
      // Send Header and Json
      $form->sendJsonResponse();
    }
  } // function actionResourceFormSuccess()





  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "ResourceView: getViewBlockInfo( $resId )" );

    $resViewBlockInfo = $this->defResCtrl->getViewBlockInfo( $resId );

    return $resViewBlockInfo;
  }

} // class ResourceView extends Vie
