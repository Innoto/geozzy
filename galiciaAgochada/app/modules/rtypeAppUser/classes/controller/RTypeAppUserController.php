<?php

class RTypeAppUserController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeAppUserController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppUser() );
  }

  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppUserController: manipulateForm()" );

    // Lanzamos los manipulateForm de las extensiones
    parent::manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
    $urlAliasLang = $form->multilangFieldNames('urlAlias');
    foreach( $urlAliasLang as $key => $field ) {
      $form->removeField( $field);
    }
    $urlAliasLang = $form->multilangFieldNames('title');
    foreach( $urlAliasLang as $key => $field ) {
      $form->removeField( $field);
    }
    $form->removeField('rExtContact_email');
    $form->removeField('externalUrl');
    $form->removeValidationRules('published');

  } // function manipulateForm()

  public function getFormBlockInfo( FormController $form ) {

    $formBlockInfo = parent::getFormBlockInfo( $form );
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
    $templates['formProfile'] = new Template();
    $templates['formProfile']->assign( 'title', __( 'Profile Data' ) );
    $templates['formProfile']->setTpl( 'rTypeAppFormProfileBlock.tpl', 'rtypeAppUser' );
    $templates['formProfile']->assign( 'res', $formBlockInfo );

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

    // Preparamos los datos para visualizar el Recurso con sus extensiones
    $viewBlockInfo = parent::getViewBlockInfo();

    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppUser' );

    $viewBlockInfo['template'] = array( 'full' => $template );

    return $viewBlockInfo;
  }

} // class RTypeAppUserController
