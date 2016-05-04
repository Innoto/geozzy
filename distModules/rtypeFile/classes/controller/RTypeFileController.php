<?php

class RTypeFileController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeFileController::__construct' );

    parent::__construct( $defResCtrl, new rtypeFile() );
  }

  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeFileController: manipulateForm()" );

    parent::manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

  } // function manipulateForm()

  public function getFormBlockInfo( FormController $form ) {
    // error_log( "RTypeHotelController: getFormBlockInfo()" );
    $formBlockInfo = parent::getFormBlockInfo( $form );

    $this->fileCtrl = new RExtFileController( $this );

    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'geozzy' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' ),
      $form->multilangFieldNames( 'mediumDescription' )
    );
    $formFieldsNames[] = 'externalUrl';
    $formFieldsNames[] = $this->fileCtrl->addPrefix( 'author' );
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel estado de publicacion
    $templates['publication'] = new Template();
    $templates['publication']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['publication']->assign( 'title', __( 'Publication' ) );
    $templates['publication']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'published', 'weight' );
    $templates['publication']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel SEO
    $templates['seo'] = new Template();
    $templates['seo']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['seo']->assign( 'title', __( 'SEO' ) );
    $templates['seo']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'urlAlias' ),
      $form->multilangFieldNames( 'headKeywords' ),
      $form->multilangFieldNames( 'headDescription' ),
      $form->multilangFieldNames( 'headTitle' )
    );
    $templates['seo']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel image
    $templates['image'] = new Template();
    $templates['image']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['image']->assign( 'title', __( 'Select a image' ) );
    $templates['image']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'image' );
    $templates['image']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel image
    $templates['file'] = new Template();
    $templates['file']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['file']->assign( 'title', __( 'Multimedia file' ) );
    $templates['file']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $this->fileCtrl->addPrefix( 'file' );
    $templates['file']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel image
    $templates['image'] = new Template();
    $templates['image']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['image']->assign( 'title', __( 'Select a image' ) );
    $templates['image']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'image' );
    $templates['image']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel cuadro informativo
    $templates['info'] = new Template();
    $templates['info']->setTpl( 'rTypeFormInfoPanel.tpl', 'geozzy' );
    $templates['info']->assign( 'title', __( 'Information' ) );
    $templates['info']->assign( 'res', $formBlockInfo );

    $resourceType = new ResourcetypeModel();
    $type = $resourceType->listItems(array('filters' => array('id' => $formBlockInfo['data']['rTypeId'])))->fetch();
    if ($type){
      $templates['info']->assign( 'rType', $type->getter('name_es') );
    }
    $timeCreation = date('d/m/Y', time($formBlockInfo['data']['timeCreation']));
    $templates['info']->assign( 'timeCreation', $timeCreation );
    if (isset($formBlockInfo['data']['userUpdate'])){
      $userModel = new UserModel();
      $userUpdate = $userModel->listItems( array( 'filters' => array('id' => $formBlockInfo['data']['userUpdate']) ) )->fetch();
      $userUpdateName = $userUpdate->getter('name');
      $timeLastUpdate = date('d/m/Y', time($formBlockInfo['data']['timeLastUpdate']));
      $templates['info']->assign( 'timeLastUpdate', $timeLastUpdate.' ('.$userUpdateName.')' );
    }
    if (isset($formBlockInfo['data']['averageVotes'])){
      $templates['info']->assign( 'averageVotes', $formBlockInfo['data']['averageVotes']);
    }
    $templates['info']->assign( 'res', $formBlockInfo );
    $templates['info']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['file'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['miniFormModal'] = new Template();
    $templates['miniFormModal']->assign( 'title', __( 'Resource' ) );
    $templates['miniFormModal']->setTpl( 'rTypeFileFormModalBlock.tpl', 'rtypeFile' );
    $templates['miniFormModal']->assign( 'res', $formBlockInfo );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'res', $formBlockInfo );


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }

  /**
   * Validaciones extra previas a usar los datos del recurso
   *
   * @param $form FormController Objeto form. del recurso
   */
  // parent::resFormRevalidate( $form );


  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {

    parent::resFormProcess( $form, $resource );

    if( !$form->existErrors() ) {
      //error_log( "rExtCtrl->rExtModel:" . print_r( $this->rExtCtrl->rExtModel, true ) );
      $fileCtrl = new RExtFileController( $this );
      $valueFile = $form->getFieldValue( $fileCtrl->addPrefix( 'file' ) );
      $valueImage = $form->getFieldValue( 'image' );

      if(!$valueImage && ( $valueFile && strpos($valueFile['validate']['type'], 'image/') !== false )){


        $rexData = $fileCtrl->getRExtData($resource->getter('id'));
        $resdata['id'] = $resource->getter('id');
        $resdata['image'] = $rexData['file']['id'];
        $resModel = new resourceModel($resdata);
        $resModel->save();
      }
      // image field == file field
    }

    /*
    if( !$form->existErrors() ) {
      $this->rExtCtrl = $this->newRExtContr();
      $this->rExtCtrl->resFormProcess( $form, $resource );
    }
    if( !$form->existErrors() ) {

      $valueFile = $form->getFieldValue( $this->rExtCtrl->addPrefix( 'file' ) );
      $valueImage = $form->getFieldValue( 'image' );

      if(!$valueImage && ( $valueFile && strpos($valueFile['validate']['type'], 'image/') !== false )){

        var_dump($this->defResCtrl->resObj->getter('id'));
        exit;
        $resdata['id'] = $this->rExtCtrl->rExtModel->getter('resource');
        $resdata['image'] = $this->rExtCtrl->rExtModel->getter('file');
        $resModel = new resourceModel($resdata);
        $resModel->save();
      }
      // image field == file field
    }
    */
  }

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource );

  /**
    Preparamos los datos para visualizar el Recurso
   **/
  public function getViewBlockInfo() {
    $viewBlockInfo = parent::getViewBlockInfo();

    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeFile' );

    $this->rExtCtrl = $this->newRExtContr();
    $rExtViewInfo = $this->rExtCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->rExtCtrl->rExtName ] = $rExtViewInfo;

    $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    if( $rExtViewInfo ) {
      if( $rExtViewInfo['template'] ) {
        foreach( $rExtViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToFragment( 'rextFileBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextFileBlock', false );
    }

    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
  }

} // class RTypeFileController
