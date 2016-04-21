<?php
Cogumelo::load('coreView/View.php');
rtypePoi::load('controller/RTypePoiController.php');
geozzy::load('controller/ResourceController.php');
geozzy::load( 'view/GeozzyResourceView.php' );


class RTypePoiView extends View
{

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir = false );

    $this->defResCtrl = $defResCtrl;
    $this->rTypeCtrl = new RTypePoiController( $defResCtrl );
  }

  function accessCheck() {

    return true;

  }

  /**
    Defino un formulario con su TPL como Bloque
   */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "RTypePoiView: getFormBlock()" );

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
    // error_log( "RTypePoiView: actionResourceForm()" );

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
   * Creacion de formulario de microPoio
   */
  public function createModalForm( $urlParams = false ) {

    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    $formName = 'PoiCreate';
    $urlAction = '/rtypePoi/Poi/sendPoi';

    $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypePoi') ) )->fetch();
    $valuesArray['rTypeId'] = $rtype->getter('id');

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, false, $valuesArray );
    $form = $formBlockInfo['objForm'];

    $form->setFieldParam('published', 'type', 'reserved');
    $form->setFieldParam('published', 'value', '1');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach ($urlAliasLang as $key => $field) {
      $form->removeField( $field);
      $form->removeValidationRules($field);
    }
    $form->removeField('externalUrl');
    $form->removeValidationRules('published');

    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    $formBlockInfo['template']['miniFormModal']->addToBlock('rextPoiBlock', $formBlockInfo['ext']['rextPoi']['template']['full']);
    $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
    $formBlockInfo['template']['miniFormModal']->exec();
  }


  /**
   * Edicion de Recursos
   */
  public function editModalForm( $urlParams = false ) {

    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    user::load('controller/UserAccessController.php');
    $useraccesscontrol = new UserAccessController();
    $access = $useraccesscontrol->checkPermissions('resource:edit', 'admin:full');
    if(!$access){
      cogumelo::redirect("/admin/403");
      exit;
    }

    /* Validamos os parámetros da url e obtemos un array de volta*/
    $formName = 'PoiEdit';
    $urlAction = '/rtypePoi/Poi/sendPoi';

    if( isset( $urlParams['1'] ) ) {
      $idResource = $urlParams['1'];
      $resourceModel = new ResourceModel();
      $resourceList = $resourceModel->listItems(array( 'affectsDependences' =>
        array( 'PoiModel', 'FiledataModel' ),
        'filters' => array( 'id' => $idResource ) ));
      $resource = $resourceList->fetch();
    }

    if( $resource ) {
      $resourceData = $resource->getAllData();

      $resourceData = $resourceData[ 'data' ];

      // Cargo los datos de image dentro de los del collection
      $fileDep = $resource->getterDependence( 'image' );

      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $fileData = $fileModel->getAllData();
          $resourceData[ 'image' ] = $fileData[ 'data' ];
        }
      }

      $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypePoi') ) )->fetch();
      $resourceData['rTypeId'] = $rtype->getter('id');

      $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, false, $resourceData );

      $form = $formBlockInfo['objForm'];

      $urlAliasLang = $form->multilangFieldNames('urlAlias');
      foreach ($urlAliasLang as $key => $field) {
        $form->removeField( $field);
        $form->removeValidationRules($field);
      }

      // Cambiamos el template del formulario
      $formBlockInfo['template']['miniFormModal']->addToBlock('rextPoiBlock', $formBlockInfo['ext']['rextPoi']['template']['full']);
      $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
      $formBlockInfo['template']['miniFormModal']->exec();
    }
    else {
      cogumelo::error( 'Imposible acceder al Poio indicado.' );
    }


  } // function resourceEditForm()


/*
  public function resourceShowForm( $formName, $urlAction, $valuesArray = false, $resCtrl = false ) {

    if( !$resCtrl ) {
      $resCtrl = new ResourceController();
    }

    $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, $valuesArray );

    $form = $formBlockInfo['objForm'];
    $form->setFieldParam('published', 'type', 'reserved');
    $form->setFieldParam('published', 'value', '1');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach ($urlAliasLang as $key => $field) {
      $form->removeField( $field);
      $form->removeValidationRules($field);
    }
    $form->removeValidationRules('published');

    $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
    $formBlockInfo['template']['miniFormModal']->exec();
  }
*/




  public function sendModalResourceForm() {

    $resourceView = new GeozzyResourceView();
    $resource = null;

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $resourceView->defResCtrl->resFormLoad();

    if( !$form->existErrors() ) {
      // Validar y guardar los datos
      $resource = $resourceView->actionResourceFormProcess( $form );

    }

    if( !$form->existErrors() ) {

      $resCtrl = new ResourceController();

      $form->removeSuccess( 'redirect' );
      $form->setSuccess( 'jsEval', ' successResourceForm( { '.
        ' id : "'.$resource->getter('id').'",'.
        ' title: "'.$resource->getter('title_'.$form->langDefault).'" });'
      );
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $resourceView->actionResourceFormSuccess( $form, $resource );
  }

} // class RTypePoiView
