<?php
geozzy::load( 'controller/RTypeController.php' );
geozzy::load( 'controller/RExtController.php' );

/**
METODOS A CAMBIAR/ELIMINAR
loadResourceObject
getResourceData: Controlar ben translate e cargar a maioria dos datos
**/


class ResourceController {

  public $rTypeCtrl = null;
  public $rTypeIdName = null;
  public $resObj = null;
  public $resData = null;
  private $taxonomyAll = null;
  private $collectionsAll = [];
  public $actLang = null;
  public $defLang = null;
  public $allLang = null;

  public function __construct( $resId = false ) {
    // error_log( 'ResourceController::__construct' );

    common::autoIncludes();
    form::autoIncludes();
    //user::autoIncludes();
    user::load('controller/UserAccessController.php');
    filedata::autoIncludes();

    global $C_LANG; // Idioma actual, cogido de la url
    $this->actLang = $C_LANG;
    $this->defLang = Cogumelo::getSetupValue( 'lang:default' );
    $this->allLang = Cogumelo::getSetupValue( 'lang:available' );

    if( $resId ) {
      $this->loadResourceObject( $resId );
    }
  }

  /**
   *  Cargando controlador del RType
   */
  public function getRTypeCtrl( $rTypeId = false ) {
    // error_log( "ResourceController: getRTypeCtrl( $rTypeId )" );

    if( !$this->rTypeCtrl ) {
      $rTypeIdName = $this->getRTypeIdName( $rTypeId );
      if( class_exists( $rTypeIdName ) ) {
        // error_log( "ResourceController: getRTypeCtrl = $rTypeIdName" );
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
    // error_log( "ResourceController: getRTypeIdName( $rTypeId )" );
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
    if( $this->resObj === null || ( $resId && $resId !== $this->resObj->getter('id') ) ) {
      $resModel = new ResourceModel();
      $resList = $resModel->listItems( array( 'affectsDependences' =>
        array( 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ExtraDataModel' ),
        // array( 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ResourceTaxonomytermModel', 'ExtraDataModel' ),
        'filters' => array( 'id' => $resId, 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1 ) ) );
      $this->resObj = ( $resList ) ? $resList->fetch() : null;
    }

    return( $this->resObj !== null &&  $this->resObj !== false );
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

    if( (!$this->resData || ( $resId && $resId !== $this->resData['id'] ) ) && $this->loadResourceObject( $resId ) ) {
      $langDefault = Cogumelo::getSetupValue( 'lang:default' );

      $langsConf = Cogumelo::getSetupValue( 'lang:available' );
      if( is_array( $langsConf ) ) {
        $langAvailable = array_keys( $langsConf );
      }

      // Cargamos todos los campos "en bruto"
      $resourceData = $this->resObj->getAllData( 'onlydata' );

      // Añadimos los campos en el idioma actual o el idioma principal
      $resourceFields = $this->resObj->getCols();
      foreach( $resourceFields as $key => $value ) {
        if( !isset( $resourceData[ $key ] ) ) {
          $resourceData[ $key ] = $this->resObj->getter( $key );
          // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
          if( $resourceData[ $key ] === '' && isset( $resourceData[ $key.'_'.$langDefault ] ) ) {
            $resourceData[ $key ] = $resourceData[ $key.'_'.$langDefault ];
          }
        }
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
            if( $urlLang === $this->actLang ) {
              $resourceData['urlAlias'] = $resourceData[ 'urlAlias_'.$urlLang ];
              if( count( $this->allLang ) > 1 ) {
                $resourceData['urlAlias'] = '/'.$urlLang.$resourceData[ 'urlAlias' ];
              }
            }
          }
        }
      }
      if( !isset($resourceData['urlAlias']) ) {
        $resourceData['urlAlias'] = '/'.$this->actLang.'/'.
          Cogumelo::getSetupValue('mod:geozzy:resource:directUrl').'/'.
          $resourceData[ 'id' ].'#UAF';
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
  public function getBaseFormObj( $formName, $urlAction, $successArray = false, $valuesArray = false ) {
    // error_log( "ResourceController: getBaseFormObj()" );
    // error_log( "valuesArray: ".print_r( $valuesArray, true ) );

    $form = new FormController( $formName, $urlAction );

    if($successArray){
      foreach( $successArray as $tSuccess => $success ) {
        if($tSuccess == "redirect"){
          $cancelButton = $success;
        }
        $form->setSuccess( $tSuccess, $success );
      }
    }

    /* Establecemos la página a la que debe retornar */

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
        'params' => array( 'label' => __( 'Priority' ), 'type' => 'select', 'class' => 'gzzSelect2',
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
        'params' => array(
          'id' => 'resourceCollections', 'class' => 'cgmMForm-order resourceCollection',
          'label' => __( 'Collections' ), 'type' => 'select',
          'data-col-type' => 'base',
          'multiple' => true, 'options'=> $resCollections
        )
      ),
      'addCollections' => array(
        'params' => array(
          'id' => 'resourceAddCollection', 'class' => 'resourceAddCollection',
          'data-col-type' => 'base',
          'data-col-select' => 'resourceCollections',
          'data-col-title' => __('Create Collection'),
          'type' => 'button', 'value' => __( 'Add Collection' )
        )
      ),
      'multimediaGalleries' => array(
        'params' => array(
          'id' => 'resourceMultimediaGalleries', 'class' => 'cgmMForm-order resourceCollection',
          'label' => __( 'Galleries' ), 'type' => 'select',
          'data-col-type' => 'multimedia',
          'multiple' => true, 'options'=> $resMultimedia
        )
      ),
      'addMultimediaGalleries' => array(
        'params' => array(
          'id' => 'resourceAddMultimediaGallery', 'class' => 'resourceAddCollection',
          'data-col-type' => 'multimedia',
          'data-col-select' => 'resourceMultimediaGalleries',
          'data-col-title' => __('Create Multimedia Gallery'),
          'type' => 'button', 'value' => __( 'Add Multimedia Gallery' )
        )
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
      // error_log( 'ResourceController getFormObj: ' . print_r( $valuesArray, true ) );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => __( 'Send' ), 'class' => 'gzzAdminToMove' ) );
    if(isset($cancelButton) && $cancelButton){
      $form->setField( 'cancel', array( 'type' => 'button', 'value' => __( 'Cancel' ), 'class' => 'btn btn-warning gzzAdminToMove', 'data-href' => $cancelButton ) );
    }
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
  public function getFormObj( $formName, $urlAction, $successArray = false, $valuesArray = false ) {

    $form = $this->getBaseFormObj( $formName, $urlAction, $successArray, $valuesArray );

    if( $this->getRTypeCtrl( $form->getFieldValue( 'rTypeId' ) ) ) {
      $this->rTypeCtrl->manipulateForm( $form );
    }

    $form->saveToSession(); // Nos aseguramos de que el form se guarda en sesion

    return( $form );
  }

  /**
   * Creamos el formulario y preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Array $formBlockInfo{ 'template' => Array, 'data' => Array, 'ext' => Array, 'dataForm' => Array, objForm => Form }
   */
  public function getFormBlockInfo( $formName, $urlAction, $successArray = false, $valuesArray = false ) {
    $form = $this->getFormObj( $formName, $urlAction, $successArray, $valuesArray );

    $formBlockInfo = $this->rTypeCtrl->getFormBlockInfo( $form );
    $formBlockInfo['objForm'] = $form;

    return( $formBlockInfo );
  }

  /**
   * Se reconstruye el formulario con sus datos y se realizan las validaciones que contiene
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
   * Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( $form ) {
    $urlAliasFieldNames = $form->multilangFieldNames( 'urlAlias' );
    $fieldName = is_array( $urlAliasFieldNames ) ? $urlAliasFieldNames['0'] : $urlAliasFieldNames;
    if( $form->isFieldDefined( $fieldName ) ) {
      $this->evalFormUrlAlias( $form, 'urlAlias' );
    }
  }

  /**
   * Creación-Edición-Borrado de los elementos del recurso base e iniciar transaction
   */
  public function resFormProcess( $form ) {
    $this->resObj = false;

    if( !$form->existErrors() ) {
      $useraccesscontrol = new UserAccessController();
      $user = $useraccesscontrol->getSessiondata();

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) && is_numeric( $form->getFieldValue( 'id' ) ) ) {
        $valuesArray[ 'userUpdate' ] = $user['data']['id'];
        $valuesArray[ 'timeLastUpdate' ] = gmdate( "Y-m-d H:i:s", time() );
        unset( $valuesArray[ 'image' ] );
      }
      else {
        $valuesArray[ 'user' ] = $user['data']['id'];
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
      $this->resObj = new ResourceModel( $valuesArray );
      if( $this->resObj === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    if( !$form->existErrors()) {

      // TRANSACTION START
      $this->resObj->transactionStart();

      $saveResult = $this->resObj->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    /*
      DEPENDENCIAS / RELACIONES
    */
    if( !$form->existErrors() && $form->isFieldDefined( 'image' ) ) {
      $this->setFormFiledata( $form, 'image', 'image', $this->resObj );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'topics' ) ) {
      $this->setFormTopic( $form, 'topics', $this->resObj );
    }

    if( !$form->existErrors() && ( $form->isFieldDefined( 'collections' ) || $form->isFieldDefined( 'multimediaGalleries' ) ) ) {
      $this->setFormCollection( $form, $this->resObj );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'starred' ) ) {
      $this->setFormTax( $form, 'starred', 'starred', $form->getFieldValue( 'starred' ), $this->resObj );
    }

    if( !$form->existErrors() ) {
      $this->setFormUrlAlias( $form, 'urlAlias', $this->resObj );
    }
    /*
      DEPENDENCIAS (END)
    */
    if( !$form->existErrors()) {
      $saveResult = $this->resObj->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    return $this->resObj;
  }

  /**
   * Enviamos el OK-ERROR a la BBDD y al formulario y finalizar transaction
   */
  public function resFormSuccess( $form, $resource ) {
    if( !$form->existErrors() ) {
      // TRANSACTION COMMIT
      $resource->transactionCommit();
    }
    else {
      // TRANSACTION ROLLBACK
      if( $resource ) {
        $resource->transactionRollback();
      }
      // $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
    }

    $form->sendJsonResponse();
  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */


  /**
   * Filedata methods
   */
  public function setFormFiledata( $form, $fieldName, $colName, $resObj ) {
    $fileField = $form->getFieldValue( $fieldName );
    // error_log( 'setFormFiledata fileInfo: '. print_r( $fileField, true ) );
    $fileFieldValues = false;
    $error = false;

    $filedataCtrl = new FiledataController();
    $newFiledataObj = false;

    if( isset( $fileField['status'] ) ) {

      // error_log( 'To Model - fileInfo: '. print_r( $fileField[ 'values' ], true ) );
      // error_log( 'To Model - status: '.$fileField['status'] );
      // error_log( '========' );error_log( '========' );error_log( '========' );error_log( '========' );

      switch( $fileField['status'] ) {
        case 'LOADED':
          $fileFieldValues = $fileField['values'];
          $newFiledataObj = $filedataCtrl->createNewFile( $fileFieldValues );
          // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
          if( $newFiledataObj ) {
            $resObj->setter( $colName, $newFiledataObj->getter( 'id' ) );
          }
          break;
        case 'REPLACE':
          // error_log( 'To Model - fileInfoPrev: '. print_r( $fileField[ 'prev' ], true ) );
          $fileFieldValues = $fileField['values'];
          $prevFiledataId = $resObj->getter( $colName );
          $newFiledataObj = $filedataCtrl->createNewFile( $fileFieldValues );
          // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
          if( $newFiledataObj ) {
            $resObj->setter( $colName, $newFiledataObj->getter( 'id' ) );
            // error_log( 'To Model - deleteFile ID: '.$prevFiledataId );
            $filedataCtrl->deleteFile( $prevFiledataId );
          }
          break;
        case 'DELETE':
          if( $prevFiledataId = $resObj->getter( $colName ) ) {
            // error_log( 'To Model - prevFiledataId: '.$prevFiledataId );
            $filedataCtrl->deleteFile( $prevFiledataId );
            $resObj->setter( $colName, null );
          }
          break;
        case 'EXIST':
          $fileFieldValues = $fileField[ 'values' ];
          if( $prevFiledataId = $resObj->getter( $colName ) ) {
            // error_log( 'To Model - UPDATE prevFiledataId: '.$prevFiledataId );
            $filedataCtrl->updateInfo( $prevFiledataId, $fileFieldValues );
          }
          break;
        default:
          // error_log( 'To Model: DEFAULT='.$fileField['status'] );
          break;
      }

      /*
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
      */
    }

    if( $error ) {
      $form->addFieldRuleError( $fieldName, false, $error );
    }
  }


  /**
   * ExtraData methods
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
   * Taxonomy/Topic methods
   */
  public function getOptionsTopic() {
    $topics = array();
    $topicModel =  new TopicModel();
    $topicList = $topicModel->listItems();
    while( $topic = $topicList->fetch() ){
      $topics[ $topic->getter( 'id' ) ] = $topic->getter( 'name', Cogumelo::getSetupValue( 'lang:default' ) );
    }

    return $topics;
  }

  public function getOptionsTax( $taxIdName ) {
    $options = array();
    $taxTermModel =  new TaxonomyTermModel();
    $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $taxIdName ), 'order' => array('weight' => 1),
      'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );
    while( $taxTerm = $taxTermList->fetch() ){
      $options[ $taxTerm->getter( 'id' ) ] = $taxTerm->getter( 'name', Cogumelo::getSetupValue( 'lang:default' ) );
    }

    return $options;
  }
  public function getOptionsTaxAdvancedArray( $taxIdName ) {
    $options = array();
    $taxTermModel =  new TaxonomyTermModel();
    $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'TaxonomygroupModel.idName' => $taxIdName ), 'order' => array('weight' => 1),
      'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );
    while( $taxTerm = $taxTermList->fetch() ){
      $options[ $taxTerm->getter( 'id' ) ] = array(
        'text' => $taxTerm->getter( 'name', Cogumelo::getSetupValue( 'lang:default' )),
        'data-term-icon' => $taxTerm->getter('icon'),
        'value' => $taxTerm->getter( 'id' )
      );
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





  /**
   * Devuelve las collections asociadas al recurso dado o al actual agrupadas por tipo
   */
  public function getCollectionsAll( $resId = false ) {
    $collsInfo = false;

    if( !$resId && gettype( $this->resObj ) === 'object' ) {
      $resId = $this->resObj->getter('id');
    }
    // error_log( "getCollectionsAll( $resId )" );

    if( $resId ) {
      if( isset( $this->collectionsAll[ $resId ] ) ) {
        $collsInfo = $this->collectionsAll[ $resId ];
      }
      else {
        $collResModel = new CollectionResourcesListViewModel();
        $collResList = $collResModel->listItems( array(
          'filters' => array( 'resourceMain' => $resId ),
          'order' => array( 'weight' => 1, 'weightMain' => 1 )
        ));
        if( $collResList ) {
          $collsInfo = [];
          while( $collResObj = $collResList->fetch() ) {
            $colId = $collResObj->getter('id');
            $colType = $collResObj->getter('collectionType');
            $collsInfo[ $colType ][ $colId ] = $collResObj->getAllData( 'onlydata' );
          }
        }

        $this->collectionsAll[ $resId ] = $collsInfo;
      }
      // error_log( "getCollectionsAll( $resId ): ".print_r( $collsInfo, true ) );
    }

    return $collsInfo;
  }

  public function getCollectionsSelect( $collsInfo ) {
    // error_log( "ResourceController: getCollectionsInfo( $resId )" );
    $collsSelect = array(
      'options' => array(),
      'values' => array()
    );

    $langDefault = Cogumelo::getSetupValue( 'lang:default' );
    foreach( $collsInfo as $collId => $collInfo ) {
        $collsSelect[ 'options' ][ $collId ] = $collInfo[ 'title_'.$langDefault ];
        $collsSelect[ 'values' ][] = $collId;
    }

    // error_log( "ResourceController: getCollectionsSelect = ". print_r( $collsSelect, true ) );
    return $collsSelect;
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
          'filters' => array( 'resource' => $resId, 'CollectionModel.collectionType' => 'base' ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'CollectionModel' )
        )
      );

      while( $res = $resCollectionList->fetch() ){
        $collections = $res->getterDependence( 'collection', 'CollectionModel' );

        if( $collections ){
          $colInfo[ 'options' ][ $res->getter( 'collection' ) ] = $collections[ 0 ]->getter( 'title', Cogumelo::getSetupValue( 'lang:default' ) );
          $colInfo[ 'values' ][] = $res->getter( 'collection' );
        }
      }
    }

    // error_log( "ResourceController: getCollectionsInfo = ". print_r( $colInfo, true ) );
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
          'filters' => array( 'resource' => $resId, 'CollectionModel.collectionType' => 'multimedia' ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'CollectionModel' )
        )
      );

      while( $res = $resMultimediaList->fetch() ){
        $multimediaGalleries = $res->getterDependence( 'collection', 'CollectionModel' );
        if( $multimediaGalleries ){
          $multimediaInfo[ 'options' ][ $res->getter( 'collection' ) ] = $multimediaGalleries[ 0 ]->getter( 'title', Cogumelo::getSetupValue( 'lang:default' ) );
          $multimediaInfo[ 'values' ][] = $res->getter( 'collection' );
        }
      }
    }

    // error_log( "ResourceController: getMultimediaInfo = ". print_r( $colInfo, true ) );
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



  public function saveFormCollectionField( $form, $baseObj, $fieldName ) {
    // SOLO procesamos collections del tipo indicado en $fieldName

    $baseId = $baseObj->getter( 'id' );

    $formCollValues = $form->getFieldValue( $fieldName );
    if( !is_array( $formCollValues ) ) {
      $formCollValues = ( is_numeric( $formCollValues ) ) ? array( $formCollValues ) : array();
    }

    $formCollType = $form->getFieldParam( $fieldName, 'data-col-type' );

    // error_log( 'saveFormCollectionField( form, '.$baseId.', '.$fieldName.' ) tipo '.$formCollType );

    $relPrevInfo = false;
    // Si estamos editando, repasamos y borramos relaciones sobrantes
    if( $baseId ) {
      $relModel = new ResourceCollectionsModel();
      $relPrevList = $relModel->listItems( array( 'filters' => array( 'resource' => $baseId ) ) );
      if( $relPrevList ) {
        // estaban asignados antes
        $relPrevInfo = array();
        $colModel = new CollectionModel();
        while( $relPrev = $relPrevList->fetch() ) {
          $colList = $colModel->listItems( array( 'filters' => array( 'id' => $relPrev->getter('collection') ) ) );
          $collection = ( $colList ) ? $colList->fetch() : false;
          if( $collection && $collection->getter('collectionType') === $formCollType ) {
            $relPrevInfo[ $relPrev->getter( 'collection' ) ] = $relPrev->getter( 'id' );
            if( !in_array( $relPrev->getter( 'collection' ), $formCollValues ) ) {
              // desasignar
              $relPrev->delete();
            }
          }
        }
      }
    }

    // Creamos-Editamos todas las relaciones
    if( count( $formCollValues ) > 0 ) {
      $weight = 0;
      foreach( $formCollValues as $value ) {
        $weight++;
        $info = array( 'resource' => $baseId, 'collection' => $value, 'weight' => $weight );
        if( $relPrevInfo !== false && isset( $relPrevInfo[ $value ] ) ) { // Update
          $info[ 'id' ] = $relPrevInfo[ $value ];
        }
        $relObj = new ResourceCollectionsModel( $info );
        if( !$relObj->save() ) {
          $form->addFieldRuleError( $fieldName, false, __( 'Error setting collection values' ) );
          break;
        }
      }
    }
  }

  private function setFormCollection( $form, $baseObj ) {
    // SOLO procesamos collections de tipo "base" o "multimedia"

    $this->saveFormCollectionField( $form, $baseObj, 'collections' );
    $this->saveFormCollectionField( $form, $baseObj, 'multimediaGalleries' );
  }


  /**
   * UrlAlias methods
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
    // "Aplanamos" caracteres no ASCII7
    $url = str_replace( $this->urlTranslate[ 'from' ], $this->urlTranslate[ 'to' ], $url );
    // Solo admintimos a-z A-Z 0-9 - / El resto pasan a ser -
    $url = preg_replace( '/[^a-z0-9\-\/]/i', '-', $url );
    // Eliminamos - sobrantes
    $url = preg_replace( '/--+/', '-', $url );
    $url = preg_replace( '/-*\/-*/', '/', $url );
    $url = trim( $url, '-' );
    // Por si ha quedado algo, pasamos el validador de PHP
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

  public function getUrlByPattern( $resId, $langId = false, $title = false ) {
    // error_log( "getUrlByPattern( $resId, $langId )" );
    $urlAlias = '/'. Cogumelo::getSetupValue( 'mod:geozzy:resource:directUrl' ) .'/'.$resId;

    $pattern = '/';

    $patterns = Cogumelo::getSetupValue( 'mod:geozzy:resource:urlAliasPatterns' );
    if( $patterns ) {
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

    if( !$title ) {
      if( $resData = $this->getResourceData( $resId ) ) {
        if( $langId ) {
          if( isset( $resData[ 'title_'.$langId ] ) && $resData[ 'title_'.$langId ] !== '' ) {
            $title = strtolower( $resData[ 'title_'.$langId ] );
          }
          else {
            if( isset( $resData[ 'title_'.$this->defLang ] ) && $resData[ 'title_'.$this->defLang ] !== '' ) {
              $title = strtolower( $resData[ 'title_'.$this->defLang ] );
            }
          }
        }
        else {
          if( isset( $resData['title'] ) && $resData['title'] !== '' ) {
            $title = strtolower( $resData['title'] );
          }
          else {
            if( isset( $resData[ 'title_'.$this->defLang ] ) && $resData[ 'title_'.$this->defLang ] !== '' ) {
              $title = strtolower( $resData[ 'title_'.$this->defLang ] );
            }
          }
        }
      }
    }

    $urlAlias = ( $title ) ? $pattern.$title : $pattern.$resId;

    return $this->sanitizeUrl( $urlAlias );
  }


  public function setUrl( $resId, $langId = false, $urlAlias = false ) {
    // error_log( "setUrl( $resId, $langId, $urlAlias )" );
    $result = true;

    if( !isset( $urlAlias ) || $urlAlias === false || $urlAlias === '' ) {
      $urlAlias = $this->getUrlByPattern( $resId, $langId );
      //$urlAlias = '/'.Cogumelo::getSetupValue('mod:geozzy:resource:directUrl').'/'.$resId;
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
   * Get resource URL
   *
   * @param $resId integer|string Id o IdName del recurso
   * @param $lang string Idioma
   *
   * @return string
   */
  public function getUrlAlias( $resId, $lang = false ) {
    $urlAlias = false;

    global $C_LANG; // Idioma actual, cogido de la url
    if( !$lang ) {
      $lang = $C_LANG;
    }

    $filters = array( 'lang' => $lang );

    if( is_numeric( $resId ) ) {
      $filters['resource'] = $resId;
    }
    else {
      $filters['resourceIdName'] = $resId;
    }

    $urlModel = new UrlAliasResourceViewModel();
    $urlList = $urlModel->listItems( array( 'filters' => $filters ));

    $urlObj = ( $urlList ) ? $urlList->fetch() : false;

    if( $urlObj ) {
      $urlAlias = '/'.$lang.$urlObj->getter('urlFrom');
    }
    else {
      $urlAlias = '/'.$lang.'/'.Cogumelo::getSetupValue( 'mod:geozzy:resource:directUrl' ).'/'.$resId;
    }

    return $urlAlias;
  }



  /**
   * Datos y template por defecto del ResourceBlock
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "ResourceController: getViewBlockInfo()" );

    $viewBlockInfo = array(
      'template' => false,
      'data' => false,
      'ext' => array()
    );

    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    if( $this->loadResourceObject( $resId ) ) {
      if( $this->resObj->getter( 'published' ) || $user ) {
        $viewBlockInfo['data'] = $this->getResourceData( $resId, true );
        if( $this->getRTypeCtrl() ) {
          $viewBlockInfo = $this->rTypeCtrl->getViewBlockInfo( );
        }

        if( !$this->resObj->getter( 'published' ) ) {
          // Recurso NO publicado
          $viewBlockInfo = array(
            'unpublished' => $viewBlockInfo
          );
        }
      }
    }

    return( $viewBlockInfo );
  } // function getViewBlockInfo()





  /**
   * Devuelve resData con los campos traducibles en el idioma actual
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

  public function getResourceThumbnail( $param ) {
    // error_log( 'getResourceThumbnail :'.print_r($param,true));
    $thumbImg = Cogumelo::getSetupValue('publicConf:vars:media').'/module/geozzy/img/default-multimedia.png';

    if( array_key_exists( 'imageId', $param ) && $param['imageId'] && $param['imageId'] !== 'null' ) {
      if( !isset( $param['imageName'] ) ) {
        $param['imageName'] = $param['imageId'].'.jpg';
      }
      $prof = array_key_exists( 'profile', $param ) ? $param['profile'].'/' : '';
      $thumbImg = Cogumelo::getSetupValue('publicConf:vars:mediaHost').'cgmlImg/'.$param['imageId'].
        '/'.$prof.$param['imageName'];
    }
    else {
      if( array_key_exists( 'url', $param ) ){
        $isYoutubeID = $this->ytVidId( $param['url'] );
        if( $isYoutubeID ) {
          $thumbImg = 'http://img.youtube.com/vi/'.$isYoutubeID.'/0.jpg';
        }
      }
    }

    return $thumbImg;
  }

  public function ytVidId( $url ) {
    $p = '#^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$#';
    return (preg_match($p, $url, $coincidencias)) ? $coincidencias[1] : false;
  }

  // Carga los datos de todas las colecciones de recursos asociadas al recurso dado
  public function getCollectionBlockInfo( $resId, $collectionTypes = false ) {
    $collectionResources = false;

    if( $collectionTypes === false ) {
      $collTypesFilter = [ 'base', 'multimedia' ];
    }
    else {
      $collTypesFilter = is_array( $collectionTypes ) ? $collectionTypes : [ $collectionTypes ];
    }

    $resourceCollectionsAllModel =  new ResourceCollectionsAllModel();
    if( isset( $resId ) ) {
      $resCollectionList = $resourceCollectionsAllModel->listItems(
        array(
          'filters' => array( 'resourceMain' => $resId, 'collectionTypeIn' => $collTypesFilter ),
          'order' => array( 'weightMain' => 1, 'weightSon' => 1 ),
          'affectsDependences' => array( 'ResourceModel', 'RExtUrlModel', 'UrlAlias' )
        )
      );
      while( $collection = $resCollectionList->fetch() ) {
        $collId = $collection->getter('id');
        if( !isset( $collectionResources[ $collId ] ) ) {
          $collectionResources[ $collId ]['col'] = array(
            'id' => $collId,
            'title' => $collection->getter('title'),
            'shortDescription' => $collection->getter('shortDescription'),
            'description' => $collection->getter('description'),
            'image' => $collection->getter('image'),
            'collectionType' => $collection->getter('collectionType')
          );
          $collectionResources[ $collId ]['res'] = [];
        }
        $resources = $collection->getterDependence( 'resourceSon', 'ResourceModel');
        if( $resources && is_array($resources) && count($resources) > 0 ) {
          switch( $collection->getter('collectionType') ) {
            case 'multimedia':
              foreach( $resources as $resVal ) {
                // Saltamos recursos no publicados
                if( !$resVal->getter( 'published' ) ) {
                  continue;
                }

                $thumbSettings = array(
                  'profile' => 'imgMultimediaGallery'
                );

                $imgId = $resVal->getter( 'image' );

                if( $imgId && $imgId !== 'null' ) {
                  $thumbSettings['imageId'] = $imgId;
                  $thumbSettings['imageName'] = $imgId.'.jpg';
                }

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

                $urlAlias = $this->getUrlAlias( $resVal->getter('id') );
                $collectionResources[ $collId ]['res'][ $resVal->getter('id') ] = array(
                  'id' => $resVal->getter('id'),
                  'rType' => $resVal->getter('rTypeId'),
                  'title' => $resVal->getter('title'),
                  'shortDescription' => $resVal->getter('shortDescription'),
                  'mediumDescription' => $resVal->getter('mediumDescription'),
                  'externalUrl' => $resVal->getter('externalUrl'),
                  'urlAlias' => $urlAlias,
                  'imageId' => $resVal->getter( 'image' ), // TODO: Deberia ser image
                  'image' => $imgUrl, // TODO: CAMBIAR!!! Sobreescribe un campo existente y necesario
                  'imageUrl' => $imgUrl, // Entrada nueva con una URL para "image"
                  'image_big' => $imgUrl2,
                  'multimediaUrl' => $multimediaUrl,
                );
              }
              break;
            case 'base':
            default:
              foreach( $resources as $resVal ) {
                // Saltamos recursos no publicados
                if( !$resVal->getter( 'published' ) ) {
                  continue;
                }

                $thumbSettings = array(
                  'imageId' => $resVal->getter( 'image' ),
                  'imageName' => $resVal->getter( 'image' ).'.jpg',
                  'profile' => 'fast_cut'
                );
                $resDataExtArray = $resVal->getterDependence('id', 'RExtUrlModel');
                if( $resDataExt = $resDataExtArray[0] ) {
                  $thumbSettings['url'] = $resDataExt->getter('url');
                }
                $imgUrl = $this->getResourceThumbnail( $thumbSettings );

                $urlAlias = $this->getUrlAlias( $resVal->getter('id') );

                $collectionResources[ $collId ]['res'][ $resVal->getter('id') ] = array(
                  'id' => $resVal->getter('id'),
                  'rType' => $resVal->getter('rTypeId'),
                  'title' => $resVal->getter('title'),
                  'shortDescription' => $resVal->getter('shortDescription'),
                  'mediumDescription' => $resVal->getter('mediumDescription'),
                  'externalUrl' => $resVal->getter('externalUrl'),
                  'urlAlias' => $urlAlias,
                  'imageId' => $resVal->getter( 'image' ), // TODO: Deberia ser image
                  'image' => $imgUrl, // TODO: CAMBIAR!!! Sobreescribe un campo existente y necesario
                  'imageUrl' => $imgUrl, // Entrada nueva con una URL para "image"
                );
              }
              break;
          } // switch
        } // if
      }
    }

    return($collectionResources);
  }

  // Itera sobre el array de colecciones y devuelve un bloque creado con cada una, dependiendo de si son o no multimedia
  public function goOverCollections( array $collections, $collectionType = false ) {
    $collectionBlock = array();

    foreach( $collections as $idCollection => $collection ) {
      if( $collectionType && $collectionType !== $collection['col']['collectionType'] ) {
        continue;
      }
      $collectionBlock[ $idCollection ] = $this->getCollectionBlock( $collection );
    }

    return $collectionBlock;
  }

  // Obtiene un bloque de una colección no multimedia dada
  public function getCollectionBlock( $collection ) {

    $template = new Template();
    $template->assign( 'id', $collection['col']['id'] );

    switch( $colType = $collection['col']['collectionType'] ) {
      case 'multimedia':
        $template->assign( 'max', 6 );
        $template->assign( 'multimediaAll', $collection );
        $template->setTpl( 'resourceMultimediaViewBlock.tpl', 'geozzy' );
        break;
      case 'base':
      default;
        $template->assign( 'collectionResources', $collection );
        $template->setTpl( 'resourceCollectionViewBlock.tpl', 'geozzy' );
        break;
    }

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

}
