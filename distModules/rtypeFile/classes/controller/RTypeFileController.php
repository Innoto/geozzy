<?php

class RTypeFileController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    // error_log( __METHOD__ );

    parent::__construct( $defResCtrl, new rtypeFile() );
  }

  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( __METHOD__ );

    parent::manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

  } // function manipulateForm()

  public function getFormBlockInfo( FormController $form ) {
    // error_log( __METHOD__ );

    $formBlockInfo = parent::getFormBlockInfo( $form );

    $this->fileCtrl = new RExtFileController( $this );

    $templates = $formBlockInfo['template'];

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


    // TEMPLATE panel image
    $templates['file'] = new Template();
    $templates['file']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['file']->assign( 'title', __( 'Multimedia file' ) );
    $templates['file']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $this->fileCtrl->addPrefix( 'file' );
    $templates['file']->assign( 'formFieldsNames', $formFieldsNames );



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
    $templates['multiFormModal'] = new Template();
    $templates['multiFormModal']->assign( 'title', __( 'Resource' ) );
    $templates['multiFormModal']->setTpl( 'rTypeMultiFileFormModalBlock.tpl', 'rtypeFile' );
    $templates['multiFormModal']->assign( 'res', $formBlockInfo );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['adminMultiFormModal'] = new Template();
    $templates['adminMultiFormModal']->assign( 'title', __( 'Resource' ) );
    $templates['adminMultiFormModal']->setTpl( 'rTypeMultiFileAdminFormModalBlock.tpl', 'rtypeFile' );
    $templates['adminMultiFormModal']->assign( 'res', $formBlockInfo );

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
  public function getViewBlockInfo( $resId = false ) {
    $viewBlockInfo = parent::getViewBlockInfo( $resId );

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
