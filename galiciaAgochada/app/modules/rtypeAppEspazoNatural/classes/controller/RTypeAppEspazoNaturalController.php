<?php
rextAppEspazoNatural::autoIncludes();
rextContact::autoIncludes();
rextMapDirections::autoIncludes();
rextAppZona::autoIncludes();
rextSocialNetwork::autoIncludes();

class RTypeAppEspazoNaturalController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ) {
    // error_log( 'RTypeAppEspazoNaturalController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppEspazoNatural() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppEspazoNaturalController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');
    // eliminamos campos de recurso
    //$form->removeField('externalUrl');

    // Extensión Espazo Natural
    $rTypeExtNames[] = 'rextAppEspazoNatural';
    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $rExtFieldNames = $this->rExtCtrl->manipulateForm( $form );

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión Contacto
    $rTypeExtNames[] = 'rextContact';
    $this->contactCtrl = new RExtContactController( $this );
    $rExtFieldNames = $this->contactCtrl->manipulateForm( $form );

    // Extensión redes sociales
    $rTypeExtNames[] = 'rextSocialNetwork';
    $this->socialCtrl = new RExtSocialNetworkController( $this );
    $rExtFieldNames = $this->socialCtrl->manipulateForm( $form );
    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión Zona
    $rTypeExtNames[] = 'rextAppZona';
    $this->zonaCtrl = new RExtAppZonaController( $this );
    $rExtFieldNames = $this->zonaCtrl->manipulateForm( $form );

    // eliminamos los campos de contacto que no necesitamos
    /*$form->removeField('rExtContact_address');
    $form->removeField('rExtContact_city');
    $form->removeField('rExtContact_cp');
    $form->removeField('rExtContact_province');
    $form->removeField('rExtContact_phone');
    $form->removeField('rExtContact_email');
    $form->removeField('rExtContact_timetable');*/

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );


    return( $rTypeFieldNames );
  } // function manipulateForm()


  public function getFormBlockInfo( FormController $form ) {
    // error_log( "RTypeAppEspazoNaturalController: getFormBlockInfo()" );

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

    $this->espazoCtrl = new RExtAppEspazoNaturalController( $this );
    $espazoViewInfo = $this->espazoCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->espazoCtrl->rExtName ] = $espazoViewInfo;

    $this->contactCtrl = new RExtContactController( $this );
    $contactViewInfo = $this->contactCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->contactCtrl->rExtName ] = $contactViewInfo;

    $this->socialCtrl = new RExtSocialNetworkController( $this );
    $socialViewInfo = $this->socialCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->socialCtrl->rExtName ] = $socialViewInfo;

    $this->zonaCtrl = new RExtAppZonaController( $this );
    $zonaViewInfo = $this->zonaCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->zonaCtrl->rExtName ] = $zonaViewInfo;

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
    $formFieldsNames[] = 'rTypeIdName';
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

    // TEMPLATE panel localización
    $templates['contact'] = new Template();
    $templates['contact']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['contact']->assign( 'title', __( 'Contact' ) );
    $templates['contact']->setBlock( 'blockContent', $contactViewInfo['template']['basic'] );

    // TEMPLATE panel social network
    $templates['social'] = new Template();
    $templates['social']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['social']->assign( 'title', __( 'Social Networks' ) );
    $templates['social']->setBlock( 'blockContent', $socialViewInfo['template']['basic'] );

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

    // TEMPLATE panel categorization
    $templates['categorization'] = new Template();
    $templates['categorization']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['categorization']->assign( 'title', __( 'Categorization' ) );
    $templates['categorization']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $this->espazoCtrl->prefixArray( array('rextAppEspazoNaturalType'));
    $formFieldsNames[] = $this->zonaCtrl->addPrefix('rextAppZonaType');
    $templates['categorization']->assign( 'formFieldsNames', $formFieldsNames );

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
    $timeCreation = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeCreation']));
    $templates['info']->assign( 'timeCreation', $timeCreation );
    if (isset($formBlockInfo['data']['userUpdate'])){
      $userModel = new UserModel();
      $userUpdate = $userModel->listItems( array( 'filters' => array('id' => $formBlockInfo['data']['userUpdate']) ) )->fetch();
      $userUpdateName = $userUpdate->getter('name');
      $timeLastUpdate = gmdate('d/m/Y', strtotime($formBlockInfo['data']['timeLastUpdate']));
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
    $templates['adminFull']->addToBlock( 'col8', $templates['contact'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['social'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['location'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['multimedia'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['collections'] );
    $templates['adminFull']->addToBlock( 'col8', $templates['seo'] );
    // COL4
    $templates['adminFull']->addToBlock( 'col4', $templates['publication'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['image'] );
    $templates['adminFull']->addToBlock( 'col4', $templates['categorization'] );
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
    // error_log( "RTypeAppEspazoNaturalController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
      $this->rExtCtrl->resFormRevalidate( $form );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormRevalidate( $form );

      $this->socialCtrl = new RExtSocialNetworkController( $this );
      $this->socialCtrl->resFormRevalidate( $form );

      $this->zonaCtrl = new RExtAppZonaController( $this );
      $this->zonaCtrl->resFormRevalidate( $form );
    }
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppEspazoNaturalController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
      $this->rExtCtrl->resFormProcess( $form, $resource );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormProcess( $form, $resource );

      $this->socialCtrl = new RExtSocialNetworkController( $this );
      $this->socialCtrl->resFormProcess( $form, $resource );

      $this->zonaCtrl = new RExtAppZonaController( $this );
      $this->zonaCtrl->resFormProcess( $form, $resource );
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   **/
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppEspazoNaturalController: resFormSuccess()" );

    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $this->rExtCtrl->resFormSuccess( $form, $resource );

    $this->contactCtrl = new RExtContactController( $this );
    $this->contactCtrl->resFormSuccess( $form, $resource );

    $this->socialCtrl = new RExtSocialNetworkController( $this );
    $this->socialCtrl->resFormSuccess( $form, $resource );

    $this->zonaCtrl = new RExtAppZonaController( $this );
    $this->zonaCtrl->resFormSuccess( $form, $resource );
  }


  /**
    Preparamos los datos para visualizar el Recurso
   **/
  public function getViewBlockInfo() {
    // error_log( "RTypeEspazoNaturalController: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => $this->defResCtrl->getResourceData( false, true ),
      'ext' => array()
    );

    $template = new Template();
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppEspazoNatural' );

    $this->rExtCtrl = new RExtAppEspazoNaturalController( $this );
    $rExtViewInfo = $this->rExtCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->rExtCtrl->rExtName ] = $rExtViewInfo;

    $this->contactCtrl = new RExtContactController( $this );
    $contactViewInfo = $this->contactCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->contactCtrl->rExtName ] = $contactViewInfo;

    $this->mapDirCtrl = new RExtMapDirectionsController( $this );
    $mapDirViewInfo = $this->mapDirCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->mapDirCtrl->rExtName ] = $mapDirViewInfo;
    // error_log( 'viewBlockInfo ext '. $this->mapDirCtrl->rExtName .' = '. print_r( $mapDirViewInfo, true ) );

    $this->socialCtrl = new RExtSocialNetworkController( $this );
    $socialViewInfo = $this->socialCtrl->getViewBlockInfo();
    $viewBlockInfo['ext'][ $this->socialCtrl->rExtName ] = $socialViewInfo;

    $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    $resData = $this->defResCtrl->getResourceData( false, true );

    if( $rExtViewInfo ) {
      if( $rExtViewInfo['template'] ) {
        foreach( $rExtViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextAppEspazoNaturalBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextAppEspazoNatural', false );
    }

    if( $contactViewInfo ) {
      if( $contactViewInfo['template'] ) {
        foreach( $contactViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextContactBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextContactBlock', false );
    }

    if( $mapDirViewInfo ) {
      if( $mapDirViewInfo['template'] ) {
        foreach( $mapDirViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextMapDirectionsBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextMapDirectionsBlock', false );
    }

    if( $socialViewInfo ) {
      if( $socialViewInfo['template'] ) {
        foreach( $socialViewInfo['template'] as $nameBlock => $templateBlock ) {
          $template->addToBlock( 'rextSocialNetworkBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextSocialNetworkBlock', false );
    }


    /* Cargamos los bloques de colecciones */
    $collectionArrayInfo = $this->defResCtrl->getCollectionBlockInfo( $resData[ 'id' ] );

    $multimediaArray = false;
    $collectionArray = false;
    if ($collectionArrayInfo){
      foreach ($collectionArrayInfo as $key => $collectionInfo){
        if ($collectionInfo['col']['collectionType'] == 'multimedia'){ // colecciones multimedia
            $multimediaArray[$key] = $collectionInfo;
        }
        else{ // resto de colecciones
            $collectionArray[$key] = $collectionInfo;
        }
      }

      if ($multimediaArray){
        $arrayMultimediaBlock = $this->defResCtrl->goOverCollections( $multimediaArray, $collectionType = 'multimedia' );
        if ($arrayMultimediaBlock){
          foreach ($arrayMultimediaBlock as $multimediaBlock){
            $multimediaBlock->assign( 'max', 6 );
            $multimediaBlock->setTpl('appEspazoNaturalMultimediaViewBlock.tpl', 'rtypeAppEspazoNatural');
            $template->addToBlock( 'multimediaGalleries', $multimediaBlock );
          }
        }
      }

      if ($collectionArray){
        $arrayCollectionBlock = $this->defResCtrl->goOverCollections( $collectionArray, $collectionType = 'base' );
        if ($arrayCollectionBlock){
          foreach ($arrayCollectionBlock as $collectionBlock){
            $collectionBlock->setTpl('appEspazoNaturalCollectionViewBlock.tpl', 'rtypeAppEspazoNatural');
            $template->addToBlock( 'collections', $collectionBlock );
          }
        }
      }
    }



    $viewBlockInfo['template'] = array( 'full' => $template );

    return $viewBlockInfo;
  }

} // class RTypeAppEspazoNaturalController
