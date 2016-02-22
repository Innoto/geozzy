<?php
rextEatAndDrink::autoIncludes();
rextContact::autoIncludes();
rextMapDirections::autoIncludes();
rextAppZona::autoIncludes();
rextSocialNetwork::autoIncludes();

class RTypeAppRestaurantController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    // error_log( 'RTypeAppRestaurantController::__construct' );

    parent::__construct( $defResCtrl, new rtypeAppRestaurant() );
  }



  /**
    Defino el formulario
   **/
  public function manipulateForm( FormController $form ) {
    // error_log( "RTypeAppRestaurantController: manipulateForm()" );

    $rTypeExtNames = array();
    $rTypeFieldNames = array();

    $rTypeExtNames[] = 'rextEatAndDrink';
    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $rExtFieldNames = $this->eatCtrl->manipulateForm( $form );

    // Elimino los campos de la extensión que no quiero usar
    $form->removeField('rextEatAndDrink_capacity');

    // cambiamos el tipo de topics y starred para que no se muestren
    $form->setFieldParam('topics', 'type', 'reserved');
    $form->setFieldParam('starred', 'type', 'reserved');
    $form->removeValidationRules('topics');
    $form->removeValidationRules('starred');

    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión contacto
    $rTypeExtNames[] = 'rextContact';
    $this->contactCtrl = new RExtContactController( $this );
    $rExtFieldNames = $this->contactCtrl->manipulateForm( $form );
    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    $form->setFieldParam( 'externalUrl', 'label', __( 'Home URL' ) );

    // Extensión social network
    $rTypeExtNames[] = 'rextSocialNetwork';
    $this->socialCtrl = new RExtSocialNetworkController( $this );
    $rExtFieldNames = $this->socialCtrl->manipulateForm( $form );
    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Extensión Zona
    $rTypeExtNames[] = 'rextAppZona';
    $this->zonaCtrl = new RExtAppZonaController( $this );
    $rExtFieldNames = $this->zonaCtrl->manipulateForm( $form );
    $rTypeFieldNames = array_merge( $rTypeFieldNames, $rExtFieldNames );

    // Valadaciones extra
    // $form->setValidationRule( 'restaurantName_'.$form->langDefault, 'required' );

    return( $rTypeFieldNames );
  } // function manipulateForm()


  public function getFormBlockInfo( FormController $form ) {
    // error_log( "RTypeAppRestaurantController: getFormBlockInfo()" );

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

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $EatandDrinkViewInfo = $this->eatCtrl->getFormBlockInfo( $form );
    $viewBlockInfo['ext'][ $this->eatCtrl->rExtName ] = $EatandDrinkViewInfo;

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
    $formFieldsNames[] = 'rTypeIdName';
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


    // TEMPLATE panel reservas

    $templates['reservation'] = new Template();
    $templates['reservation']->setTpl( 'rTypeFormDefPanel.tpl', 'geozzy' );
    $templates['reservation']->assign( 'title', __( 'Reservation' ) );
    $templates['reservation']->assign( 'res', $formBlockInfo );
    $formFieldsNames = $this->eatCtrl->prefixArray( array( 'reservationURL', 'reservationPhone' ) );
    $templates['reservation']->assign( 'formFieldsNames', $formFieldsNames );

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
    $formFieldsNames = $this->eatCtrl->prefixArray( array('eatanddrinkType', 'eatanddrinkSpecialities', 'averagePrice') );
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
    $templates['adminFull']->addToBlock( 'col8', $templates['reservation'] );
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
    // error_log( "RTypeAppRestaurantController: resFormRevalidate()" );

    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormRevalidate( $form );

      $this->contactCtrl = new RExtContactController( $this );
      $this->contactCtrl->resFormRevalidate( $form );

      $this->socialCtrl = new RExtSocialNetworkController( $this );
      $this->socialCtrl->resFormRevalidate( $form );
    }

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   **/
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RTypeAppRestaurantController: resFormProcess()" );
    if( !$form->existErrors() ) {
      $this->eatCtrl = new RExtEatAndDrinkController( $this );
      $this->eatCtrl->resFormProcess( $form, $resource );

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
    // error_log( "RTypeAppRestaurantController: resFormSuccess()" );

    $this->eatCtrl = new RExtEatAndDrinkController( $this );
    $this->eatCtrl->resFormSuccess( $form, $resource );

    $this->contactCtrl = new RExtContactController( $this );
    $this->contactCtrl->resFormSuccess( $form, $resource );

    $this->socialCtrl = new RExtSocialNetworkController( $this );
    $this->socialCtrl->resFormSuccess( $form, $resource );
  }


  /**
    Preparamos los datos para visualizar el Recurso
   **/
  public function getViewBlockInfo() {
    // error_log( "RTypeAppRestaurantController: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => $this->defResCtrl->getResourceData( false, true ),
      'ext' => array()
    );

    $template = new Template();
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeAppRestaurant' );

    $this->rExtCtrl = new RExtEatAndDrinkController( $this );
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
          $template->addToBlock( 'rextEatAndDrinkBlock', $templateBlock );
        }
      }
    }
    else {
      $template->assign( 'rextEatAndDrinkBlock', 'false' );
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
            $template->addToBlock( 'multimediaGalleries', $multimediaBlock );
          }
        }
      }

      if ($collectionArray){
        $arrayCollectionBlock = $this->defResCtrl->goOverCollections( $collectionArray, $collectionType = 'base' );
        if ($arrayCollectionBlock){
          foreach ($arrayCollectionBlock as $collectionBlock){
            $template->addToBlock( 'collections', $collectionBlock );
          }
        }
      }
    }


    $viewBlockInfo['template'] = array( 'full' => $template );

    return $viewBlockInfo;
  }

} // class RTypeAppRestaurantController
