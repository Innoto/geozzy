<?php

class RTypeUrlController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeUrlController::__construct' );

    parent::__construct( $defResCtrl, new rtypeUrl() );
  }

  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeUrlController: manipulateForm()" );
    parent::manipulateForm( $form );
    // Valadaciones extra

    // Eliminamos campos del formulario de recurso que no deseamos
    $removeFields = array_merge(
      $form->multilangFieldNames( 'content' ),
      $form->multilangFieldNames( 'datoExtra1' ),
      $form->multilangFieldNames( 'datoExtra2' ),
      array( 'collections', 'addCollections', 'multimediaGalleries', 'addMultimediaGalleries',
        'topics', 'starred', 'locLat', 'locLon', 'defaultZoom', 'externalUrl')
    );
    $form->removeField( $removeFields );
    $form->saveToSession();

  } // function manipulateForm()

  public function getFormBlockInfo( FormController $form ) {
    // error_log( "RTypeHotelController: getFormBlockInfo()" );
    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];
    $this->urlCtrl = new RExtUrlController( $this );

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

    $formFieldsNames[] = $this->urlCtrl->addPrefix( 'author' );
    $formFieldsNames[] = $this->urlCtrl->addPrefix( 'urlContentType' );
    $formFieldsNames[] = $this->urlCtrl->addPrefix( 'url' );
    $formFieldsNames[] = $this->urlCtrl->addPrefix( 'embed' );

    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['miniFormModal'] = new Template();
    $templates['miniFormModal']->assign( 'title', __( 'Resource' ) );
    $templates['miniFormModal']->setTpl( 'rTypeUrlFormModalBlock.tpl', 'rtypeUrl' );
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
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController Objeto form. del recurso
   * @param $resource ResourceModel Objeto form. del recurso
   */
  // parent::resFormProcess( $form, $resource );

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

    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeUrl' );

    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
  }

} // class RTypeUrlController
