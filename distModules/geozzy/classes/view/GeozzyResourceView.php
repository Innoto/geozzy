<?php
Cogumelo::load('coreView/View.php');
geozzy::load('controller/ResourceController.php');



class GeozzyResourceView extends View
{

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();

    $this->defResCtrl = new ResourceController();
  }

  /**
     Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }



  /**
     Defino el formulario
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Form
   **/
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormObj()" );

    $form = $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );

    $this->loadRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );

    if( $this->rTypeCtrl ) {
      $rTypeFieldNames = $this->rTypeCtrl->manipulateForm( $form );
      error_log( 'rTypeFieldNames: '.print_r( $rTypeFieldNames, true ) );
    }

    // Una vez que lo tenemos completamente definido, guardamos el form en sesion
    $form->saveToSession();

    return $form;
  } // function getFormObj()


  /**
     Defino el formulario y creo su Bloque con su TPL
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Template
   **/
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormBlock()" );

    $rTypeFieldNames = array();

    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $this->template->assign( 'formOpen', $form->getHtmpOpen() );

    $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $this->template->assign( 'rTypeName', $form->getFieldValue( 'rTypeName' ) );
    $this->template->assign( 'rTypeFieldNames', $form->getFieldValue( 'rTypeFieldNames' ) );

    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function getFormBlock()


  /**
     Action del formulario
   **/
  public function actionResourceForm() {
    error_log( "GeozzyResourceView: actionResourceForm()" );
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $this->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validaciones extra previas a usar los datos del recurso base
      $this->defResCtrl->resFormRevalidate( $form );
    }

    $this->loadRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );

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

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->defResCtrl->resFormSuccess( $form, $resource );
  } // function actionResourceForm()





  /**
     Cargando controlador del RType
   **/
  public function loadRTypeCtrl( $rTypeId ) {
    error_log( "GeozzyResourceView: loadRTypeCtrl( $rTypeId )" );

    switch( $rTypeId ) {
      case 20: // 'rtypeHotel'
        rtypeHotel::autoIncludes();
        $this->rTypeCtrl = new RTypeHotelController( $this->defResCtrl );
        break;
      /*
      case 'rtypeRestaurant':
        rtypeHotel::autoIncludes();
        $this->rTypeCtrl = new RTypeRestaurantController( $this->defResCtrl );
        break;
      */
      default:
        $this->rTypeCtrl = false;
        break;
    }
  }

} // class ResourceView extends Vie
