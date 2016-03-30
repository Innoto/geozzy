<?php
Cogumelo::load('coreView/View.php');
rtypeEvent::load('controller/RTypeEventController.php');
geozzy::load('controller/ResourceController.php');


class RTypeEventView extends View
{

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir = false );

    $this->defResCtrl = $defResCtrl;
    $this->rTypeCtrl = new RTypeEventController( $defResCtrl );
  }

  function accessCheck() {

    return true;

  }

  /**
    Defino un formulario con su TPL como Bloque
   */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "RTypeEventView: getFormBlock()" );

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
    // error_log( "RTypeEventView: actionResourceForm()" );

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




  /**
   * Creacion de formulario de microevento
   */
  public function createForm( $urlParams = false ) {
    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('resource:create', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $rtypeControl = new ResourcetypeModel();
    $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'idName' => 'rtypeEvent' ) ) )->fetch();

    if( $rTypeItem ) {
      $recursoData = array();
      $recursoData['rTypeId'] = $rTypeItem->getter('id');
      $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
      $recursoData['typeReturn'] = $rTypeItem->getter('id');
    }


    /*
    $recursoData = false;
    $validation = array( 'resourcetype' => '#^\d+$#' );
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    if( $urlParamsList ) {
      $rTypeItem = false;

      if( isset( $urlParamsList['resourcetype'] ) ) {
        $urlParamRtype = $urlParamsList['resourcetype'];
        $rtypeControl = new ResourcetypeModel();
        $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $urlParamRtype ) ) )->fetch();
      }

      if( $rTypeItem ) {
        $recursoData = array();
        $recursoData['rTypeId'] = $rTypeItem->getter('id');
        $recursoData['rTypeIdName'] = $rTypeItem->getter('idName');
        $recursoData['typeReturn'] = $urlParamsList['resourcetype'];

      }
    }*/

    $this->resourceShowForm( 'eventCreate', '/rtypeEvent/event/sendevent', $recursoData );
  } // function resourceForm()


  public function resourceShowForm( $formName, $urlAction, $valuesArray = false, $resCtrl = false ) {

    if( !$resCtrl ) {
      $resCtrl = new ResourceController();
    }


    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $valuesArray );
    $formBlockInfo['template']['adminFull']->exec();
  }


  /**
   * Edicion de Recursos
   */
  public function editForm( $urlParams = false ) {
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('resource:edit', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    $recursoData = false;
    $urlParamTopic = false;
    $topicItem = false;
    $typeItem = false;

    /* Validamos os parámetros da url e obtemos un array de volta*/
    $validation = array( 'resourceId'=> '#^\d+$#');
    $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    $resCtrl = new ResourceController();
    $recursoData = $resCtrl->getResourceData( $urlParamsList['resourceId'] );

    $recursoData['typeReturn'] = 'rtypeEvent';

    if( $recursoData ) {
      $this->resourceShowForm( 'resourceEdit', '/admin/resource/sendresource', $recursoData, $resCtrl );
    }
    else {
      cogumelo::error( 'Imposible acceder al recurso indicado.' );
    }
  } // function resourceEditForm()


  public function sendForm(){

  }

} // class RTypeEventView
