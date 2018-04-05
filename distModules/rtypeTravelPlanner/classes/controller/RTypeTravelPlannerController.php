<?php

class RTypeTravelPlannerController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeTravelPlanner() );
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


    // Cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam( 'topics', 'type', 'reserved' );
    $form->setFieldParam( 'starred', 'type', 'reserved' );
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
    $form->removeField( 'externalUrl' );
    $form->removeField( $form->multilangFieldNames( 'urlAlias' ) );
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {

    // Cargamos la informacion del form, los datos y lanzamos los getFormBlockInfo de las extensiones
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
      $form->multilangFieldNames( 'content' )
    );
    $templates['formBase']->assign( 'formFieldsNames', $formFieldsNames );



    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );

    // COL8
    $templates['adminFull']->addToFragment( 'col8', $templates['formBase'] );
    // COL4
    $templates['adminFull']->addToFragment( 'col4', $templates['publication'] );
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
  public function getViewBlockInfo( $resId = false ) {

    // Preparamos los datos para visualizar el Recurso con sus extensiones
    $viewBlockInfo = parent::getViewBlockInfo( $resId );

    // $template = new Template();
    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeTravelPlanner' );
    //$template->addClientScript( 'js/model/ResourceModel.js' , 'geozzy');
    //$template->addClientScript( 'js/collection/ResourceCollection.js' , 'geozzy');
    //$template->addClientScript( 'js/model/ResourcetypeModel.js' , 'geozzy');
    //$template->addClientScript( 'js/collection/ResourcetypeCollection.js' , 'geozzy');
    $viewBlockInfo['data']['title'] = __("Travel Planner");

    $favsResourcesInfo = ($viewBlockInfo['data']['id']) ? $this->getResourcesInfo( $viewBlockInfo['data']['id'] ) : false;
    $template->assign( 'favsResourcesInfo', $favsResourcesInfo );

    $viewBlockInfo['template']['full'] = $template;
    $viewBlockInfo['footer'] = false;

    return $viewBlockInfo;
  }


  public function getResourcesInfo( $resIds ) {
    geozzy::load('model/ResourceModel.php');
    $resInfo = array();

    $urlAliasModel = new UrlAliasModel();
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'resourceIn' => $resIds ), 'cache' => $this->cacheQuery ) );
    $urls = array();
    while( $urlAlias = $urlAliasList->fetch() ) {
      $urls[$urlAlias->getter('resource')] = $urlAlias->getter('urlFrom');
    }

    $resourceModel = new ResourceModel();
    $resourceList = $resourceModel->listItems( array( 'filters' => array( 'inId' => $resIds, 'published' => 1 ), 'cache' => $this->cacheQuery ) );
    while( $resObj = $resourceList->fetch() ) {
      $resId = $resObj->getter('id');
      $resInfo[ $resId ] = array(
        'id' => $resObj->getter('id'),
        'title' => $resObj->getter('title'),
        'shortDescription' => $resObj->getter('shortDescription'),
        'image' => $resObj->getter('image'),
        'url' => $urls[ $resId ],
        'rTypeId' => $resObj->getter('rTypeId')
      );
    }

    return $resInfo;
  }



} // class RTypeBlogController
