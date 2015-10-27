<?php
geozzy::load( 'controller/RTypeController.php' );
geozzy::load( 'controller/RExtController.php' );


class ResourceController {

  public $rTypeCtrl = null;
  public $resObj = null;
  public $resData = null;
  private $taxonomyAll = null;

  public function __construct() {
    // error_log( 'ResourceController::__construct' );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();
  }

  /**
   *  Cargando controlador del RType
   */
  public function getRTypeCtrl( $rTypeId ) {
    error_log( "GeozzyResourceView: getRTypeCtrl( $rTypeId )" );

    if( !$this->rTypeCtrl ) {
      $rTypeModel = new ResourcetypeModel();
      $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'id' => $rTypeId ) ) );
      if( $rTypeInfo = $rTypeList->fetch() ) {
        $rTypeIdName = $rTypeInfo->getter( 'idName' );
        if( class_exists( $rTypeIdName ) ) {
          error_log( "GeozzyResourceView: getRTypeCtrl = $rTypeIdName" );
          $rTypeIdName::autoIncludes();
          $rTypeCtrlClassName = $rTypeIdName.'Controller';
          $this->rTypeCtrl = new $rTypeCtrlClassName( $this );
        }
      }
    }

    return $this->rTypeCtrl;
  }


  /**
   * Load resource object
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function loadResourceObject( $resId ) {
    if( $this->resObj === null ) {
      $resModel = new ResourceModel();
      $resList = $resModel->listItems( array( 'affectsDependences' =>
        array( 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ExtraDataModel' ),
        // array( 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ResourceTaxonomytermModel', 'ExtraDataModel' ),
        'filters' => array( 'id' => $resId, 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1 ) ) );
      $this->resObj = ( $resList ) ? $resList->fetch() : null;
    }

    return( $this->resObj != null &&  $this->resObj != false );
  }


  /**
   * Load basic data values
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getResourceData( $resId, $translate = false ) {
    // error_log( "ResourceController: getResourceData()" );

    if( !$this->resData && $this->loadResourceObject( $resId ) ) {
      $resourceData = false;

      $langDefault = LANG_DEFAULT;
      global $LANG_AVAILABLE;
      if( isset( $LANG_AVAILABLE ) && is_array( $LANG_AVAILABLE ) ) {
        $langAvailable = array_keys( $LANG_AVAILABLE );
      }

      if( $translate ) {
        $resourceData = array();
        foreach( $this->resObj->getCols() as $key => $value ) {
          $resourceData[ $key ] = $this->resObj->getter( $key );
        }
      }
      else {
        $resourceData = $this->resObj->getAllData( 'onlydata' );
      }

      // Cargo los datos de urlAlias dentro de los del recurso
      $urlAliasDep = $this->resObj->getterDependence( 'id', 'UrlAliasModel' );
      if( $urlAliasDep !== false ) {
        foreach( $urlAliasDep as $urlAlias ) {
          $urlLang = $urlAlias->getter('lang');
          if( $urlLang ) {
            $resourceData[ 'urlAlias_'.$urlLang ] = $urlAlias->getter('urlFrom');
          }
        }
      }

      // Cargo los datos de image dentro de los del recurso
      $fileDep = $this->resObj->getterDependence( 'image' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $resourceData[ 'image' ] = $fileModel->getAllData( 'onlydata' );
        }
      }

      // Cargo los datos de temáticas con las que está asociado el recurso
      $topicsDep = $this->resObj->getterDependence( 'id', 'ResourceTopicModel');
      if( $topicsDep !== false ) {
        foreach( $topicsDep as $topicRel ) {
          $topicsArray[$topicRel->getter('id')] = $topicRel->getter('topic');
        }
        $resourceData[ 'topics' ] = $topicsArray;
      }

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $termsGroupedIdName = $this->getTermsInfoByGroupIdName( $resId );
      if( $termsGroupedIdName !== false ) {
        foreach( $termsGroupedIdName as $idNameTaxgroup => $taxTermArray ) {
          $resourceData[ $idNameTaxgroup ] = $taxTermArray;
          // error_log( '$resourceData[ '.$idNameTaxgroup.' ] = : '.print_r( $taxTermArray, true ) );
        }
      }

      // Cargo los datos del campo batiburrillo
      $extraDataDep = $this->resObj->getterDependence( 'id', 'ExtraDataModel');
      if( $extraDataDep !== false ) {
        foreach( $extraDataDep as $extraData ) {
          foreach( $langAvailable as $lang ) {
            $resourceData[ $extraData->getter('name').'_'.$lang ] = $extraData->getter( 'value_'.$lang );
          }
        }
      }

      // Cargo el campo rTypeIdName
      $rtypeControl = new ResourcetypeModel();
      $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $resourceData['rTypeId'] ) ) )->fetch();
      $resourceData['rTypeIdName'] = $rTypeItem->getter('idName');

      if( $resourceData ) {
        $this->resData = $resourceData;
      }
    }

    return $this->resData;
  }


  /**
   * Defino el formulario del Recurso Base
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Form
   */
  public function getBaseFormObj( $formName, $urlAction, $valuesArray = false ) {
    error_log( "ResourceController: getBaseFormObj()" );
    // error_log( "valuesArray: ".print_r( $valuesArray, true ) );

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', __( 'Thank you' ) );

    if( !isset($valuesArray['topicReturn']) ) {
      $form->setSuccess( 'redirect', SITE_URL . 'admin#resource/list' );
    }
    else {
      $form->setSuccess( 'redirect', SITE_URL . 'admin#topic/'.$valuesArray['topicReturn']);
    }

    $resCollections = array();
    if( isset( $valuesArray[ 'id' ] ) ) {
      $colInfo = $this->getCollectionsInfo( $valuesArray[ 'id' ] );
      if( $colInfo ) {
        $resCollections = $colInfo['options'];
        $valuesArray[ 'collections' ] = $colInfo['values'];
      }
    }

    $resMultimedia = array();
    if( isset( $valuesArray[ 'id' ] ) ) {
      $multimediaInfo = $this->getMultimediaInfo( $valuesArray[ 'id' ] );
      if( $multimediaInfo ) {
        $resMultimedia = $multimediaInfo['options'];
        $valuesArray[ 'multimediaGalleries' ] = $multimediaInfo['values'];
      }
    }

    // Geograpic Location
    Cogumelo::load('coreModel/DBUtils.php');
    if( isset($valuesArray['loc']) ) {
      $geoLocation = DBUtils::decodeGeometry($valuesArray['loc']);
      $valuesArray['locLat'] = $geoLocation['data'][0];
      $valuesArray['locLon'] = $geoLocation['data'][1];
    }

    $fieldsInfo = array(
      'rTypeId' => array(
        'params' => array( 'type' => 'reserved' )
      ),
      'rTypeIdName' => array(
        'params' => array( 'id' => 'rTypeIdName', 'type' => 'hidden' )
      ),
      'title' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Title' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'shortDescription' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Short description' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'mediumDescription' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Medium description' ), 'type' => 'textarea' )
      ),
      'content' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Content' ), 'type' => 'textarea',
          'htmlEditor' => 'true',
          'value' => '<p>ola mundo<br />...probando ;-)</p>' )
      ),
      'externalUrl' => array(
        'params' => array( 'label' => __( 'External URL' ) ),
        'rules' => array( 'maxlength' => '2000', 'url' => true )
      ),
      'image' => array(
        'params' => array( 'label' => __( 'Image' ), 'type' => 'file', 'id' => 'imgResource',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => '/imgResource' ),
        'rules' => array( 'minfilesize' => '1024', 'maxfilesize' => '2097152', 'accept' => 'image/jpeg' )
      ),
      'urlAlias' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'SEO: URL' ) ),
        'rules' => array( 'maxlength' => '2000' )
      ),
      'weight' => array(
        'params' => array( 'label' => __( 'Priority' ), 'type' => 'select',
          'options'=> array( '0' => __( 'Normal' ), '-20' => __( 'High' ), '20' => __( 'Low' ) ) )
      ),
      'headKeywords' => array(
        'params' => array( 'label' => __( 'SEO: Head Keywords' ) ),
        'rules' => array( 'maxlength' => '150' )
      ),
      'headDescription' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'SEO: Head Description' ) ),
        'rules' => array( 'maxlength' => '150' )
      ),
      'headTitle' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'SEO: Head Title' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'datoExtra1' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Extra information 1' ) ),
        'rules' => array( 'maxlength' => '1000' )
      ),
      'datoExtra2' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Extra information 2' ) ),
        'rules' => array( 'maxlength' => '1000' )
      ),
      'collections' => array(
        'params' => array( 'label' => __( 'Collections' ), 'type' => 'select', 'class' => 'cgmMForm-order',
        'multiple' => true, 'options'=> $resCollections, 'id' => 'resourceCollections' )
      ),
      'addCollections' => array(
        'params' => array( 'id' => 'resourceAddCollection', 'type' => 'button', 'value' => __( 'Add Collection' ))
      ),
      'multimediaGalleries' => array(
        'params' => array( 'label' => __( 'Collections' ), 'type' => 'select', 'class' => 'cgmMForm-order',
        'multiple' => true, 'options'=> $resMultimedia, 'id' => 'resourceMultimediaGalleries' )
      ),
      'addMultimediaGalleries' => array(
        'params' => array( 'id' => 'resourceAddMultimediaGallery', 'type' => 'button', 'value' => __( 'Add Multimedia Gallery' ))
      ),
      'published' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => 'Publicado' ))
      ),
      'topics' => array(
        'params' => array( 'label' => __( 'Topics' ), 'type' => 'checkbox', 'options'=> $this->getOptionsTopic() )
      ),
      'starred' => array(
        'params' => array( 'label' => __( 'Starred' ), 'type' => 'checkbox', 'options'=> $this->getOptionsTax( 'starred' ) )
      ),
      'locLat' => array(
        'params' => array( 'label' => __( 'Latitude' ) ),
        'rules' => array( 'number' => true )
      ),
      'locLon' => array(
        'params' => array( 'label' => __( 'Longitude' ) ),
        'rules' => array( 'number' => true )
      ),
      'defaultZoom' => array(
        'params' => array( 'label' => __( 'Zoom' ) ),
        'rules' => array( 'number' => true )
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    // Valadaciones extra
    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );
    $form->removeValidationRule( 'collections', 'inArray' );
    $form->removeValidationRule( 'multimediaGalleries', 'inArray' );

    // Si es una edicion, añadimos el ID y cargamos los datos
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );

      // Limpiando la informacion de terms para el form
      foreach( array( 'starred' ) as $taxFieldName ) {
        if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
          $taxFieldValues = array();
          foreach( $valuesArray[ $taxFieldName ] as $value ) {
            $taxFieldValues[] = ( is_array( $value ) ) ? $value[ 'id' ] : $value;
          }
          $valuesArray[ $taxFieldName ] = $taxFieldValues;
        }
      }

      $form->loadArrayValues( $valuesArray );
      // error_log( 'GeozzyResourceView getFormObj: ' . print_r( $valuesArray, true ) );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => __( 'Send' ), 'class' => 'gzzAdminToMove' ) );

    // Una vez que lo tenemos definido, guardamos el form en sesion
    $form->saveToSession();

    return( $form );
  }


  /**
   * Construimos el formulario completo
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Form
   */
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    error_log( "ResourceController: getFormObj()" );

    // error_log( "valuesArray: ".print_r( $valuesArray, true ) );
    $form = $this->getBaseFormObj( $formName, $urlAction, $valuesArray );

    $this->rTypeCtrl = $this->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );
    if( $this->rTypeCtrl ) {
      $rTypeFieldNames = $this->rTypeCtrl->manipulateForm( $form );
      // error_log( 'rTypeFieldNames: '.print_r( $rTypeFieldNames, true ) );
    }

    // Una vez que lo tenemos definido, guardamos el form en sesion
    $form->saveToSession();

    return( $form );
  }


  /**
   * Defino el formulario y creo su Bloque con su TPL
   *
   * @param $form object Form
   * @param $template object
   *
   * @return Template
   */
  public function formToTemplate( $form, $template = false ) {
    error_log( "ResourceController: formToTemplate()" );

    if( !$template ) {
      $template = new Template();
      $template->addClientStyles( 'masterResource.less');
      $template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );
    }

    $template->assign( 'formOpen', $form->getHtmpOpen() );
    $template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );
    $template->assign( 'formFieldsHiddenArray', array() );
    $template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );
    $template->assign( 'formClose', $form->getHtmlClose() );
    $template->assign( 'formValidations', $form->getScriptCode() );

    /* Cuadro información de recurso */
    if ($this->resData){
      $rtypeModel = new ResourcetypeModel();
      $rType = $rtypeModel->listItems( array( 'filters' => array('id' => $this->resData['rTypeId']) ) )->fetch();
      $rTypeName = $rType->getter('name_'.LANG_DEFAULT);

      $userModel = new UserModel();
      $user = $userModel->listItems( array( 'filters' => array('id' => $this->resData['user']) ) )->fetch();
      $userName = $user->getter('name');

      $timeCreation = date('d/m/Y', time($this->resData['timeCreation']));

      $template->assign( 'resourceId', $this->resData['id']);
      $template->assign( 'rTypeName', $rTypeName);
      $template->assign( 'timeCreation', $timeCreation);
      $template->assign( 'userName', $userName);

      if (isset($this->resData['userUpdate'])){
        $userUpdate = $userModel->listItems( array( 'filters' => array('id' => $this->resData['userUpdate']) ) )->fetch();
        $userUpdateName = $userUpdate->getter('name');
        $timeLastUpdate = date('d/m/Y', time($this->resData['timeLastUpdate']));
        $template->assign( 'timeLastUpdate', $timeLastUpdate);
        $template->assign( 'userUpdate', $userUpdateName);
      }

      if (isset($this->resData['averageVotes'])){
        $template->assign( 'timeLastUpdate', $this->resData['averageVotes']);
      }
    }

    $this->rTypeCtrl = $this->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) );
    if( $this->rTypeCtrl ) {
      $rTypeTemplate = $this->rTypeCtrl->manipulateFormTemplate( $form, $template );
      if( $rTypeTemplate ) {
        $template = $rTypeTemplate;
      }
    }

    return( $template );
  }


  /**
   * Defino el formulario y creo su Bloque con su TPL
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Template
   */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormBlock()" );

    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $template = $this->formToTemplate( $form );

    return( $template );
  }


  /**
   * Cargamos el contenido del Template del Form en el de Admin
   *
   * @param $formBlock Template Contiene el form y los datos cargados
   * @param $template Template Contiene la estructura de columnas para Admin
   * @param $adminViewResource AdminViewResource Acceso a los métodos usados en Admin
   */
  public function loadAdminFormColumns( $formBlock, $template, $adminViewResource ) {

    $adminColsInfo = $this->getAdminFormColumns( $formBlock, $template, $adminViewResource );

    // Pasamos al control al rType
    if( $this->rTypeCtrl ) {
      $rTypeAdminCols = $this->rTypeCtrl->manipulateAdminFormColumns( $formBlock, $template, $adminViewResource, $adminColsInfo );
      if( $rTypeAdminCols ) {
        $adminColsInfo = $rTypeAdminCols;
      }
    }

    // Metemos los bloques dentro de las columnas del Template
    foreach( $adminColsInfo as $colName => $colElements ) {
      if( count( $colElements ) ) {
        foreach( $colElements as $idName => $colElementInfo ) {
          list( $content, $title, $icon ) = $colElementInfo;
          $template->addToBlock( $colName, $adminViewResource->getPanelBlock( $content, $title, $icon ) );
        }
      }
    }
  }


  /**
   * Repartimos el contenido del Template del Form en elementos para las distintas columnas
   *
   * @param $formBlock Template Contiene el form y los datos cargados
   * @param $template Template Contiene la estructura de columnas para Admin
   * @param $adminViewResource AdminViewResource Acceso a los métodos usados en Admin
   *
   * @return Array Información de los elementos de cada columna
   */
  public function getAdminFormColumns( $formBlock, $template, $adminViewResource ) {
    $cols = array(
      'col8' => array(),
      'col4' => array()
    );

    // Fragmentamos el formulario generado
    $formImage = $adminViewResource->extractFormBlockFields( $formBlock, array( 'image' ) );
    $formPublished = $adminViewResource->extractFormBlockFields( $formBlock, array( 'published', 'weight' ) );
    $formSeo = $adminViewResource->extractFormBlockFields( $formBlock,
      array( 'urlAlias', 'headKeywords', 'headDescription', 'headTitle' ) );
    $formContacto = $adminViewResource->extractFormBlockFields( $formBlock, array( 'datoExtra1', 'datoExtra2' ) );
    $formCollections = $adminViewResource->extractFormBlockFields( $formBlock, array( 'collections', 'addCollections' ) );
    $formMultimediaGalleries = $adminViewResource->extractFormBlockFields( $formBlock, array( 'multimediaGalleries', 'addMultimediaGalleries' ) );
    $formLatLon = $adminViewResource->extractFormBlockFields( $formBlock, array( 'locLat', 'locLon', 'defaultZoom' ) );

    // El bloque que usa $formBlock contiene la estructura del form

    // Bloques de 8
    $cols['col8']['main'] = array( $formBlock, __('Main Resource information'), 'fa-archive' );

    if( $formLatLon ) {
      $formPartBlock = $this->setBlockPartTemplate($formLatLon);
      $cols['col8']['location'] = array( $formPartBlock , __('Location'), 'fa-archive' );
    }

    if( $formCollections ) {
      $formPartBlock = $this->setBlockPartTemplate($formCollections);
      $cols['col8']['collections'] = array( $formPartBlock, __('Collections of related resources'), 'fa-th-large' );
    }
    if( $formMultimediaGalleries ) {
      $formPartBlock = $this->setBlockPartTemplate($formMultimediaGalleries);
      $cols['col8']['multimedia'] = array( $formPartBlock, __('Multimedia galleries'), 'fa-th-large' );
    }

    if( $formSeo ) {
      $formPartBlock = $this->setBlockPartTemplate($formSeo);
      $cols['col8']['seo'] = array( $formPartBlock, __( 'SEO' ), 'fa-globe' );
    }


    // Bloques de 4
    if( $formPublished ) {
      $formPartBlock = $this->setBlockPartTemplate($formPublished);
      $cols['col4']['publication'] = array( $formPartBlock, __( 'Publication' ), 'fa-adjust' );
    }

    if( $formImage ) {
      $formPartBlock = $this->setBlockPartTemplate($formImage);
      $cols['col4']['image'] = array( $formPartBlock, __( 'Select a image' ), 'fa-file-image-o' );
    }

    // Componemos el bloque geolocalización
    $resourceLocLat = $formBlock->getTemplateVars('locLat');
    $resourceLocLon = $formBlock->getTemplateVars('locLon');
    $resourceDefaultZoom = $formBlock->getTemplateVars('defaultZoom');

    $locationData = '<div class="row locData">'.$resourceLocLat.'</div>
                     <div class="row locData">'.$resourceLocLon.'</div>
                     <div class="row locData">'.$resourceDefaultZoom.'</div>
                     <div class="row btn btn-primary col-md-offset-3">'.__("Authomatic Location").'</div>';


    $locAll = '<div class="location">
            <div class="row"><div class="col-lg-6"><div class="descMap">Haz click en el lugar donde se ubica el recurso<br>Podrás arrastrar y soltar la localización</div><div id="resourceLocationMap"></div></div>
            <div class="col-lg-6 locationData">'.$locationData.'</div></div>
            </div>';

    $adminColsInfo['col8']['location'] = array( $locAll, __( 'Location' ), 'fa-globe' );


    // Recuperamos las temáticas que tiene asociadas el recurso
    $resourceId = $formBlock->getTemplateVars('resourceId');
    $allTopics = $this->getOptionsTopic();

    $resourceTopicModel = new ResourceTopicModel();
    $resourceTopicList = $resourceTopicModel->listItems( array( 'filters' => array( 'resource' => $resourceId ) ) );
    $topicsHtml = '';
    if( $resourceTopicList ) {
      $relPrevInfo = array();
      while( $topicList = $resourceTopicList->fetch() ){
        $topics[$topicList->getter( 'topic' )] = $allTopics[$topicList->getter( 'topic' )];
      }

      if (isset($topics)){
        foreach ($topics as $topicId=>$topicName){
          $topicsHtml = $topicsHtml.'<div class="row"><div class="col-lg-4"></div><div class="col-lg-8">'.$topicName.'</div></div>';
        }
      }
    }

    // Recuperamos las taxonomías asociadas al recurso
    $starredHtml = '';
    $resourceTax = $this->getTermsInfoByGroupIdName( $resourceId );
    if (isset($resourceTax['starred'])){
      foreach ($resourceTax['starred'] as $tax){
        $starred[$tax['id']] = $tax['idName'];
      }
      if (isset($starred)){
        foreach ($starred as $starredId=>$starredName){
          $starredHtml = $starredHtml.'<div class="row"><div class="col-lg-4"></div><div class="col-lg-8">'.$starredName.'</div></div>';
        }
      }
    }

    $resourceType = $formBlock->getTemplateVars('rTypeName');
    $timeCreation = $formBlock->getTemplateVars('timeCreation');
    $user = $formBlock->getTemplateVars('userName');
    if ($formBlock->getTemplateVars('timeLastUpdate')){
      $timeLastUpdate = $formBlock->getTemplateVars('timeLastUpdate');
      $userUpdate = $formBlock->getTemplateVars('userUpdate');
      $update = $timeLastUpdate.' ('.$userUpdate.')';
    }else{
      $update =  '- - -';
    }
    $info = '<div class="infoBasic table-stripped">
            <div class="row"><div class="col-lg-4">ID</div><div class="col-lg-8">'.$resourceId.'</div></div>
            <div class="row"><div class="col-lg-4">Tipo</div><div class="col-lg-8">'.$resourceType.'</div></div>
            <div class="row"><div class="col-lg-4">Creado</div><div class="col-lg-8">'.$timeCreation.' ('.$user.')</div></div>
            <div class="row"><div class="col-lg-4">Actualizado</div><div class="col-lg-8">'.$update.'</div></div>
            <div class="row"><div class="col-lg-4">Temáticas</div><div class="col-lg-8"></div></div>'.$topicsHtml.'
            <div class="row"><div class="col-lg-4">Destacados</div><div class="col-lg-8"></div></div>'.$starredHtml.'
            </div>';

    $cols['col4']['info'] = array( $info, __( 'Information' ), 'fa-globe' );

    return $cols;
  }

  /**
    Se crea un nuevo template y se asigna el array de variables recibido
   */
  public function setBlockPartTemplate($formPartArray){
    $partTemplate = new Template();
    $partTemplate->addClientStyles( 'masterResource.less');
    $partTemplate->setTpl('resourceFormBlockPart.tpl', 'admin');
    $partTemplate->assign('formFieldsArray', $formPartArray);
    $partTemplate->assign('formFieldsHiddenArray', array());
    return $partTemplate;
  }

  /**
    Se reconstruye el formulario con sus datos y se realizan las validaciones que contiene
   */
  public function resFormLoad() {
    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    if( !$form->existErrors() ) {
      if( !$form->processFileFields() ) {
        $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea '.
          'necesario subirlos otra vez.', 'formError' );
      }
    }

    return $form;
  }

  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( $form ) {
    $urlAliasFieldNames = $form->multilangFieldNames( 'urlAlias' );
    $fieldName = is_array( $urlAliasFieldNames ) ? $urlAliasFieldNames['0'] : $urlAliasFieldNames;
    if( $form->isFieldDefined( $fieldName ) ) {
      $this->evalFormUrlAlias( $form, 'urlAlias' );
    }
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( $form ) {
    $resource = false;

    if( !$form->existErrors() ) {
      $useraccesscontrol = new UserAccessController();
      $user = $useraccesscontrol->getSessiondata();

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) && is_numeric( $form->getFieldValue( 'id' ) ) ) {
        $valuesArray[ 'userUpdate' ] = $user->getter( 'id' );
        $valuesArray[ 'timeLastUpdate' ] = date( "Y-m-d H:i:s", time() );
        unset( $valuesArray[ 'image' ] );
      }
      else {
        $valuesArray[ 'user' ] = $user->getter( 'id' );
        $valuesArray[ 'timeCreation' ] = date( "Y-m-d H:i:s", time() );
      }

      //Resource LOCATION
      if( isset( $valuesArray[ 'locLat' ] ) && isset( $valuesArray[ 'locLon' ] ) ) {
        Cogumelo::load( 'coreModel/DBUtils.php' );
        $valuesArray[ 'loc' ] = DBUtils::encodeGeometry(
          array(
            'type' => 'POINT',
            'data'=> array( $valuesArray[ 'locLat' ], $valuesArray[ 'locLon' ] )
          )
        );
        unset( $valuesArray[ 'locLat' ] );
        unset( $valuesArray[ 'locLon' ] );
      }
    }


    if( !$form->existErrors() ) {
      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $resource = new ResourceModel( $valuesArray );
      if( $resource === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    if( !$form->existErrors()) {

      // TRANSACTION START
      $resource->transactionStart();

      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    /**
      DEPENDENCIAS / RELACIONES
    */
    if( !$form->existErrors() && $form->isFieldDefined( 'image' ) ) {
      $this->setFormFiledata( $form, 'image', 'image', $resource );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'topics' ) ) {
      $this->setFormTopic( $form, 'topics', $resource );
    }

    if( !$form->existErrors() && ( $form->isFieldDefined( 'collections' ) || $form->isFieldDefined( 'multimediaGalleries' ) ) ) {
      $this->setFormCollection( $form, $resource );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'starred' ) ) {
      $this->setFormTax( $form, 'starred', 'starred', $form->getFieldValue( 'starred' ), $resource );
    }

    if( !$form->existErrors() ) {
      $this->setFormUrlAlias( $form, 'urlAlias', $resource );
    }
    /**
      DEPENDENCIAS (END)
    */
    if( !$form->existErrors()) {
      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    return $resource;
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( $form, $resource ) {
    if( !$form->existErrors() ) {
      // TRANSACTION COMMIT
      $resource->transactionCommit();

      echo $form->jsonFormOk();
    }
    else {
      // TRANSACTION ROLLBACK
      if( $resource ) {
        $resource->transactionRollback();
      }

      // $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
      echo $form->jsonFormError();
    }
  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */


  /**
    Filedata methods
   */
  public function setFormFiledata( $form, $fieldName, $colName, $resObj ) {
    $fileField = $form->getFieldValue( $fieldName );
    error_log( 'setFormFiledata fileInfo: '. print_r( $fileField, true ) );
    $fileFieldValues = false;
    $error = false;

    if( isset( $fileField['status'] ) ) {

      // error_log( 'To Model - fileInfo: '. print_r( $fileField[ 'values' ], true ) );
      // error_log( 'To Model - status: '.$fileField['status'] );

      switch( $fileField['status'] ) {
        case 'LOADED':
          // error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = $fileField[ 'values' ];
          break;
        case 'REPLACE':
          // error_log( 'To Model: '.$fileField['status'] );
          // // error_log( 'To Model - fileInfoPrev: '. print_r( $fileField[ 'prev' ], true ) );
          /**
            TODO: Falta eliminar o ficheiro anterior
          */
          $fileFieldValues = $fileField[ 'values' ];
          break;
        case 'DELETE':
          // error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = null;
          /**
            TODO: Falta eliminar o ficheiro anterior
          */
          break;
        case 'EXIST':
          // error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = $fileField[ 'values' ];
          break;
        default:
          error_log( 'To Model: DEFAULT='.$fileField['status'] );
          break;
      }

      if( $fileFieldValues !== false ) {
        if( $fileFieldValues === null ) {
          $resObj->setter( $colName, null );
        }
        else {
          $newFiledataModel = new FiledataModel( $fileFieldValues );
          if( $newFiledataModel->save() ) {
            $resObj->setter( $colName, $newFiledataModel->getter( 'id' ) );
          }
          else {
            $error = 'File save error';
          }
        }
      }
      else {
        $error = 'Not file value';
      }
    }

    if( $error ) {
      $form->addFieldRuleError( $fieldName, false, $error );
    }
  }


  /**
    ExtraData methods
   */
  private function setFormExtraData( $form, $fieldName, $colName, $baseObj ) {
    if( $form->isFieldDefined( $fieldName ) || $form->isFieldDefined( $fieldName.'_'.$form->langDefault ) ) {
      $baseId = $baseObj->getter( 'id' );

      $extraData = array( 'resource' => $baseId, 'name' => $colName );
      if( count( $form->langAvailable ) > 1 ) {
        foreach( $form->langAvailable as $langId ) {
          $extraData[ 'value_'.$langId ] = $form->getFieldValue( $fieldName.'_'.$langId );
        }
      }
      else {
        $extraData[ 'value' ] = $form->getFieldValue( $fieldName );
      }

      $relObj = new extraDataModel( $extraData );
      if( !$relObj->save() ) {
        foreach( $form->multilangFieldNames( $fieldName ) as $fieldNameLang ) {
          $form->addFieldRuleError( $fieldNameLang, false, __( 'Error setting values' ) );
        }
      }
    }
  }

  /**
    Taxonomy/Topic methods
   */

  public function getOptionsTopic() {
    $topics = array();
    $topicModel =  new TopicModel();
    $topicList = $topicModel->listItems();
    while( $topic = $topicList->fetch() ){
      $topics[ $topic->getter( 'id' ) ] = $topic->getter( 'name', LANG_DEFAULT );
    }

    return $topics;
  }

  public function getOptionsTax( $taxIdName ) {
    $options = array();
    $taxTermModel =  new TaxonomyTermModel();
    $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $taxIdName ),
      'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );
    while( $taxTerm = $taxTermList->fetch() ){
      $options[ $taxTerm->getter( 'id' ) ] = $taxTerm->getter( 'name', LANG_DEFAULT );
    }

    return $options;
  }



  public function getResTerms( $resId ) {
    $taxTerms = array();

    $taxTermModel =  new ResourceTaxonomytermModel();
    $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );

    while( $taxTerm = $taxTermList->fetch() ) {
      $taxTerms[ $taxTerm->getter( 'id' ) ] = $taxTerm->getter( 'taxonomyterm' );
    }

    error_log( "getResTerms( $resId ): ".print_r( $taxTerms, true ) );
    return( count( $taxTerms ) > 0 ? $taxTerms : false );
  }

  /**
   * Devolve os taxterm asociados ao recurso dado e a info da taxonomía a maiores
   */
  public function getTaxonomyAll( $resId ) {

    if( !$this->taxonomyAll ) {
      $taxTerms = array();

      $resourceTaxAllModel = new ResourceTaxonomyAllModel();
      $taxAllList = $resourceTaxAllModel->listItems(array( 'filters' => array( 'resource' => $resId ) ));
      if( $taxAllList ) {
        while( $taxTerm = $taxAllList->fetch() ) {
          $taxTerms[ $taxTerm->getter('id') ] = $taxTerm->getAllData( 'onlydata' );
        }
      }

      if( count( $taxTerms ) > 0 ) {
        $this->taxonomyAll = $taxTerms;
      }
    }

    // error_log( "getTaxonomyAll( $resId ): ".print_r( $this->taxonomyAll, true ) );
    return $this->taxonomyAll;
  }



  /**
   * Devolve en grupos os taxterm asociados ao recurso dado e a info da taxonomía a maiores
   */
  public function getTermsInfoByGroupId( $resId ) {
    $taxGrouped = array();

    $taxTerms = $this->getTaxonomyAll( $resId );
    if( $taxTerms ) {
      foreach( $taxTerms as $infoTerm ) {
        $idTaxGroup = $infoTerm[ 'taxgroup' ];
        if( !isset( $taxGrouped[ $idTaxGroup ] ) ) {
          $taxGrouped[ $idTaxGroup ] = array();
        }
        $taxGrouped[ $idTaxGroup ][ $infoTerm[ 'id' ] ] = $infoTerm;
      }
    }

    // error_log( "getTermsGroupedId( $resId ): ".print_r( $taxGrouped, true ) );
    return( count( $taxGrouped ) > 0 ? $taxGrouped : false );
  }


  /**
   * Devolve en grupos os taxterm asociados ao recurso dado e a info da taxonomía a maiores
   */
  public function getTermsInfoByGroupIdName( $resId ) {
    $taxGrouped = array();

    $taxTerms = $this->getTaxonomyAll( $resId );
    if( $taxTerms ) {
      foreach( $taxTerms as $infoTerm ) {
        $idNameTaxGroup = $infoTerm[ 'idNameTaxgroup' ];
        if( !isset( $taxGrouped[ $idNameTaxGroup ] ) ) {
          $taxGrouped[ $idNameTaxGroup ] = array();
        }
        $taxGrouped[ $idNameTaxGroup ][ $infoTerm[ 'id' ] ] = $infoTerm;
      }
    }

    // error_log( "getTermsInfoByGroupIdName( $resId ): ".print_r( $taxGrouped, true ) );
    return( count( $taxGrouped ) > 0 ? $taxGrouped : false );
  }



  public function getCollectionsInfo( $resId ) {
    error_log( "ResourceController: getCollectionsInfo( $resId )" );
    $colInfo = array(
      'options' => array(),
      'values' => array()
    );

    $resourceCollectionsModel =  new ResourceCollectionsModel();

    if( isset( $resId ) ) {
      $resCollectionList = $resourceCollectionsModel->listItems(
        array(
          'filters' => array( 'resource' => $resId, 'CollectionModel.multimedia' => 0 ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'CollectionModel' )
        )
      );

      while( $res = $resCollectionList->fetch() ){

        $collections = $res->getterDependence( 'collection', 'CollectionModel' );
        if( $collections ){
          $colInfo[ 'options' ][ $res->getter( 'collection' ) ] = $collections[ 0 ]->getter( 'title', LANG_DEFAULT );
          $colInfo[ 'values' ][] = $res->getter( 'collection' );
        }
      }
    }
    // error_log( "ResourceController: getCollectionsInfo = ". print_r( $colInfo, true ) );
    return ( count( $colInfo['values'] ) > 0 ) ? $colInfo : false;
  }

  public function getMultimediaInfo( $resId ) {
    error_log( "ResourceController: getMultimediaInfo( $resId )" );
    $multimediaInfo = array(
      'options' => array(),
      'values' => array()
    );

    $resourceCollectionsModel =  new ResourceCollectionsModel();

    if( isset( $resId ) ) {
      $resMultimediaList = $resourceCollectionsModel->listItems(
        array(
          'filters' => array( 'resource' => $resId, 'CollectionModel.multimedia' => 1 ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'CollectionModel' )
        )
      );

      while( $res = $resMultimediaList->fetch() ){
        $multimediaGalleries = $res->getterDependence( 'collection', 'CollectionModel' );
        if( $multimediaGalleries ){
          $multimediaInfo[ 'options' ][ $res->getter( 'collection' ) ] = $multimediaGalleries[ 0 ]->getter( 'title', LANG_DEFAULT );
          $multimediaInfo[ 'values' ][] = $res->getter( 'collection' );
        }
      }
    }

    // error_log( "ResourceController: getCollectionsInfo = ". print_r( $colInfo, true ) );
    return ( count( $multimediaInfo['values'] ) > 0 ) ? $multimediaInfo : false;
  }


  private function setFormTopic( $form, $fieldName, $baseObj ) {
    $baseId = $baseObj->getter( 'id' );
    $formValues = $form->getFieldValue( $fieldName );
    $relPrevInfo = false;

    if( $formValues !== false && !is_array( $formValues ) ) {
      $formValues = array( $formValues );
    }

    // Si estamos editando, repasamos y borramos relaciones sobrantes
    if( $baseId ) {
      $relModel = new ResourceTopicModel();
      $relPrevList = $relModel->listItems( array( 'filters' => array( 'resource' => $baseId ) ) );
      if( $relPrevList ) {
        // estaban asignados antes
        $relPrevInfo = array();
        while( $relPrev = $relPrevList->fetch() ){
          $relPrevInfo[ $relPrev->getter( 'topic' ) ] = $relPrev->getter( 'id' );
          if( $formValues === false || !in_array( $relPrev->getter( 'topic' ), $formValues ) ){ // desasignar
            $relPrev->delete();
          }
        }
      }
    }

    // Creamos-Editamos todas las relaciones
    if( $formValues !== false ) {
      $weight = 0;
      foreach( $formValues as $value ) {
        $weight++;
        $info = array( 'resource' => $baseId, 'topic' => $value, 'weight' => $weight );
        if( $relPrevInfo !== false && isset( $relPrevInfo[ $value ] ) ) { // Update
          $info[ 'id' ] = $relPrevInfo[ $value ];
        }
        $relObj = new ResourceTopicModel( $info );
        if( !$relObj->save() ) {
          $form->addFieldRuleError( $fieldName, false, __( 'Error setting values' ) );
          break;
        }
      }
    }
  }

  public function setFormTax( $form, $fieldName, $taxGroup, $taxTermIds, $baseObj ) {
    // error_log( "ResourceController: setFormTax $fieldName, $taxGroup, ".$baseObj->getter( 'id' ) );
    $relPrevInfo = false;
    $baseId = $baseObj->getter( 'id' );
    // $taxTermIds = $form->getFieldValue( $fieldName );

    if( $taxTermIds !== false && !is_array( $taxTermIds ) ) {
      $taxTermIds = ( $taxTermIds !== '' &&  is_numeric( $taxTermIds ) ) ? array( $taxTermIds ) : false;
    }

    // Si estamos editando, repasamos y borramos relaciones sobrantes
    if( $baseId ) {

      $relFilter = array( 'resource' => $baseId );

      if( is_numeric( $taxGroup ) ) {
        $relFilter[ 'taxgroup' ] = $taxGroup;
      }
      else {
        $relFilter[ 'idNameTaxgroup' ] = $taxGroup;
      }

      $relModel = new ResourceTaxonomyAllModel();
      $relPrevList = $relModel->listItems( array(
        'filters' => $relFilter
      ));

      if( $relPrevList ) {
        // estaban asignados antes
        $relPrevInfo = array();
        while( $relPrev = $relPrevList->fetch() ){
          $relPrevInfo[ $relPrev->getter( 'id' ) ] = $relPrev->getter( 'idResTaxTerm' );
          if( $taxTermIds === false || !in_array( $relPrev->getter( 'id' ), $taxTermIds ) ){ // desasignar
            $restermModel = new ResourceTaxonomytermModel();
            // buscamos el término eliminado y lo borramos
            $resterm = $restermModel->ListItems( array( 'filters' => array( 'resource' => $baseId, 'taxonomyterm' =>$relPrev->getter( 'id' ) ) ) )->fetch();
            $resterm->delete();
          }
        }
      }
    }

    // Creamos-Editamos todas las relaciones
    if( $taxTermIds !== false ) {
      $weight = 0;
      foreach( $taxTermIds as $value ) {
        $weight++;
        $info = array( 'resource' => $baseId, 'taxonomyterm' => $value, 'weight' => $weight );
        if( $relPrevInfo !== false && isset( $relPrevInfo[ $value ] ) ) { // Update
          $info[ 'id' ] = $relPrevInfo[ $value ];
        }

        $relObj = new ResourceTaxonomytermModel( $info );
        if( !$relObj->save() ) {
          $form->addFieldRuleError( $fieldName, false, __( 'Error setting values' ) );
          break;
        }
      }
    }
  } // setFormTax( $form, $fieldName, $taxGroup, $taxTermIds, $baseObj )

  private function setFormCollection( $form, $baseObj ) {

    $baseId = $baseObj->getter( 'id' );
    $formValuesCol = $form->getFieldValue( 'collections' );
    $formValuesMulti = $form->getFieldValue( 'multimediaGalleries' );

    if( !is_array($formValuesCol) && $formValuesCol != false ){
      $fVCol = array();
      array_push( $fVCol, $formValuesCol );
      $formValuesCol = $fVCol;
    }

    if( !is_array($formValuesMulti) && $formValuesMulti != false ){
      $fVMulti = array();
      array_push( $fVMulti, $formValuesMulti );
      $formValuesMulti = $fVMulti;
    }

    $formValuesCol = ( !is_array($formValuesCol) ) ? array() : $formValuesCol;
    $formValuesMulti = ( !is_array($formValuesMulti) ) ? array() : $formValuesMulti;

    $formValues = array_merge( $formValuesCol, $formValuesMulti );
    $relPrevInfo = false;

    if( $formValues !== false && !is_array( $formValues ) ) {
      $formValues = array( $formValues );
    }

    // Si estamos editando, repasamos y borramos relaciones sobrantes
    if( $baseId ) {
      $relModel = new ResourceCollectionsModel();
      $relPrevList = $relModel->listItems( array( 'filters' => array( 'resource' => $baseId ) ) );
      if( $relPrevList ) {
        // estaban asignados antes
        $relPrevInfo = array();
        while( $relPrev = $relPrevList->fetch() ){
          $relPrevInfo[ $relPrev->getter( 'collection' ) ] = $relPrev->getter( 'id' );
          if( $formValues === false || !in_array( $relPrev->getter( 'collection' ), $formValues ) ){ // desasignar
            $relPrev->delete();
          }
        }
      }
    }

    // Creamos-Editamos todas las relaciones
    if( $formValues !== false ) {
      $weight = 0;
      foreach( $formValues as $value ) {
        $weight++;
        $info = array( 'resource' => $baseId, 'collection' => $value, 'weight' => $weight );
        if( $relPrevInfo !== false && isset( $relPrevInfo[ $value ] ) ) { // Update
          $info[ 'id' ] = $relPrevInfo[ $value ];
        }
        $relObj = new ResourceCollectionsModel( $info );
        if( !$relObj->save() ) {
          $form->addFieldRuleError( $fieldName, false, __( 'Error setting values' ) );
          break;
        }
      }
    }
  }


  /**
    UrlAlias methods
   */

  private function evalFormUrlAlias( $form, $fieldName ) {
    foreach( $form->multilangFieldNames( $fieldName ) as $fieldNameLang ) {
      $url = $form->getFieldValue( $fieldNameLang );
      $error = false;
      if( $url !== '' ) {
        if( strpos( $url, '/' ) !== 0 ) {
          $error = 'La URL tiene que comenzar con una barra /';
        }
        elseif( strpos( $url, ' ' ) !== false ) {
          $error = 'La URL no puede contener espacios';
        }
      }
      if( $error ) {
        $form->addFieldRuleError( $fieldNameLang, false, $error );
      }
    }
  }

  private function setFormUrlAlias( $form, $fieldName, $resObj ) {
    error_log( "setFormUrlAlias( form, $fieldName, resObj )" );
    if( $form->isFieldDefined( $fieldName ) || $form->isFieldDefined( $fieldName.'_'.$form->langDefault ) ) {
      $resId = $resObj->getter('id');
      foreach( $form->langAvailable as $langId ) {
        $url = $form->getFieldValue( $fieldName.'_'.$langId );
        if( $this->setUrl( $resId, $langId, $url ) === false ) {
          $form->addFieldRuleError( $fieldName.'_'.$langId, false, __( 'Error setting URL alias' ) );
          break;
        }
      }
    }
  }

  private function setUrl( $resId, $langId, $urlAlias ) {
    error_log( "setUrl( $resId, $langId, $urlAlias )" );
    $result = true;

    if( !isset( $urlAlias ) || $urlAlias === false || $urlAlias === '' ) {
      $urlAlias = '/recurso/'.$resId;
    }

    $aliasArray = array( 'http' => 0, 'canonical' => 1, 'lang' => $langId,
      'urlFrom' => $urlAlias, 'urlTo' => null, 'resource' => $resId
    );

    $elemModel = new UrlAliasModel();
    $elemsList = $elemModel->listItems( array( 'filters' => array( 'canonical' => 1, 'resource' => $resId,
      'lang' => $langId ) ) );
    if( $elem = $elemsList->fetch() ) {
      error_log( 'setUrl: Xa existe - '.$elem->getter( 'id' ) );
      $aliasArray[ 'id' ] = $elem->getter( 'id' );
    }

    $elemModel = new UrlAliasModel( $aliasArray );
    if( $elemModel->save() === false ) {
      $result = false;
      error_log( 'setUrl: ERROR gardando a url' );
    }
    else {
      $result = $elemModel->getter( 'id' );
      error_log( 'setUrl: Creada/Actualizada - '.$result );
    }

    return $result;
  }





  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( $resData ) {
    error_log( "GeozzyResourceView: getViewBlock()" );

    $resBlock = $this->getResourceBlock( $resData );

    $this->getRTypeCtrl( $resData[ 'rTypeId' ] );

    if( $this->rTypeCtrl ) {
      error_log( 'GeozzyResourceView: rTypeCtrl->getViewBlock' );
      $rTypeBlock = $this->rTypeCtrl->getViewBlock( $resBlock );
      if( $rTypeBlock ) {
        error_log( 'GeozzyResourceView: resBlock = rTypeBlock' );
        $resBlock = $rTypeBlock;
      }
    }

    return( $resBlock );
  } // function getViewBlock( $resData )


  public function getResourceBlock( $resData ) {
    error_log( "GeozzyResourceView: getResourceBlock()" );

    $template = new Template();

    // DEBUG
    $htmlMsg = "\n<pre>\n" . print_r( $resData, true ) . "\n</pre>\n";

    foreach( $resData as $key => $value ) {
      $template->assign( $key, $value );
      // error_log( $key . ' === ' . print_r( $value, true ) );
    }

    /*
    // Cargo los datos de image dentro de los del recurso
    if( $resData[ 'image' ] !== false ) {
      error_log( "" . print_r( $resData[ 'image' ], true ) );
      $titleImage = isset( $resData[ 'image' ][ 'title' ] ) ? $resData[ 'image' ][ 'title' ] : '';
      $template->assign( 'image', '<img src="/cgmlformfilews/' . $resData[ 'image' ][ 'id' ] . '"
        alt="' . $titleImage . '" title="' . $titleImage . '"></img>' );
      error_log( 'getterDependence fileData: ' . print_r( $resData[ 'image' ], true ) );
    }
    else {
      $template->assign( 'image', '<p>'.__('None').'</p>' );
    }
    */

    $collections = $this->getCollectionsInfo( $resData[ 'id' ] );
    error_log( "collections = ". print_r( $collections, true ) );

    if( $collections ) {
      foreach( $collections[ 'values' ] as $collectionId ) {
        //$collectionBlock = $this->getCollectionBlock( $collectionId );
        $collectionBlock = $this->getCollectionBlock( $collections[ 'values' ][ '0' ][ 'data' ] );
        if( $collectionBlock ) {
          $template->addToBlock( 'collections', $collectionBlock );
        }
      }
    }
    $template->addClientStyles( 'masterResource.less');
    $template->setTpl( 'resourceViewBlock.tpl', 'geozzy' );

    return( $template );
  } // function getResourceBlock( $resData )


  public function getCollectionBlock( $collection ) {
    error_log( "GeozzyResourceView: getCollectionBlock()" );

    $template = false;

    /**
      Cargamos os datos da collection e metemolos no tpl para crear un bloque
      Parece que funciona, falta cargar a imaxe e a colección de recursos asociados pq teño dúbidas
      */

      $template = new Template();
      $template->assign( 'title', $collection['title_'.LANG_DEFAULT] );
      $template->assign( 'shortDescription', $collection['shortDescription_'.LANG_DEFAULT] );
      $template->assign( 'image', '<p>'.__('None').'</p>' );
      $template->assign( 'collectionResources', 'Listado dos recursos da colección Num. '.$collection['id'] );


      $template->setTpl( 'resourceCollectionViewBlock.tpl', 'geozzy' );

      return( $template );

    /*
      $collectionModel =  new CollectionModel();

      $collectionList = $collectionModel->listItems(
        array(
          'filters' => array( 'id' => $collectionId ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'FiledataModel', 'CollectionResourcesModel', 'ResourceModel' )
        )
      );

      while( $res = $resCollectionList->fetch() ){
        $collections = $res->getterDependence( 'collection', 'ResourceModel' );
        $colInfo[ 'options' ][ $res->getter( 'collection' ) ] = $collections[ 0 ]->getter( 'title', LANG_DEFAULT );
        $colInfo[ 'values' ][] = $res->getter( 'collection' );
      }

      $template = new Template();

      // DEBUG
      $htmlMsg = "\n<pre>\n" . print_r( $resObj->getAllData( '' ), true ) . "\n</pre>\n";

      foreach( $resObj->getCols() as $key => $value ) {
        $template->assign( $key, $resObj->getter( $key ) );
        // error_log( $key . ' === ' . print_r( $resObj->getter( $key ), true ) );
      }

      // Cargo los datos de image dentro de los del recurso
      $fileDep = $resObj->getterDependence( 'image' );
      if( $fileDep !== false ) {
        $titleImage = $fileDep['0']->getter('title');
        $template->assign( 'image', '<img src="/cgmlformfilews/' . $fileDep['0']->getter('id') . '"
          alt="' . $titleImage . '" title="' . $titleImage . '"></img>' );
        // error_log( 'getterDependence fileData: ' . print_r( $fileDep['0']->getAllData(), true ) );
      }
      else {
        $template->assign( 'image', '<p>'.__('None').'</p>' );
      }

    */


  } // function getCollectionBlock( $collection )


} // class ResourceController
