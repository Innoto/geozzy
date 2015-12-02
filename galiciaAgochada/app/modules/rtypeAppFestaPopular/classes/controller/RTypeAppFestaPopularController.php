<?php



class RTypeAppFestaPopularController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    error_log( 'RTypeAppFestaPopularController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppFestaPopular() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppFestaPopularController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    return( $rTypeFieldNames );
  } // function manipulateForm()


  public function getFormBlockInfo( FormController $form ) {
    error_log( "RTypeAppFestaPopularlController: getFormBlockInfo()" );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false,
      'ext' => array()
    );

    $formBlockInfo['dataForm'] = array(
      'formOpen' => $form->getHtmpOpen(),
      'formFieldsArray' => $form->getHtmlFieldsArray(),
      'formFieldsHiddenArray' => array(),
      'formFields' => $form->getHtmlFieldsAndGroups(),
      'formClose' => $form->getHtmlClose(),
      'formValidations' => $form->getScriptCode()
    );

    if( $resId = $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->defResCtrl->getResourceData( $resId );
    }

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
    $formFieldsNames[] = 'topics';
    $formFieldsNames[] = 'starred';
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

    // TEMPLATE panel contacto
    $templates['location'] = new Template();
    $templates['location']->setTpl( 'rTypeFormLocationPanel.tpl', 'geozzy' );
    $templates['location']->assign( 'title', __( 'Location' ) );
    $templates['location']->assign( 'res', $formBlockInfo );
    $templates['location']->assign('directions', $form->multilangFieldNames( 'rExtContact_directions' ));

    // TEMPLATE panel multimedia
    $templates['multimedia'] = new Template();
    $templates['multimedia']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['multimedia']->assign( 'title', __( 'Multimedia galleries' ) );
    $templates['multimedia']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'multimediaGalleries', 'addMultimediaGalleries' );
    $templates['multimedia']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE panel collections
    $templates['collections'] = new Template();
    $templates['collections']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['collections']->assign( 'title', __( 'Collections of related resources' ) );
    $templates['collections']->assign( 'res', $formBlockInfo );
    $formFieldsNames = array( 'collections', 'addCollections' );
    $templates['collections']->assign( 'formFieldsNames', $formFieldsNames );

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
    /* Temáticas */
    if (isset($formBlockInfo['data']['topicsName'])){
      $templates['info']->assign( 'resourceTopicList', $formBlockInfo['data']['topicsName']);
    }
    $templates['info']->assign( 'res', $formBlockInfo );
    $templates['info']->assign( 'formFieldsNames', $formFieldsNames );

    // TEMPLATE con todos los paneles
    $templates['adminFull'] = new Template();
    $templates['adminFull']->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $templates['adminFull']->assign( 'headTitle', __( 'Edit Resource' ) );
    // COL8
    $templates['adminFull']->addToBlock( 'col8', $templates['formBase'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['location'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['multimedia'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['collections'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToBlock( 'col4', $templates['publication'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['image'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['info'] );

    // TEMPLATE en bruto con todos los elementos del form
    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rTypeFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'res', $formBlockInfo );


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
    Validaciones extra previas a usar los datos del recurso base
   **/
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RTypeAppFestaPopularController: resFormRevalidate()" );

  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppFestaPopularController: resFormProcess()" );

  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppFestaPopularController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   **/
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RTypeAppFestaPopularController: getViewBlock()" );
    $template = false;

    $template = $resBlock;

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   **/
  public function getViewBlockInfo() {
    error_log( "RTypeAppFestaPopularController: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => $this->defResCtrl->getResourceData( false, true ),
      'ext' => array()
    );

    // PENDIENTE

    return $viewBlockInfo;
  }

} // class RTypeAppFestaPopularController
