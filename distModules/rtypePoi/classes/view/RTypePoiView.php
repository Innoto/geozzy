<?php
geozzy::load('view/RTypeViewCore.php');
geozzy::load('controller/ResourceController.php');
geozzy::load( 'view/GeozzyResourceView.php' );


class RTypePoiView extends RTypeViewCore implements RTypeViewInterface {

  public function __construct( $defResCtrl = null ) {
    // error_log( 'RTypePoiView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    if( ! is_object($defResCtrl)) {
      $defResCtrl = new ResourceController();
    }

    parent::__construct( $defResCtrl, new rtypePoi() );
  }


  /**
   * Creacion de formulario de microPoio
   */
  public function createModalForm( $urlParams = false ) {

    $resCtrl = new ResourceController();
    $rtypeModel = new ResourcetypeModel();

    $formName = 'poiCreate';
    $urlAction = '/rtypePoi/poi/sendPoi';

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

    $formBlockInfo['template']['miniFormModal']->addToFragment('rextPoiBlock', $formBlockInfo['ext']['rextPoi']['template']['adminExt']);
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
    $urlAction = '/rtypePoi/poi/sendPoi';

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

      $rtypeRes = $rtypeModel->listItems( array( 'filters' => array('id' => $resourceData['rTypeId']) ) )->fetch();
      $rtype = $rtypeModel->listItems( array( 'filters' => array('idName' => 'rtypePoi') ) )->fetch();

      $formBlockInfo = $resCtrl->getFormBlockInfo( $formName, $urlAction, false, $resourceData );

      $form = $formBlockInfo['objForm'];

      $urlAliasLang = $form->multilangFieldNames('urlAlias');
      foreach ($urlAliasLang as $key => $field) {
        $form->removeField( $field);
        $form->removeValidationRules($field);
      }

      // Cambiamos el template del formulario
      if($resourceData['rTypeId'] === $rtype->getter('id')){ // rtypePoi
        $formBlockInfo['template']['miniFormModal']->addToFragment('rextPoiBlock', $formBlockInfo['ext']['rextPoi']['template']['adminExt']);
        $formBlockInfo['template']['miniFormModal']->assign( 'res', $formBlockInfo );
      }
      else { // otro recurso
        $formBlockInfo['template']['miniFormModal'] = new Template();
        $formBlockInfo['template']['miniFormModal']->assign( 'rtype', $rtypeRes->getter('name_'.$form->langDefault));
        $formBlockInfo['template']['miniFormModal']->setTpl('noEditable.tpl', 'rtypePoi');
      }
      $formBlockInfo['template']['miniFormModal']->exec();

    }
    else {
      cogumelo::error( 'Imposible acceder al POI indicado.' );
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
      $form->setSuccess( 'jsEval', ' rextPoiJs.successResourceForm( { '.
        ' id : "'.$resource->getter('id').'",'.
        ' title: "'.$resource->getter('title_'.$form->langDefault).'" });'
      );
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $resourceView->actionResourceFormSuccess( $form, $resource );
  }

} // class RTypePoiView
