<?php
geozzy::load( 'controller/RTypeController.php' );
geozzy::load( 'controller/RExtController.php' );



/**
METODOS A CAMBIAR/ELIMINAR

loadResourceObject

getResourceData: Controlar ben translate e cargar a maioria dos datos

formToTemplate

loadAdminFormColumns

getAdminFormColumns

setBlockPartTemplate

getViewBlock

getResourceBlock

getCollectionBlock
**/





class ResourceController {

  public $rTypeCtrl = null;
  public $rTypeIdName = null;
  public $resObj = null;
  public $resData = null;
  private $taxonomyAll = null;
  public $actLang = null;
  public $defLang = null;
  public $allLang = null;

  public function __construct( $resId = false ) {
    // error_log( 'ResourceController::__construct' );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();

    global $C_LANG, $LANG_AVAILABLE; // Idioma actual, cogido de la url
    $this->actLang = $C_LANG;
    $this->defLang = LANG_DEFAULT;
    $this->allLang = $LANG_AVAILABLE;

    if( $resId ) {
      $this->loadResourceObject( $resId );
    }
  }

  /**
   *  Cargando controlador del RType
   */
  public function getRTypeCtrl( $rTypeId = false ) {
    // error_log( "GeozzyResourceView: getRTypeCtrl( $rTypeId )" );

    if( !$this->rTypeCtrl ) {
      $rTypeIdName = $this->getRTypeIdName( $rTypeId );
      if( class_exists( $rTypeIdName ) ) {
        // error_log( "GeozzyResourceView: getRTypeCtrl = $rTypeIdName" );
        $rTypeIdName::autoIncludes();
        $rTypeCtrlClassName = $rTypeIdName.'Controller';
        $this->rTypeCtrl = new $rTypeCtrlClassName( $this );
      }
    }

    return $this->rTypeCtrl;
  }


  /**
   *  Cargando IdName del RType
   */
  public function getRTypeIdName( $rTypeId = false, $resId = false ) {
    //error_log( "GeozzyResourceView: getRTypeIdName( $rTypeId )" );
    $rTypeIdName = false;

    if( $rTypeId === false ) {
      $resData = $this->getResourceData( $resId );
      $rTypeId = ( $resData ) ? $resData['rTypeId'] : false;
    }
    if( $rTypeId !== false ) {
      $rTypeModel = new ResourcetypeModel();
      $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'id' => $rTypeId ) ) );
      if( $rTypeInfo = $rTypeList->fetch() ) {
        $rTypeIdName = $rTypeInfo->getter( 'idName' );
      }
    }

    return $rTypeIdName;
  }


  /**
   * Load resource object
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function loadResourceObject( $resId = false ) {
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
  public function getResourceData( $resId = false, $translate = false ) {
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

      // Geograpic Location
      Cogumelo::load('coreModel/DBUtils.php');
      if( isset( $resourceData['loc'] ) ) {
        $geoLocation = DBUtils::decodeGeometry( $resourceData['loc'] );
        $resourceData['locLat'] = $geoLocation['data'][0];
        $resourceData['locLon'] = $geoLocation['data'][1];
      }

      // Cargo los datos de urlAlias dentro de los del recurso
      $urlAliasDep = $this->resObj->getterDependence( 'id', 'UrlAliasModel' );
      if( $urlAliasDep !== false ) {
        foreach( $urlAliasDep as $urlAlias ) {
          $urlLang = $urlAlias->getter('lang');
          if( $urlLang ) {
            $resourceData[ 'urlAlias_'.$urlLang ] = $urlAlias->getter('urlFrom');
            if ($urlLang == $this->actLang){
              $resourceData[ 'urlAlias'] = $resourceData[ 'urlAlias_'.$urlLang ];
            }
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
        $topicsArray = array();
        foreach( $topicsDep as $topicRel ) {
          $topicsArray[ $topicRel->getter('id') ] = $topicRel->getter('topic');
          //$topicsIdsArray[$topicRel->getter('topic')] = $topicRel->getter('topic');
        }
        $resourceData[ 'topics' ] = $topicsArray;
        $resourceData[ 'topic' ] = current( $resourceData[ 'topics' ] );

        $topicModel = new TopicModel();
        foreach( $topicsArray as $i => $topicId ) {
          $resourceTopicList[$topicId] = $topicModel->listItems( array( 'filters' => array( 'id' => $topicId ) ) )->fetch()->getter('name');
        }
        $resourceData[ 'topicsName' ] = $resourceTopicList;
        /**
          TODO: Asegurarse de que os topics se cargan en orden
        */
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
    // error_log( "ResourceController: getBaseFormObj()" );
    // error_log( "valuesArray: ".print_r( $valuesArray, true ) );

    $form = new FormController( $formName, $urlAction );

    // $form->setSuccess( 'accept', __( 'Thank you' ) );

    /* Establecemos la página a la que debe retornar */
    if( !isset($valuesArray['topicReturn']) ) {
      if (isset($valuesArray['typeReturn'])){ // tabla de páginas
        $form->setSuccess( 'redirect', SITE_URL . 'admin#resourcepage/list/type/'.$valuesArray['typeReturn']);
      }
      else{ // tabla general de contenidos
        $form->setSuccess( 'redirect', SITE_URL . 'admin#resource/list' );
      }
    }
    else { // tabla de recursos de una temática
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
        'params' => array( 'label' => __( 'Content' ), 'type' => 'textarea', 'htmlEditor' => 'true' )
      ),
      'externalUrl' => array(
        'params' => array( 'label' => __( 'External URL' ) ),
        'rules' => array( 'maxlength' => '2000', 'url' => true )
      ),
      'image' => array(
        'params' => array( 'label' => __( 'Image' ), 'type' => 'file', 'id' => 'imgResource',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => ResourceModel::$cols['image']['uploadDir'] ),
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
        'translate' => true,
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
        'params' => array( 'label' => __( 'Galleries' ), 'type' => 'select', 'class' => 'cgmMForm-order',
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
    // error_log( "ResourceController: getFormObj()" );

    // error_log( "valuesArray: ".print_r( $valuesArray, true ) );
    $form = $this->getBaseFormObj( $formName, $urlAction, $valuesArray );

    if( $this->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) ) ) {
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
    // error_log( "ResourceController: formToTemplate()" );

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

      $timeCreation = gmdate('d/m/Y', time($this->resData['timeCreation']));

      $template->assign( 'resourceId', $this->resData['id']);
      $template->assign( 'rTypeName', $rTypeName);
      $template->assign( 'timeCreation', $timeCreation);
      $template->assign( 'userName', $userName);

      if (isset($this->resData['userUpdate'])){
        $userUpdate = $userModel->listItems( array( 'filters' => array('id' => $this->resData['userUpdate']) ) )->fetch();
        $userUpdateName = $userUpdate->getter('name');
        $timeLastUpdate = gmdate('d/m/Y', time($this->resData['timeLastUpdate']));
        $template->assign( 'timeLastUpdate', $timeLastUpdate);
        $template->assign( 'userUpdate', $userUpdateName);
      }

      if (isset($this->resData['averageVotes'])){
        $template->assign( 'averageVotes', $this->resData['averageVotes']);
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








  public function getFormBlockInfo( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "GeozzyResourceView: getFormBlockInfo()" );

    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $formBlockInfo = $this->rTypeCtrl->getFormBlockInfo( $form );
    $formBlockInfo['objForm'] =  $form;

    return( $formBlockInfo );
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
    // error_log( "GeozzyResourceView: getFormBlock()" );

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

    // Componemos el bloque geolocalización
    $templateBlock = $formBlock->getTemplateVars('formFieldsArray');
    if (isset($templateBlock['locLat'])){
      $resourceLocLat = $templateBlock['locLat'];
      $resourceLocLon = $templateBlock['locLon'];
      $resourceDefaultZoom = $templateBlock['defaultZoom'];

      $locationData = '<div class="row">'.
        '<div class="col-md-3">'.$resourceLocLat.'</div>'.
        '<div class="col-md-3">'.$resourceLocLon.'</div>'.
        '<div class="col-md-3">'.$resourceDefaultZoom.'</div>'.
        '<div class="col-md-3"><div class="automaticBtn btn btn-primary">'.__("Automatic Location").'</div></div></div>';

      $locAll = '<div class="row location">'.
          '<div class="col-lg-12 mapContainer">'.
            '<div class="descMap">Haz click en el lugar donde se ubica el recurso, podrás arrastrar y soltar la localización</div>'.
          '</div>'.
          '<div class="col-lg-12 locationData">'.$locationData.'</div>'.
        '</div>';

      $cols['col8']['location'] = array( $locAll, __( 'Location' ), 'fa-globe' );
    }


    // Recuperamos las temáticas que tiene asociadas el recurso
    $resourceId = $formBlock->getTemplateVars('resourceId');

    $allTopics = $this->getOptionsTopic();
    $resourceTopicModel = new ResourceTopicModel();
    $resourceTopicList = $resourceTopicModel->listItems( array( 'filters' => array( 'resource' => $resourceId ) ) );
    $topicsHtml = '';
    if( $resourceTopicList ) {
      while( $topicList = $resourceTopicList->fetch() ) {
        $allTopics[ $topicList->getter( 'topic' ) ];
        $topicsHtml = $topicsHtml.'<div class="row rowWhite"><div class="infoCol col-md-4"></div>'.
          '<div class="infoColData col-md-8">'.$allTopics[ $topicList->getter( 'topic' ) ].'</div></div>';
      }
    }

    // Recuperamos las taxonomías asociadas al recurso
    $starredHtml = '';
    $resourceTax = $this->getTermsInfoByGroupIdName( $resourceId );
    if( isset( $resourceTax['starred'] ) && count( $resourceTax['starred'] ) > 0 ) {
      foreach( $resourceTax['starred'] as $tax ) {
        $starredHtml = $starredHtml.'<div class="row rowWhite"><div class="infoCol col-md-4"></div><div class="infoColData col-md-8">'.$tax['idName'].'</div></div>';
      }
    }

    $resourceType = $formBlock->getTemplateVars('rTypeName');
    $timeCreation = $formBlock->getTemplateVars('timeCreation');
    $user = $formBlock->getTemplateVars('userName');
    if( $formBlock->getTemplateVars('timeLastUpdate') ) {
      $timeLastUpdate = $formBlock->getTemplateVars('timeLastUpdate');
      $userUpdate = $formBlock->getTemplateVars('userUpdate');
      $update = $timeLastUpdate.' ('.$userUpdate.')';
    }
    else{
      $update =  '- - -';
    }
    $info = '<div class="infoBasic">'.
      '<div class="row"><div class="infoCol col-md-4">ID</div><div class="infoColData col-md-8">'.$resourceId.'</div></div>'.
      '<div class="row"><div class="infoCol col-md-4">Tipo</div><div class="infoColData col-md-8">'.$resourceType.'</div></div>'.
      '<div class="row"><div class="infoCol col-md-4">Creado</div><div class="infoColData col-md-8">'.$timeCreation.' ('.$user.')</div></div>'.
      '<div class="row"><div class="infoCol col-md-4">Actualizado</div><div class="infoColData col-md-8">'.$update.'</div></div>'.
      '<div class="row"><div class="infoCol col-md-12">Temáticas</div></div>'.$topicsHtml.
      '<div class="row"><div class="infoCol col-md-12">Destacados</div></div>'.$starredHtml.
      '</div>';

    $cols['col4']['info'] = array( $info, __( 'Information' ), 'fa-globe' );

    return $cols;
  }

  /**
    Se crea un nuevo template y se asigna el array de variables recibido
   */
  public function setBlockPartTemplate( $formPartArray ) {
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
        $valuesArray[ 'timeLastUpdate' ] = gmdate( "Y-m-d H:i:s", time() );
        unset( $valuesArray[ 'image' ] );
      }
      else {
        $valuesArray[ 'user' ] = $user->getter( 'id' );
        $valuesArray[ 'timeCreation' ] = gmdate( "Y-m-d H:i:s", time() );
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
    // error_log( 'setFormFiledata fileInfo: '. print_r( $fileField, true ) );
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
            TODO: Falta ver se eliminamos o ficheiro anterior
          */
          $fileFieldValues = $fileField[ 'values' ];
          break;
        case 'DELETE':
          // error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = null;
          /**
            TODO: Falta ver se eliminamos o ficheiro anterior
          */
          break;
        case 'EXIST':
          // error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = $fileField[ 'values' ];
          break;
        default:
          // error_log( 'To Model: DEFAULT='.$fileField['status'] );
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

    // error_log( "getResTerms( $resId ): ".print_r( $taxTerms, true ) );
    return( count( $taxTerms ) > 0 ? $taxTerms : false );
  }

  /**
   * Devolve os taxterm asociados ao recurso dado e a info da taxonomía a maiores
   */
  public function getTaxonomyAll( $resId ) {
    $taxTerms = array();

    if( !$this->taxonomyAll || $this->resObj->getter('id') != $resId ) {

      $resourceTaxAllModel = new ResourceTaxonomyAllModel();
      $taxAllList = $resourceTaxAllModel->listItems(array( 'filters' => array( 'resource' => $resId ) ));
      if( $taxAllList ) {
        while( $taxTerm = $taxAllList->fetch() ) {
          $taxTerms[ $taxTerm->getter('id') ] = $taxTerm->getAllData( 'onlydata' );
        }
      }

      if( $this->resObj && $this->resObj->getter('id') === $resId ) {
        $this->taxonomyAll = $taxTerms;
      }
    }
    else {
      $taxTerms = $this->taxonomyAll;
    }

    // error_log( "getTaxonomyAll( $resId ): ".print_r( $this->taxonomyAll, true ) );
    return $taxTerms;
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
    // error_log( "ResourceController: getCollectionsInfo( $resId )" );
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
     //error_log( "ResourceController: getCollectionsInfo = ". print_r( $colInfo, true ) );
    return ( count( $colInfo['values'] ) > 0 ) ? $colInfo : false;
  }

  public function getMultimediaInfo( $resId ) {
    // error_log( "ResourceController: getMultimediaInfo( $resId )" );
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

    // print('SET TOPIC:');
    // print_r($baseObj->data);
    $baseId = $baseObj->getter( 'id' );

    $formValues = $form->getFieldValue( $fieldName );



    $relPrevInfo = false;

    if( $formValues !== false && !is_array( $formValues ) ) {
      $formValues = array( $formValues );
    }

    if (count($formValues)===1){
      $elm = current($formValues);
      if (!$elm || $elm === 0){
         $formValues = false;
      }
    }

    if( $formValues !== false ) {
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
      $weight = 0;
      foreach( $formValues as $value ) {
        $weight++;
        $info = array( 'resource' => $baseId, 'topic' => $value, 'weight' => $weight );
        if( $relPrevInfo !== false && isset( $relPrevInfo[ $value ] ) ) { // Update
          $info[ 'id' ] = $relPrevInfo[ $value ];
        }

        // Creamos la nueva relación
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
  private $urlTranslate = array(
    'from' => array( 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï',
      'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä',
      'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù',
      'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č',
      'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ',
      'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ',
      'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň',
      'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ',
      'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů',
      'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ',
      'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ',
      'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ' ),
    'to'   => array( 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I',
      'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a',
      'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u',
      'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c',
      'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G',
      'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i',
      'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N',
      'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S',
      's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u',
      'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o',
      'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A',
      'a', 'A', 'a', 'O', 'o' ),
  );

  private function sanitizeUrl( $url ) {
    $url = str_replace( $this->urlTranslate[ 'from' ], $this->urlTranslate[ 'to' ], $url );
    $url = preg_replace( '/[_\'" \+\.]/i', '-', $url );
    $url = filter_var( $url, FILTER_SANITIZE_URL );

    return $url;
  }

  private function evalFormUrlAlias( $form, $fieldName ) {
    foreach( $form->multilangFieldNames( $fieldName ) as $fieldNameLang ) {
      $url = $form->getFieldValue( $fieldNameLang );
      $error = false;
      if( $url !== '' ) {
        $url = $this->sanitizeUrl( $url );

        if( strpos( $url, '/' ) !== 0 ) {
          $error = 'La URL tiene que comenzar con una barra /';
        }
        else {
          if( filter_var( 'http://www.test.com'.$url, FILTER_VALIDATE_URL) === false ) {
            $error = 'La URL no es válida';
          }
        }
      }
      if( $error ) {
        $form->addFieldRuleError( $fieldNameLang, false, $error );
      }
    }
  }

  private function setFormUrlAlias( $form, $fieldName, $resObj ) {
    // error_log( "setFormUrlAlias( form, $fieldName, resObj )" );
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

  public function getUrlByPattern( $resId, $langId = false ) {
    // error_log( "getUrlByPattern( $resId, $langId )" );
    global $CGMLCONF;
    $urlAlias = '/'.$CGMLCONF['geozzy']['resourceURL'].'/'.$resId;

    $pattern = '/';


    if( isset( $CGMLCONF['geozzy']['resource']['urlAliasPatterns'] ) ) {
      $patterns = $CGMLCONF['geozzy']['resource']['urlAliasPatterns'];
      if( isset( $patterns['default'] ) ) {
        $pattern = $patterns['default'];
      }
      $rTypeIdName = $this->getRTypeIdName( false, $resId );
      if( $rTypeIdName && isset( $patterns[ $rTypeIdName ] ) ) {
        if( isset( $patterns[ $rTypeIdName ]['default'] ) ) {
          $pattern = $patterns[ $rTypeIdName ]['default'];
        }
        if( isset( $patterns[ $rTypeIdName ][ $langId ] ) ) {
          $pattern = $patterns[ $rTypeIdName ][ $langId ];
        }
      }
    }

    $resData = $this->getResourceData( $resId );
    if( $resData ) {
      if( $langId ) {
        if( isset( $resData[ 'title_'.$langId ] ) && $resData[ 'title_'.$langId ] !== '' ) {
          $urlAlias = $pattern . strtolower( $resData[ 'title_'.$langId ] );
        }
        else {
          if( isset( $resData[ 'title_'.$this->defLang ] ) && $resData[ 'title_'.$this->defLang ] !== '' ) {
            $urlAlias = $pattern . strtolower( $resData[ 'title_'.$this->defLang ] );
          }
        }
      }
      else {
        if( isset( $resData['title'] ) && $resData['title'] !== '' ) {
          $urlAlias = $pattern . strtolower( $resData['title'] );
        }
        else {
          if( isset( $resData[ 'title_'.$this->defLang ] ) && $resData[ 'title_'.$this->defLang ] !== '' ) {
            $urlAlias = $pattern . strtolower( $resData[ 'title_'.$this->defLang ] );
          }
        }
      }
    }

    return $this->sanitizeUrl( $urlAlias );
  }


  public function setUrl( $resId, $langId = false, $urlAlias = false ) {
    // error_log( "setUrl( $resId, $langId, $urlAlias )" );
    $result = true;

    global $CGMLCONF;

    if( !isset( $urlAlias ) || $urlAlias === false || $urlAlias === '' ) {
      $urlAlias = $this->getUrlByPattern( $resId, $langId );
      //$urlAlias = '/'.$CGMLCONF['geozzy']['resourceURL'].'/'.$resId;
      // error_log( "setUrl: urlAlias automatico: $urlAlias " );
    }

    $urlAlias = $this->sanitizeUrl( $urlAlias );

    $aliasArray = array( 'http' => 0, 'canonical' => 1, 'lang' => $langId,
      'urlFrom' => $urlAlias, 'urlTo' => null, 'resource' => $resId
    );

    $elemModel = new UrlAliasModel();
    $elemsList = $elemModel->listItems( array( 'filters' => array( 'canonical' => 1, 'resource' => $resId,
      'lang' => $langId ) ) );
    if( $elem = $elemsList->fetch() ) {
      // error_log( 'setUrl: Xa existe - '.$elem->getter( 'id' ) );
      $aliasArray[ 'id' ] = $elem->getter( 'id' );
    }

    $elemModel = new UrlAliasModel( $aliasArray );
    if( $elemModel->save() === false ) {
      $result = false;
      error_log( 'setUrl: ERROR gardando a url' );
    }
    else {
      $result = $elemModel->getter( 'id' );
      // error_log( 'setUrl: Creada/Actualizada - '.$result );
    }

    return $result;
  }








  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( $resData = false ) {
    // error_log( "GeozzyResourceView: getViewBlock()" );

    if( !$resData ) {
      $resData = $this->getResourceData(); // true -> translated version
    }

    $resBlock = $this->getResourceBlock( $resData );
    //$resBlock = false;

    $this->getRTypeCtrl( $resData[ 'rTypeId' ] );

    if( $this->rTypeCtrl ) {
      // error_log( 'GeozzyResourceView: rTypeCtrl->getViewBlock' );
      $rTypeBlock = $this->rTypeCtrl->getViewBlock( $resBlock );
      if( $rTypeBlock ) {
        // error_log( 'GeozzyResourceView: resBlock = rTypeBlock' );
        $resBlock = $rTypeBlock;
      }
    }

    return( $resBlock );
  } // function getViewBlock( $resData )



  /**
    Datos y template por defecto del ResourceBlock
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "GeozzyResourceView: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => $this->getResourceData( $resId, true ),
      'ext' => array()
    );

    if( $this->getRTypeCtrl() ) {
      // error_log( 'GeozzyResourceView: rTypeCtrl->getViewBlockInfo' );
      $viewBlockInfo = $this->rTypeCtrl->getViewBlockInfo( );
    }

    return( $viewBlockInfo );
  } // function getViewBlockInfo()


  /**
    Devuelve resData con los campos traducibles en el idioma actual
  */
  public function getTranslatedData( $resData ) {

    foreach ( $resData as $key => $data ) {
      if( strpos($key,'_'.$this->actLang) ) { // existe en el idioma actual
        $key_parts = explode('_'.$this->actLang, $key);
        if( $data && $data !== "") {
          $resData[$key_parts[0]] = $data;
        }
        else{
          $resData[$key_parts[0]] = $resData[$key_parts[0].'_'.$this->defLang];
        }
      }
    }
    return $resData;
  }

  public function getResourceBlock( $resData ) {
    // error_log( "GeozzyResourceView: getResourceBlock()" );

    $template = new Template();

    // DEBUG
    $htmlMsg = "\n<pre>\n" . print_r( $resData, true ) . "\n</pre>\n";

    foreach( $resData as $key => $value ) {
      $template->assign( $key, $value );
      // error_log( $key . ' === ' . print_r( $value, true ) );
    }


    $collections = $this->getCollectionsInfo( $resData[ 'id' ] );
    // error_log( "collections = ". print_r( $collections, true ) );

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

  public function getResourceThumbnail( $param ) {

    $imgDefault = false;
    $thumbImg = false;
    $isYoutubeID = false;

    if( array_key_exists( 'image', $param ) && $param['image'] && $param['image'] != 'null' ){
      if( array_key_exists( 'profile', $param ) ){
        $thumbImg = '/cgmlImg/'.$param['image'].'/'.$param['profile'].'/'.$param['image'];
      }else{
        $thumbImg = '/cgmlImg/'.$param['image'];
      }
    }else{
      if( array_key_exists( 'url', $param ) ){
        $isYoutubeID = $this->ytVidId( $param['url'] );
        if(!$isYoutubeID){
          $imgDefault = true;
        }else{
          $thumbImg = 'http://img.youtube.com/vi/'.$isYoutubeID.'/0.jpg';
        }
      }else{
        $imgDefault = true;
      }
    }

    if( $imgDefault ){
      $thumbImg = '/media/module/geozzy/img/default-multimedia.png';
    }

    return $thumbImg;
  }

  public function ytVidId( $url ) {
    $p = '#^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$#';
    return (preg_match($p, $url, $coincidencias)) ? $coincidencias[1] : false;
  }

  // Carga los datos de todas las colecciones de recursos asociadas al recurso dado
  public function getCollectionBlockInfo( $resId ){
    $resourceCollectionsAllModel =  new ResourceCollectionsAllModel();
    $collectionResources = '';
    if( isset( $resId ) ) {
      $resCollectionList = $resourceCollectionsAllModel->listItems(
        array(
          'filters' => array( 'resourceMain' => $resId),
          'order' => array( 'weightMain' => 1, 'weightSon' => 1 ),
          'affectsDependences' => array( 'ResourceModel', 'RExtUrlModel', 'UrlAlias')
        )
      );

      while ( $collection = $resCollectionList->fetch() )
      {
        $collectionResources[$collection->getter('id')]['col'] = array('id' => $collection->getter('id'),
        'title' => $collection->getter('title_'.$this->actLang),
        'shortDescription' => $collection->getter('shortDescription_'.$this->actLang),
        'image' => $collection->getter('image'),
        'multimedia' => $collection->getter('multimedia'));
        $collectionResourcesFirst[$collection->getter('id')]['col'] = $collectionResources[$collection->getter('id')]['col'];


        $resources = $collection->getterDependence( 'resourceSon', 'ResourceModel');
        if ($collection->getter('multimedia')){
          if ($resources){
            foreach( $resources as $resVal ) {
              $thumbSettings = array(
               'image' => $resVal->getter( 'image' ),
               'profile' => 'imgMultimediaGallery'
              );
              $resDataExtArray = $resVal->getterDependence('id', 'RExtUrlModel');
              $multimediaUrl = false;
              if( $resDataExt = $resDataExtArray[0]){
                $thumbSettings['url'] = $resDataExt->getter('url');
                $termsGroupedIdName = $this->getTermsInfoByGroupIdName($resVal->getter('id'));
                $urlContentType = array_shift($termsGroupedIdName['urlContentType']);
                if ($urlContentType['idNameTaxgroup'] === "urlContentType"){
                  $multimediaUrl = $this->ytVidId($resDataExt->getter('url'));
                }
              }
              $imgUrl = $this->getResourceThumbnail( $thumbSettings );
              $thumbSettings['profile'] = 'hdpi4';
              $imgUrl2 = $this->getResourceThumbnail( $thumbSettings );

              $urlAlias = $this->getUrlAlias($resVal->getter('id'));

              $collectionResources[$collection->getter('id')]['res'][$resVal->getter('id')] =
                array('rType' => $resVal->getter('rTypeId'), 'title' => $resVal->getter('title_'.$this->actLang),
                      'shortDescription' => $resVal->getter('shortDescription_'.$this->actLang),
                      'multimediaUrl' => $multimediaUrl, 'image' => $imgUrl, 'image_big' => $imgUrl2, 'urlAlias' => $urlAlias);
            }
          }
        }
        else{
          if( $resources ) {
            foreach( $resources as $resVal ) {
              $thumbSettings = array(
               'image' => $resVal->getter( 'image' ),
               'profile' => 'fast_cut'
              );
              $resDataExtArray = $resVal->getterDependence('id', 'RExtUrlModel');
              if( $resDataExt = $resDataExtArray[0] ) {
                $thumbSettings['url'] = $resDataExt->getter('url');
              }
              $imgUrl = $this->getResourceThumbnail( $thumbSettings );

              $urlAlias = $this->getUrlAlias($resVal->getter('id'));

              $collectionResources[$collection->getter('id')]['res'][$resVal->getter('id')] = array(
                'rType' => $resVal->getter('rTypeId'),
                'title' => $resVal->getter('title_'.$this->actLang),
                'shortDescription' => $resVal->getter('shortDescription_'.$this->actLang),
                'image' => $imgUrl, 'urlAlias' => $urlAlias
              );
            }
          }
        }
      }
    }
    return($collectionResources);
  }

  // Itera sobre el array de colecciones y devuelve un bloque creado con cada una, dependiendo de si son o no multimedia
  public function goOverCollections( array $collections, $multimedia ) {
    $collectionBlock = array();
    foreach( $collections as $idCollection => $collection ) {
      if ($multimedia){
        $collectionBlock[$idCollection] = $this->getMultimediaBlock($collection);
      }
      else{
        $collectionBlock[$idCollection] = $this->getCollectionBlock($collection);
      }
    }
    return $collectionBlock;
  }

  // Obtiene un bloque de una colección no multimedia dada
  public function getCollectionBlock( $collection ) {

    // echo '<pre>';
    // print_r($collection);
    // echo '</pre>';

    $template = new Template();
    $template->assign( 'id', $collection['col']['id'] );
    $template->assign( 'collectionResources', $collection );
    $template->setTpl( 'resourceCollectionViewBlock.tpl', 'geozzy' );
    return $template;
  }


  // Obtiene un bloque de una colección multimedia dada
  public function getMultimediaBlock( $multimedia ) {
    $template = new Template();
    $template->assign( 'id', $multimedia['col']['id'] );
    $template->assign( 'max', 6 );
    $template->assign( 'multimediaAll', $multimedia );
    $template->setTpl( 'resourceMultimediaViewBlock.tpl', 'geozzy' );
    return $template;
  }

  // Obtiene la url del recurso en el idioma especificado y sino, en el idioma actual
  public function getUrlAlias( $resId, $lang = false ) {
    $urlAliasModel = new UrlAliasModel();

    if ($lang){
      $langId = $lang;
    }
    else{
      $langId = $this->actLang;
    }
    $urlAlias = false;
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'resource' => $resId, 'lang' => $langId ) ) )->fetch();
    if ($urlAliasList){
      $urlAlias = $langId.$urlAliasList->getter('urlFrom');
    }
    return $urlAlias;
  }

}
