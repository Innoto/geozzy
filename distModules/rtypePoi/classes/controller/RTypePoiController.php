<?php
rextPoi::autoIncludes();

class RTypePoiController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypePoi() );
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
    $form->setFieldParam('externalUrl', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
    $form->removeValidationRules('externalUrl');

    // cambiamos el id de la imagen para evitar la colisión con la modal
    $form->setFieldParam('image', 'id', 'imgResourcePoi');

    if(!empty($form->getFieldValue('content_'.$this->defResCtrl->defLang))){
      $rextPoiControl = new RExtPoiController($this);
      $pitchYaw = explode("/", $form->getFieldValue('content_'.$this->defResCtrl->defLang));
      $form->setFieldValue($rextPoiControl->addPrefix('rextPoiPitch'), $pitchYaw[0]);
      $form->setFieldValue($rextPoiControl->addPrefix('rextPoiYaw'), $pitchYaw[1]);
    }
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    //error_log( "RTypePoiController: getFormBlockInfo()" );

    $formBlockInfo = parent::getFormBlockInfo( $form );

    $templates = $formBlockInfo['template'];

    // TEMPLATE panel principa del form. Contiene los elementos globales del form.
    $templates['formBase'] = new Template();
    $templates['formBase']->setTpl( 'rTypeFormBase.tpl', 'geozzy' );
    $templates['formBase']->assign( 'title', __('Main Resource information') );
    $templates['formBase']->assign( 'res', $formBlockInfo );

    $formFieldsNames = array_merge(
      $form->multilangFieldNames( 'title' ),
      $form->multilangFieldNames( 'shortDescription' )
    );
    $formFieldsNames[] = 'rTypeIdName';
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );


    // TEMPLATE panel Poi
    $templates['poi'] = new Template();
    $templates['poi']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['poi']->assign( 'title', __( 'poi' ) );
    $templates['poi']->assign( 'res', $formBlockInfo );
    $templates['poi']->setFragment( 'blockContent', $formBlockInfo['ext']['rextPoi']['template']['adminExt'] );



    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    //$templates['adminFull']->addToFragment( 'col8', $templates['location'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['poi'] );
    $templates['adminFull']->addToFragment( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['image'] );
    $templates['adminFull']->addToFragment( 'col4', $templates['info'] );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'res', $formBlockInfo );


    // TEMPLATE para form mini para modal
    $templates['miniFormModal'] = new Template();
    $templates['miniFormModal']->assign( 'title', __( 'Resource' ) );
    $templates['miniFormModal']->setTpl( 'rTypePoiFormModalBlock.tpl', 'rtypePoi' );
    $templates['miniFormModal']->addClientScript('js/rextPoi.js', 'rextPoi');
    $templates['miniFormModal']->assign('rExt', $formBlockInfo['ext']);
    $templates['miniFormModal']->assign( 'res', $formBlockInfo );


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
    * Creación-Edición-Borrado de los elementos de la extension
    *
    * @param $form FormController
    * @param $resource ResourceModel
    */
    public function resFormProcess( FormController $form, ResourceModel $resource ) {
      parent::resFormProcess( $form, $resource );

      if( !$form->existErrors() ) {
        $rextPoiControl = new RExtPoiController($this);
        $pitch = $form->getFieldValue($rextPoiControl->addPrefix('rextPoiPitch'));
        $yaw = $form->getFieldValue($rextPoiControl->addPrefix('rextPoiYaw'));
        if( $pitch !== "" && $yaw !== "" ){
          $resource->setter('content_'.$this->defResCtrl->defLang, $pitch."/".$yaw);
        }
      }
    }

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
  public function getViewBlockInfo( $resId = false ) {

/*
    // Preparamos los datos para visualizar el Recurso con sus extensiones
    $viewBlockInfo = parent::getViewBlockInfo( $resId );

    //$template = new Template();
    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypePoi' );

    $taxtermModel = new TaxonomytermModel();

    // Recuperamos todos los términos de la taxonomía tipo
    $poiTypeList = $this->defResCtrl->getOptionsTax( 'rextPoiType' );
    foreach( $poiTypeList as $poiTypeId => $poiTypeName ) {
      $poiType = $taxtermModel->listItems(array('filters'=> array('id' => $poiTypeId), 'cache' => $this->cacheQuery))->fetch();
    }
    $template->assign('poiType', $poiType);


    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;

    */
    // Non queremos q se visualicen os POIs por urlAlias
    $bridge = new AppResourceBridgeView();
    $bridge->page404();


  }

} // class RTypePoiController
