<?php

geozzy::load('controller/RTypeController.php');

class RTypeStoryStepController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeStoryStep() );
  }


  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form ) {

    // Lanzamos los manipulateForm de las extensiones
    parent::manipulateForm( $form );

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    $form->setFieldParam( 'externalUrl', 'label', __( 'Home URL' ) );
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    //error_log( "RTypeEventController: getFormBlockInfo()" );

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
      $form->multilangFieldNames( 'mediumDescription' ),
      $form->multilangFieldNames( 'content' )
    );
    $formFieldsNames[] = 'externalUrl';
    $formFieldsNames[] = 'rTypeIdName';
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel poicollection
    $templates['poiCollection'] = new Template();
    $templates['poiCollection']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['poiCollection']->assign( 'title', __( 'POI collection' ) );
    $templates['poiCollection']->setFragment( 'blockContent', $formBlockInfo['ext']['rextPoiCollection']['template']['full'] );


    // TEMPLATE panel event
    $templates['event'] = new Template();
    $templates['event']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['event']->assign( 'title', __( 'Fechas' ) );
    // eliminamos los campos que no nos interesan
    $formValuesArray = $formBlockInfo['ext']['rextEvent']['template']['full']->getTemplateVars('rExt');
    unset($formValuesArray['dataForm']['formFieldsArray']['rextEvent_rextEventType']);
    unset($formValuesArray['dataForm']['formFieldsArray']['rextEvent_relatedResource']);
    $formBlockInfo['ext']['rextEvent']['template']['full']->assign('rExt', $formValuesArray);
    $form->removeField('rextEvent_rextEventType');
    $templates['event']->setFragment( 'blockContent', $formBlockInfo['ext']['rextEvent']['template']['full'] );

    // TEMPLATE panel storystep
    $templates['storystep'] = new Template();
    $templates['storystep']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['storystep']->assign( 'title', __( 'Story step' ) );
    $templates['storystep']->setFragment( 'blockContent', $formBlockInfo['ext']['rextStoryStep']['template']['basic'] );



    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['poiCollection'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['social'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['location'] );

    // COL8
    if( isset($templates['comment']) ) {
      $templates['adminFull']->addToFragment( 'col8', $templates['comment'] );
    }

    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['multimedia'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['collections'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['event'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['storystep'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );

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
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
   //parent::getViewBlockInfo( $resId );

} // class RTypeEventController
