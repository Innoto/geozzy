<?php
geozzy::load( 'controller/RTypeController.php' );
geozzy::load( 'controller/RExtController.php' );
cogumelo::load('coreController/Cache.php');



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

  public $cacheQuery = true; // false, true or time in seconds

  public function __construct( $resId = false ) {
    // error_log(__METHOD__);

    common::autoIncludes();
    form::autoIncludes();
    //user::autoIncludes();
    user::load('controller/UserAccessController.php');
    filedata::autoIncludes();

    global $C_LANG; // Idioma actual, cogido de la url
    $this->actLang = $C_LANG;
    $this->defLang = Cogumelo::getSetupValue( 'lang:default' );
    $this->allLang = Cogumelo::getSetupValue( 'lang:available' );

    $cache = Cogumelo::getSetupValue('cache:ResourceController:default');
    if( $cache !== null && !is_array( $cache ) ) {
      $this->cacheQuery = $cache;
    }

    if( $resId ) {
      $this->loadResourceObject( $resId );
    }
  }

  /**
   *  Cargando controlador del RType
   */
  public function getRTypeCtrl( $rType ) {
    // error_log( __METHOD__.' '.json_encode($rType) );

    $rTypeCtrl = null;


    $rTypeIdName = is_numeric( $rType ) ? $this->getRTypeIdName( $rType ) : $rType;

    if( !empty($this->rTypeCtrl) && $this->rTypeCtrl->rTypeName === $rTypeIdName ) {
      $rTypeCtrl = $this->rTypeCtrl;
    }

    if( empty($rTypeCtrl) ) {
      if( class_exists( $rTypeIdName ) ) {
        // error_log( __METHOD__.' rTypeIdName: '.$rTypeIdName );

        $rTypeIdName::autoIncludes();

        $rTypeCtrlClassName = 'RT'.mb_strcut( $rTypeIdName, 2 ).'Controller';
        $rTypeIdName::load( 'controller/'.$rTypeCtrlClassName.'.php' );
        $rTypeCtrl = new $rTypeCtrlClassName( $this );
      }
    }

    if( empty($this->rTypeCtrl) && !empty($rTypeCtrl) ) {
      $this->rTypeCtrl = $rTypeCtrl;
    }

    return $rTypeCtrl;
  }


  /**
   *  Cargando View del RType
   */
  public function getRTypeView( $rTypeId = false ) {
    // error_log( __CLASS__.": getRTypeView( $rTypeId )" );
    $rTypeView = null;

    $rTypeIdName = $this->getRTypeIdName( $rTypeId );
    if( class_exists( $rTypeIdName ) ) {
      // error_log( __CLASS__.": getRTypeView = $rTypeIdName" );

      $rTypeIdName::autoIncludes();

      $rTypeViewClassName = 'RT'.mb_strcut( $rTypeIdName, 2 ).'View';
      $rTypeIdName::load( 'view/'.$rTypeViewClassName.'.php' );
      $rTypeView = new $rTypeViewClassName( $this );
    }

    return $rTypeView;
  }


  /**
   * Load resource object
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function loadResourceObject( $resId = false ) {
    $resourceobj = null;

    if( !$this->resObj || ( $resId && $resId !== $this->resObj->getter('id') ) ) {
      $resModel = new ResourceModel();
      $resList = $resModel->listItems([
        'affectsDependences' => [ 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ExtraDataModel' ],
        'filters' => [ 'id' => $resId ],  /*, 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1 */
        'cache' => $this->cacheQuery,
      ]);
      $resourceobj = ( gettype( $resList ) === 'object' ) ? $resList->fetch() : null;

      if( $resourceobj && !$this->resObj ) {
        $this->resObj = $resourceobj;
      }
    }
    else {
      $resourceobj = $this->resObj;
    }

    return( $resourceobj );
  }


  /**
   * Load basic data values
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getResourceData( $resId = false ) {
    // error_log(__METHOD__);
    $resourceData = false;

    // if( (!$this->resData || ( $resId && $resId !== $this->resData['id'] ) ) && $resObj=$this->loadResourceObject( $resId ) ) {
    if( $resObj=$this->loadResourceObject( $resId ) ) {
      $langDefault = Cogumelo::getSetupValue( 'lang:default' );

      $langsConf = Cogumelo::getSetupValue( 'lang:available' );
      if( is_array( $langsConf ) ) {
        $langAvailable = array_keys( $langsConf );
      }

      // Cargamos todos los campos "en bruto"
      $resourceData = $resObj->getAllData( 'onlydata' );

      // Añadimos los campos en el idioma actual o el idioma principal
      $resourceFields = $resObj->getCols();
      foreach( $resourceFields as $key => $value ) {
        if( !isset( $resourceData[ $key ] ) ) {
          $resourceData[ $key ] = $resObj->getter( $key );
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
      $urlAliasDep = $resObj->getterDependence( 'id', 'UrlAliasModel' );
      if( $urlAliasDep !== false ) {
        foreach( $urlAliasDep as $urlAlias ) {
          $urlLang = $urlAlias->getter('lang');
          $urlFrom = $urlAlias->getter('urlFrom');

          // 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1
          if( empty( $urlAlias->getter('http') ) && !empty( $urlAlias->getter('canonical') ) && $urlLang ) {
            $resourceData[ 'urlAlias_'.$urlLang ] = $urlFrom;
            if( $urlLang === $this->actLang ) {
              $resourceData['urlAlias'] = $resourceData[ 'urlAlias_'.$urlLang ];
              if( count( $this->allLang ) > 1 ) {
                $resourceData['urlAlias'] = '/'.$urlLang.$resourceData['urlAlias'];
              }
            }
          }

          if( $urlAlias->getter('label') === 'adminAlias' ) {
            $resourceData[ 'urlAdminAlias_'.$urlLang ] = $urlFrom;
            if( $urlLang === $this->actLang ) {
              $resourceData['urlAdminAlias'] = $resourceData[ 'urlAdminAlias_'.$urlLang ];
              if( count( $this->allLang ) > 1 ) {
                $resourceData['urlAdminAlias'] = '/'.$urlLang.$resourceData['urlAdminAlias'];
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
      $fileDep = $resObj->getterDependence( 'image' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $resourceData[ 'image' ] = $this->getTranslatedData( $fileModel->getAllData( 'onlydata' ) );
        }
      }

      // Cargo los datos de temáticas con las que está asociado el recurso
      $topicsDep = $resObj->getterDependence( 'id', 'ResourceTopicModel');
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
          if( $t = $topicModel->listItems( [ 'filters' => [ 'id' => $topicId ], 'cache' => $this->cacheQuery ] )->fetch() ) {
            $resourceTopicList[$topicId] = $t->getter('name');
          }
        }
        $resourceData[ 'topicsName' ] = $resourceTopicList;
        /**
          TODO: Asegurarse de que os topics se cargan en orden
        */
      }

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $resourceData['taxonomies'] = $this->getTermsInfoByGroupIdName( $resourceData['id'] );
      if( $resourceData['taxonomies'] !== false ) {
        foreach( $resourceData['taxonomies'] as $idNameTaxgroup => $taxTermArray ) {
          $resourceData[ $idNameTaxgroup ] = $taxTermArray;
          // error_log( '$resourceData[ '.$idNameTaxgroup.' ] = : '.print_r( $taxTermArray, true ) );
        }
      }

      // Cargo los datos del campo batiburrillo
      $extraDataDep = $resObj->getterDependence( 'id', 'ExtraDataModel');
      if( $extraDataDep !== false ) {
        foreach( $extraDataDep as $extraData ) {
          foreach( $langAvailable as $lang ) {
            $resourceData[ $extraData->getter('name').'_'.$lang ] = $extraData->getter( 'value_'.$lang );
          }
        }
      }

      // Amplio la informacion de rType
      // $resourceData['rTypeIdName'] = $this->getRTypeIdName( $resourceData['rTypeId'] );
      $rTypeModel = new ResourcetypeModel();
      $rTypeList = $rTypeModel->listItems( [ 'filters' => [ 'id' => $resourceData['rTypeId'] ], 'cache' => $this->cacheQuery ] );
      if( $rTypeInfo = $rTypeList->fetch() ) {
        $resourceData['rTypeName'] = $rTypeInfo->getter( 'name' );
        $resourceData['rTypeIdName'] = $rTypeInfo->getter( 'idName' );
      }

      // if( $resourceData && !$this->resData ) {
      //   $this->resData = $resourceData;
      // }
    }
    // else {
    //   $resourceData = $this->resData;
    // }

    return $resourceData;
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
    // error_log(__METHOD__);
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


    if( empty( $valuesArray[ 'timeCreation' ] ) ) {
      $date = new DateTime( null, Cogumelo::getTimezoneSystem() );
      $date->setTimeZone( Cogumelo::getTimezoneDatabase() );
      $valuesArray[ 'timeCreation' ] = $date->format( 'Y-m-d H:i:s' );
    }


    $fieldsInfo = array(
      'rTypeId' => array(
        'params' => array( 'type' => 'reserved' )
      ),
      'rTypeIdName' => array(
        'params' => array( 'id' => 'rTypeIdName', 'type' => 'hidden' )
      ),
      'timeCreation' => array(
        'params' => array( 'label' => __( 'Time creation' ) )
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
        'params' => array( 'label' => __( 'Medium description' ), 'type' => 'textarea', 'htmlEditor' => 'true' ),
        'rules' => array( 'maxlength' => '1000' )
      ),
      'content' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Content' ), 'type' => 'textarea', 'htmlEditor' => 'true' ),
        'rules' => array( 'maxlength' => '32000' )
      ),
      'externalUrl' => array(
        'params' => array( 'label' => __( 'External URL' ) ),
        'rules' => array( 'maxlength' => '2000', 'url' => true )
      ),
      'image' => array(
        'params' => array( 'label' => __( 'Image' ), 'type' => 'file', 'id' => 'imgResource',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => ResourceModel::$cols['image']['uploadDir'] ),
        'rules' => array( 'minfilesize' => '1024', 'maxfilesize' => '4200000', 'accept' => 'image/jpeg,image/png' )
      ),
      'urlAlias' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'SEO: URL' ) ),
        'rules' => array( 'maxlength' => '2000' )
      ),
      'urlAdminAlias' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'SEO: URL Secundaria (Alias)' ) ),
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
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => __( 'Publicado' ) ))
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

    $htmlEditorBig = Cogumelo::getSetupValue('mod:geozzy:resource:htmlEditorBig');
    if( is_array($htmlEditorBig) ) {
      foreach( $htmlEditorBig as $bigEditorKey => $bigEditorFields ) {
        if( is_string( $bigEditorFields ) ) {
          // Nombra un campo. El indice no indica nada
          if( isset( $fieldsInfo[ $bigEditorFields ]['params']['htmlEditor'] ) ) {
            unset( $fieldsInfo[ $bigEditorFields ]['params']['htmlEditor'] );
            $fieldsInfo[ $bigEditorFields ]['params']['htmlEditorBig'] = true;
          }
        }
        else {
          // Array de campos. El indice indica el nombre del RType
          if( !empty( $valuesArray['rTypeIdName'] ) && $bigEditorKey === $valuesArray['rTypeIdName'] ) {
            error_log( __METHOD__.' htmlEditorBig rTypeIdName: '.$valuesArray['rTypeIdName'] );
            foreach( $bigEditorFields as $bigEditorField ) {
              if( isset( $fieldsInfo[ $bigEditorField ]['params']['htmlEditor'] ) ) {
                unset( $fieldsInfo[ $bigEditorField ]['params']['htmlEditor'] );
                $fieldsInfo[ $bigEditorField ]['params']['htmlEditorBig'] = true;
              }
            }
          }
        }
      }
    }

    $form->definitionsToForm( $fieldsInfo );

    // Valadaciones extra
    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );
    $form->removeValidationRule( 'collections', 'inArray' );
    $form->removeValidationRule( 'multimediaGalleries', 'inArray' );

    // Si es una edicion, añadimos el ID y cargamos los datos
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
      $form->setField( 'internalValuesArray', array( 'type' => 'reserved', 'value' => $valuesArray ));

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
      // error_log( __METHOD__.': ' . print_r( $valuesArray, true ) );
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
    // error_log( __METHOD__.': '. $formName );

    $form = $this->getBaseFormObj( $formName, $urlAction, $successArray, $valuesArray );

    $rType = $form->getFieldValue( 'rTypeIdName' ) | $form->getFieldValue( 'rTypeId' );
    if( $this->getRTypeCtrl( $rType ) ) {
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
    // error_log( __METHOD__.': '. $formName );

    $form = $this->getFormObj( $formName, $urlAction, $successArray, $valuesArray );



    if( $rTypeView = $this->getRTypeView( $form->getFieldValue( 'rTypeId' ) ) ) {
      $formBlockInfo = $rTypeView->getFormBlockInfo( $form );
    }
    // $formBlockInfo = $this->rTypeCtrl->getFormBlockInfo( $form );



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
      $form->addFormError( __('El servidor no considera válidos los datos recibidos.'), 'formError' );
    }

    if( !$form->existErrors() ) {
      if( !$form->processFileFields() ) {
        $form->addFormError( __('Ha sucedido un problema con los ficheros adjuntos. Puede que sea necesario subirlos otra vez.'), 'formError' );
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

    $urlAdminAliasFieldNames = $form->multilangFieldNames( 'urlAdminAlias' );
    $fieldName = is_array( $urlAliasFieldNames ) ? $urlAliasFieldNames['0'] : $urlAliasFieldNames;
    if( $form->isFieldDefined( $fieldName ) ) {
      $this->evalFormUrlAlias( $form, 'urlAdminAlias' );
    }

    if( $form->isFieldDefined( 'timeCreation' ) ) {
      $dt = $form->getFieldValue( 'timeCreation' );
      if( !empty( $dt ) && preg_match( '/^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{2}):(\d{2})$/', $dt ) !== 1 ) {
        $form->addFieldError( 'timeCreation', __('La fecha de creación no es válida') );
      }
    }
  }

  /**
   * Creación-Edición-Borrado de los elementos del recurso base e iniciar transaction
   */
  public function resFormProcess( $form ) {
    $this->resObj = null;

    if( !$form->existErrors() ) {
      $useraccesscontrol = new UserAccessController();
      $user = $useraccesscontrol->getSessiondata();

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) && is_numeric( $form->getFieldValue( 'id' ) ) ) {

        $date = new DateTime( null, Cogumelo::getTimezoneSystem() );
        $date->setTimeZone( Cogumelo::getTimezoneDatabase() );
        $valuesArray[ 'timeLastUpdate' ] = $date->format( 'Y-m-d H:i:s' );

        unset( $valuesArray[ 'image' ] );
        $valuesArray[ 'userUpdate' ] = $user['data']['id'];
      }
      else {
        $valuesArray[ 'user' ] = $user['data']['id'];
        if( empty( $valuesArray[ 'timeCreation' ] ) ) {
          $date = new DateTime( null, Cogumelo::getTimezoneSystem() );
          $date->setTimeZone( Cogumelo::getTimezoneDatabase() );
          $valuesArray[ 'timeCreation' ] = $date->format( 'Y-m-d H:i:s' );
        }
      }

      if( isset( $valuesArray[ 'timeCreation' ] ) ) {
        if( preg_match( '/^(\d{4})-(\d{1,2})-(\d{1,2}) (\d{1,2}):(\d{2}):(\d{2})$/', $valuesArray[ 'timeCreation' ] ) !== 1 ) {
          unset( $valuesArray[ 'timeCreation' ] );
        }
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
      if( $this->resObj === null ) {
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
     * DEPENDENCIAS / RELACIONES
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

    if( !$form->existErrors() ) {
      $this->setFormAdminUrlAlias( $form, 'urlAdminAlias', $this->resObj );
    }
    /*
     * DEPENDENCIAS (END)
     */

    if( !$form->existErrors() ) {
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
      $cacheCtrl = new Cache();
      $cacheCtrl->flush();
    }
    else {
      // TRANSACTION ROLLBACK
      if( $resource ) {
        $resource->transactionRollback();
      }
      // $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
    }
  }


  /**
   * Métodos para facilitar y organizar la verificación de los distintos elementos del recurso
   */


  /**
   *  Cargando IdName del RType
   */
  public function getRTypeIdName( $rTypeId = false, $resId = false ) {
    // error_log(__METHOD__.': '.$rTypeId );
    $rTypeIdName = false;

    if( $rTypeId === false ) {
      $resData = $this->getResourceData( $resId );
      $rTypeId = ( $resData ) ? $resData['rTypeId'] : false;
    }

    if( $rTypeId !== false ) {
      $rTypeModel = new ResourcetypeModel();
      $rTypeList = $rTypeModel->listItems( [ 'filters' => [ 'id' => $rTypeId ], 'cache' => $this->cacheQuery ] );
      if( $rTypeInfo = $rTypeList->fetch() ) {
        $rTypeIdName = $rTypeInfo->getter( 'idName' );
      }
    }

    return $rTypeIdName;
  }


  /**
   *  Cargando Id del RType IdName
   */
  public function getRTypeIdByIdName( $rTypeIdName ) {
    $rTypeId = false;

    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( [ 'filters' => [ 'idName' => $rTypeIdName ], 'cache' => $this->cacheQuery ] );
    if( gettype( $rTypeList ) === 'object' && ( $rTypeInfo = $rTypeList->fetch() ) ) {
      $rTypeId = $rTypeInfo->getter( 'id' );
    }

    return $rTypeId;
  }







  /**
   * Filedata methods
   */
  public function getFiledata( $fileIds ) {
    // error_log( __METHOD__.' : '.print_r( $fileIds, true ) );
    $result = false;

    $fileDataModel = new FiledataModel();
    if( !is_array($fileIds) ) {
      $fileDataList = $fileDataModel->listItems( [ 'filters' => [ 'id' => $fileIds ], 'cache' => $this->cacheQuery ] );
    }
    else {
      $fileDataList = $fileDataModel->listItems( [ 'filters' => [ 'idIn' => $fileIds ], 'cache' => $this->cacheQuery ] );
    }
    if( gettype($fileDataList) === 'object' ) {
      $result = [];
      $fileData = false;
      while( $fileObj = $fileDataList->fetch() ) {
        $fileData = $this->getTranslatedData( $fileObj->getAllData( 'onlydata' ) );
        $result[ $fileData['id'] ] = $fileData;
      }
      if( count( $result ) ) {
        if( !is_array($fileIds) ) {
          $result = $fileData;
        }
      }
      else {
        $result = false;
      }
    }

    return $result;
  }

  /* Actualización ficheros desde formulario */
  public function setFormFiledata( $form, $fieldName, $colName, $modelObj ) {

    $fileField = $form->getFieldValue( $fieldName );
    $filePrivateMode = $form->getFieldParam( $fieldName, 'privateMode' );
    $fileField['privateMode'] = $filePrivateMode;

    $fileRes = $this->setFiledataValues($fieldName, $fileField, $modelObj);
    return $fileRes;

  }

  /* Actualización ficheros sólo con objetos */
  public function setFiledataValues(  $fieldName, $fileField, $modelObj ) {
    $result = false;

    $filedataCtrl = new FiledataController();
    $newFiledataObj = false;

    if( isset( $fileField['status'] ) ) {

      // error_log( 'To Model - fileInfo: '. print_r( $fileField['values'], true ) );
      // error_log( 'To Model - status: '.$fileField['status'] );
      // error_log( '========' );

      switch( $fileField['status'] ) {
        case 'LOADED':
          if( $fileField['privateMode'] > 0 ) {
            $fileField['values']['privateMode'] = $fileField['privateMode'];
          }
          $newFiledataObj = $filedataCtrl->createNewFile( $fileField['values'] );
          // error_log( 'To Model - LOADED newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
          if( $newFiledataObj ) {
            $modelObj->setter( $fieldName, $newFiledataObj->getter( 'id' ) );
            $result = $newFiledataObj;
          }
          break;
        case 'REPLACE':
          // error_log( 'To Model - fileInfoPrev: '. print_r( $fileField['prev'], true ) );
          $prevFiledataId = $modelObj->getter( $fieldName );
          if( $fileField['privateMode'] > 0 ) {
            $fileField['values']['privateMode'] = $fileField['privateMode'];
          }
          $newFiledataObj = $filedataCtrl->createNewFile( $fileField['values'] );
          // error_log( 'To Model - REPLACE newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
          if( $newFiledataObj ) {
            $modelObj->setter( $fieldName, $newFiledataObj->getter( 'id' ) );
            // error_log( 'To Model - deleteFile ID: '.$prevFiledataId );
            $filedataCtrl->deleteFile( $prevFiledataId );
            $result = $newFiledataObj;
          }
          break;
        case 'DELETE':
          if( $prevFiledataId = $modelObj->getter( $fieldName ) ) {
            // error_log( 'To Model - DELETE prevFiledataId: '.$prevFiledataId );
            $filedataCtrl->deleteFile( $prevFiledataId );
            $modelObj->setter( $fieldName, null );
            $result = 'DELETE';
          }
          break;
        case 'EXIST':
          if( $prevFiledataId = $modelObj->getter( $fieldName ) ) {
            // error_log( 'To Model - EXIST-UPDATE prevFiledataId: '.$prevFiledataId );
            $filedataCtrl->updateInfo( $prevFiledataId, $fileField['values'] );
            $result = 'EXIST-UPDATE';
          }
          break;
        default:
          error_log( 'To Model: DEFAULT='.$fileField['status'] );
          break;
      }
    }


    return $result;
  }

  /**
   * Filegroup methods
   */
  public function getFilegroup( $idGroup ) {
    $result = false;

    $fileGroupModel = new FilegroupModel();
    $fileGroupList = $fileGroupModel->listItems( [ 'filters' => [ 'idGroup' => $idGroup ], 'cache' => $this->cacheQuery ] );

    if( gettype($fileGroupList) === 'object' ) {
      $fileGroupData = [];
      $fileGroupId = false;
      while( $fgObj = $fileGroupList->fetch() ) {
        $fileGroupId = $fgObj->getter('idGroup');
        $fileGroupData[ $fileGroupId ][] = $fgObj->getter('filedataId');
      }
      if( $fileGroupId && count( $fileGroupData[ $fileGroupId ] ) ) {
        $filesData = $this->getFiledata( $fileGroupData[ $fileGroupId ] );
        if( $filesData && count( $filesData ) ) {
          $result = [
            'idGroup' => $fileGroupId,
            'multiple' => $filesData
          ];
        }
      }
    }

    return $result;
  }

  public function setFormFilegroup( $form, $fieldName, $colName, $modelObj ) {
    $result = false;

    $error = false;
    $fileGroupField = $form->getFieldValue( $fieldName );
    $filePrivateMode = $form->getFieldParam( $fieldName, 'privateMode' );
    $fileGroupField['privateMode'] = $filePrivateMode;

    cogumelo::debug(__METHOD__.': '.$fieldName.' - '.$colName /*.' fileInfo: '. print_r( $fileGroupField, true )*/ );

    $filegroupRes = $this->setFilegroupValues($fieldName, $fileGroupField, $modelObj);

    if( !$filegroupRes ) {
      $form->addFieldRuleError( $fieldName, false, 'Se ha producido un error' );
    }

    return $filegroupRes;
  }

  public function setFilegroupValues( $fieldName, $fileGroupField, $modelObj ) {
    $result = false;

    $filedataCtrl = new FiledataController();
    $filegroupObj = false;

    if( !empty( $fileGroupField['multiple'] ) && is_array( $fileGroupField['multiple'] ) ) {

      $prevFilegroupId = $modelObj->getter( $fieldName );
      $filegroupId = ( $prevFilegroupId ) ? $prevFilegroupId : 0;

      foreach( $fileGroupField['multiple'] as $fileField ) {
        if( isset( $fileField['status'] ) ) {

          cogumelo::debug(__METHOD__.': To Model - status: '.$fileField['status'] );
          cogumelo::debug(__METHOD__.': ========' );

          switch( $fileField['status'] ) {
            case 'LOADED':
              if( $fileGroupField['privateMode'] > 0 ) {
                $fileField['values']['privateMode'] = $fileGroupField['privateMode'];
              }
              // $fileFieldValues = $fileField['values'];

              $newFilegroupObj = $filedataCtrl->saveToFileGroup( $fileField['values'], $filegroupId );
              cogumelo::debug(__METHOD__.': To Model SAVE: newFilegroupObj idGroup, filedataId: '.
                $newFilegroupObj->getter( 'idGroup' ).', '.$newFilegroupObj->getter( 'filedataId' ) );
              if( $newFilegroupObj ) {
                $result = $newFilegroupObj;
                if( !$filegroupId ) {
                  $filegroupId = $newFilegroupObj->getter('idGroup');
                  $modelObj->setter( $fieldName, $filegroupId );
                }
              }
              break;



            case 'DELETE':
              $deleteId = $fileField['values']['id'];

              $result = $filedataCtrl->deleteFromFileGroup( $deleteId, $filegroupId );
              cogumelo::debug(__METHOD__.': To Model Delete: '.json_encode($result) );

              break;



            default:
              error_log( 'To Model: DEFAULT='.$fileField['status'] );
              break;
          }
        }
      }
    }

    // TODO
    // ...
    // ...
    // ...


    return $result;
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
    $topicList = $topicModel->listItems( [ 'cache' => $this->cacheQuery ] );
    while( $topic = $topicList->fetch() ){
      $topics[ $topic->getter( 'id' ) ] = $topic->getter( 'name', Cogumelo::getSetupValue( 'lang:default' ) );
    }

    return $topics;
  }

  public function getOptionsTax( $taxIdName ) {
    $options = [];

    $optArray = $this->getOptionsTaxAdvancedArray( $taxIdName );

    if( count($optArray) ) {
      foreach( $optArray as $termId => $termInfo ) {
        $options[ $termId ] = $termInfo['text'];
      }
    }

    return $options;
  }

  public function getOptionsTaxAdvancedArray( $taxIdName ) {
    $options = [];
    $taxTermModel =  new TaxonomyViewModel();
    $taxTermList = $taxTermModel->listItems( [
      'filters' => [ 'taxGroupIdName' => $taxIdName ], 'order' => [ 'weight' => 1 ],
      'cache' => $this->cacheQuery
    ] );
    while( $taxTerm = $taxTermList->fetch() ) {
      $optText = $taxTerm->getter('name');
      if( !$optText || $optText === '' ) {
        $optText = $taxTerm->getter( 'name', Cogumelo::getSetupValue( 'lang:default' ));
      }
      $taxTermId = $taxTerm->getter('id');
      $options[ $taxTermId ] = [
        'text' => $optText,
        'value' => $taxTerm->getter('id'),
        'data-term-idname' => $taxTerm->getter('idName'),
        'data-term-icon' => $taxTerm->getter('icon'),
        'data-term-iconname' => $taxTerm->getter('iconName'),
        'data-term-iconakey' => $taxTerm->getter('iconAKey')
      ];
      if( $taxTerm->getter('parent') ) {
        $options[ $taxTermId ]['data-term-parent'] = $taxTerm->getter('parent');
      }
    }

    return $options;
  }



  public function getResTerms( $resId ) {
    $taxTerms = array();

    $taxTermModel =  new ResourceTaxonomytermModel();
    $taxTermList = $taxTermModel->listItems([
      'filters' => ['resource' => $resId], 'order' => ['weight' => 1],
      'cache' => $this->cacheQuery
    ]);

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

    if( !$this->taxonomyAll || ( $this->resObj && $this->resObj->getter('id') !== $resId ) ) {

      $resourceTaxAllModel = new ResourceTaxonomyAllModel();
      $taxAllList = $resourceTaxAllModel->listItems([
        'filters' => [ 'resource' => $resId ], 'order' => ['weightResTaxTerm' => 1],
        'cache' => $this->cacheQuery
      ]);

      if( gettype( $taxAllList ) === 'object' ) {
        while( $taxTerm = $taxAllList->fetch() ) {
          $termId = $taxTerm->getter('id');
          $taxTerms[ $termId ] = $this->getAllTrData( $taxTerm );

          //   $taxTerms[ $termId ] = $taxTerm->getAllData( 'onlydata' );
          //
          //   // Añadimos los campos en el idioma actual o el idioma principal
          //   $taxFields = $taxTerm->getCols();
          //   foreach( $taxFields as $key => $value ) {
          //     if( !isset( $taxTerms[ $termId ][ $key ] ) ) {
          //       $taxTerms[ $termId ][ $key ] = $taxTerm->getter( $key );
          //       // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
          //       if( $taxTerms[ $termId ][ $key ] === '' && isset( $taxTerms[ $termId ][ $key.'_'.$langDefault ] ) ) {
          //         $taxTerms[ $termId ][ $key ] = $taxTerms[ $termId ][ $key.'_'.$langDefault ];
          //       }
          //     }
          //   }
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

    if( !$resId && $this->resObj ) {
      $resId = $this->resObj->getter('id');
    }
    // error_log( "getCollectionsAll( $resId )" );

    if( $resId ) {
      if( isset( $this->collectionsAll[ $resId ] ) ) {
        $collsInfo = $this->collectionsAll[ $resId ];
      }
      else {
        $collResModel = new CollectionResourcesListViewModel();
        $collResList = $collResModel->listItems([
          'filters' => [ 'resourceMain' => $resId ],
          'order' => [ 'weight' => 1, 'weightMain' => 1 ],
          'cache' => $this->cacheQuery
        ]);
        if( gettype( $collResList ) === 'object' ) {
          $collsInfo = [];
          while( $collResObj = $collResList->fetch() ) {
            $colId = $collResObj->getter('id');
            $colType = $collResObj->getter('collectionType');
            $collsInfo[ $colType ][ $colId ] = $this->getTranslatedData( $collResObj->getAllData( 'onlydata' ) );
          }
        }

        $this->collectionsAll[ $resId ] = $collsInfo;
      }
      // error_log( "getCollectionsAll( $resId ): ".print_r( $collsInfo, true ) );
    }

    return $collsInfo;
  }

  public function getCollectionsSelect( $collsInfo ) {
    // error_log(__METHOD__);
    $collsSelect = array(
      'options' => array(),
      'values' => array()
    );

    $langDefault = Cogumelo::getSetupValue( 'lang:default' );
    foreach( $collsInfo as $collId => $collInfo ) {
        $collsSelect[ 'options' ][ $collId ] = $collInfo[ 'title_'.$langDefault ];
        $collsSelect[ 'values' ][] = $collId;
    }

    // error_log(__METHOD__.": = ". print_r( $collsSelect, true ) );
    return $collsSelect;
  }


  public function getCollectionsInfo( $resId ) {
    // error_log(__METHOD__.": $resId" );
    $colInfo = array(
      'options' => array(),
      'values' => array()
    );

    if( !empty( $resId ) ) {
      $resourceCollectionsModel =  new ResourceCollectionsModel();
      $resCollectionList = $resourceCollectionsModel->listItems([
        'filters' => [ 'resource' => $resId, 'CollectionModel.collectionType' => 'base' ],
        'order' => [ 'weight' => 1 ],
        'affectsDependences' => [ 'CollectionModel' ],
        'cache' => $this->cacheQuery
      ]);

      while( $res = $resCollectionList->fetch() ){
        $collections = $res->getterDependence( 'collection', 'CollectionModel' );

        if( $collections ){
          $colInfo[ 'options' ][ $res->getter( 'collection' ) ] = $collections[ 0 ]->getter( 'title', Cogumelo::getSetupValue( 'lang:default' ) );
          $colInfo[ 'values' ][] = $res->getter( 'collection' );
        }
      }
    }

    // error_log(__METHOD__.": = ". print_r( $colInfo, true ) );
    return ( count( $colInfo['values'] ) > 0 ) ? $colInfo : false;
  }

  public function getMultimediaInfo( $resId ) {
    // error_log(__METHOD__.": $resId" );
    $multimediaInfo = array(
      'options' => array(),
      'values' => array()
    );

    $resourceCollectionsModel =  new ResourceCollectionsModel();

    if( isset( $resId ) ) {
      $resMultimediaList = $resourceCollectionsModel->listItems([
        'filters' => [ 'resource' => $resId, 'CollectionModel.collectionType' => 'multimedia' ],
        'order' => [ 'weight' => 1 ],
        'affectsDependences' => [ 'CollectionModel' ],
        'cache' => $this->cacheQuery
      ]);

      while( $res = $resMultimediaList->fetch() ){
        $multimediaGalleries = $res->getterDependence( 'collection', 'CollectionModel' );
        if( $multimediaGalleries ){
          $multimediaInfo[ 'options' ][ $res->getter( 'collection' ) ] = $multimediaGalleries[ 0 ]->getter( 'title', Cogumelo::getSetupValue( 'lang:default' ) );
          $multimediaInfo[ 'values' ][] = $res->getter( 'collection' );
        }
      }
    }

    // error_log(__METHOD__.": = ". print_r( $colInfo, true ) );
    return ( count( $multimediaInfo['values'] ) > 0 ) ? $multimediaInfo : false;
  }







  // Carga los datos de todas las colecciones de recursos asociadas al recurso dado
  public function getCollectionBlockInfo( $resId, $collectionTypes = false, $extraFields = false ) {
    // error_log( __METHOD__.' '.$resId.' -- '.json_encode($collectionTypes).' -- '.json_encode($extraFields) );
    // error_log( 'TEMPO 1: '. sprintf( "%.3f", microtime(true) ) .' getCollectionBlockInfo - '. $_SERVER["REQUEST_URI"] );

    $collResources = false;

    $collFilters['resourceMain'] = $resId;

    if( $collectionTypes === false ) {
      $collFilters['collectionTypeIn'] = [ 'base', 'multimedia' ];
    }
    else {
      $collFilters['collectionTypeIn'] = is_array( $collectionTypes ) ? $collectionTypes : [ $collectionTypes ];
    }

    $resourceCollectionsAllModel = new ResourceCollectionsAllModel();
    $resCollectionList = $resourceCollectionsAllModel->listItems([
      'filters' => $collFilters,
      'order' => [ 'weightMain' => 1, 'weightSon' => 1 ],
      'cache' => $this->cacheQuery,
    ]);
    if( is_object( $resCollectionList ) ) {
      $resourcesSon = [];
      while( $collection = $resCollectionList->fetch() ) {
        $collId = $collection->getter('id');
        if( !isset( $collResources[ $collId ] ) ) {
          $collResources[ $collId ]['col'] = array(
            'id' => $collId,
            'title' => $collection->getter( 'title', false ),
            'shortDescription' => $collection->getter( 'shortDescription', false ),
            'description' => $collection->getter( 'description', false ),
            'image' => $collection->getter('image'),
            'imageName' => $collection->getter('imageName'),
            'imageAKey' => $collection->getter('imageAKey'),
            'collectionType' => $collection->getter('collectionType')
          );
          $collResources[ $collId ]['res'] = [];
        }

        $resSonId = $collection->getter('resourceSon');
        $resourcesSon[ $resSonId ] = true;
        $collResources[ $collId ]['res'][ $resSonId ] = true;
      } // while( $collection = $resCollectionList->fetch() )

      // error_log( 'TEMPO 2: '. sprintf( "%.3f", microtime(true) ) .' getCollectionBlockInfo - '. $_SERVER["REQUEST_URI"] );

      if( !empty( $resourcesSon ) ) {
        $resSonInfo = $this->getCollectionSonInfo( array_keys( $resourcesSon ), $extraFields );

        // error_log( 'TEMPO 3: '. sprintf( "%.3f", microtime(true) ) .' getCollectionBlockInfo - '. $_SERVER["REQUEST_URI"] );

        foreach( $collResources as $collId => $collInfo ) {
          $collResInfo = [];

          if( !empty( $collInfo['res'] ) ) {
            foreach( array_keys( $collInfo['res'] ) as $resId ) {

              $resInfo = false;
              if( !empty( $resSonInfo[ $resId ] ) ) {
                $resInfo = $resSonInfo[ $resId ];
                // Value in setup project
                $collectionsProfiles = Cogumelo::getSetupValue('collections:imageProfile');

                $thumbSettings = array(
                  'imageId' => $resInfo['imageId'],
                  'imageName' => $resInfo['imageName'],
                  // 'imageName' => $resInfo['imageId'].'.jpg',
                  'imageAKey' => $resInfo['imageAKey'],
                  'profile' => empty($collectionsProfiles['default']) ? 'fast_cut' : $collectionsProfiles['default']
                );
                $thumbSettings['url'] = !empty( $resInfo['rextUrlUrl'] ) ? $resInfo['rextUrlUrl'] : false;

                switch( $collInfo['col']['collectionType'] ) {
                  case 'multimedia':
                    $thumbSettings['profile'] = empty($collectionsProfiles['multimediaThumbnail']) ? 'imgMultimediaGallery' : $collectionsProfiles['multimediaThumbnail'];

                    $multimediaUrl = ( $thumbSettings['url'] ) ? $this->ytVidId( $thumbSettings['url'] ) : false;

                    $imgUrl = $this->getResourceThumbnail( $thumbSettings );

                    $thumbSettings['profile'] = empty($collectionsProfiles['multimediaLong']) ? 'hdpi4' : $collectionsProfiles['multimediaLong'];
                    $imgUrl2 = $this->getResourceThumbnail( $thumbSettings );

                    // TODO: CAMBIAR!!! Sobreescribe un campo (image) existente y necesario. Usar imageUrl
                    $resInfo['image'] = $imgUrl;
                    $resInfo['imageUrl'] = $imgUrl;
                    $resInfo['image_big'] = $imgUrl2;
                    $resInfo['multimediaUrl'] = $multimediaUrl;
                  break;

                  default: // base y resto
                    $imgUrl = $this->getResourceThumbnail( $thumbSettings );

                    $multimediaUrl = ( $thumbSettings['url'] ) ? $this->ytVidId( $thumbSettings['url'] ) : false;

                    // TODO: CAMBIAR!!! Sobreescribe un campo (image) existente y necesario. Usar imageUrl
                    $resInfo['image'] = $imgUrl;
                    $resInfo['imageUrl'] = $imgUrl;
                    if( $resInfo['rTypeIdName'] === 'rtypeUrl' ) {
                      $resInfo['multimediaUrl'] = $multimediaUrl;
                    }
                  break;
                } // switch
              }

              $collResInfo[ $resId ] = $resInfo;
            } // foreach( $collResources as $collId => $collInfo )
          } // if( !empty( $collInfo['res'] ) )

          $collResources[ $collId ]['res'] = $collResInfo;
        } // foreach( $collResources as $collId => $collInfo )
      }
    }

    // error_log( 'TEMPO F: '. sprintf( "%.3f", microtime(true) ) .' getCollectionBlockInfo - '. $_SERVER["REQUEST_URI"] );
    // error_log( __METHOD__.' FIN' );
    return($collResources);
  }

  public function getCollectionSonInfo( $resIds, $extraFields = false ) {
    // error_log( __METHOD__.' '.json_encode($resIds) );
    $resSonInfo = [];

    $resourceViewModel = new RExtUrlResourceViewModel();
    $resourceViewList = $resourceViewModel->listItems([
      'filters' => [ 'idIn' => $resIds, 'published' => 1 ],
      'cache' => $this->cacheQuery
    ]);

    $fileModel = new RExtFileModel();
    $fileList = $fileModel->listItems( [
      'filters' => [ 'resourceIn' => $resIds ],
      'cache' => $this->cacheQuery
    ] );

    $fieldsAuthor = [];
    if( is_object( $fileList ) ) {
      while( $fileObj = $fileList->fetch() ) {
        $fieldsAuthor[ $fileObj->getter('resource') ] = $fileObj->getter('author');
      }
    }

    if( is_object( $resourceViewList ) ) {
      while( $resVal = $resourceViewList->fetch() ) {
        $resValId = $resVal->getter('id');
        $resValImage = $resVal->getter('image');

        $resSonInfo[ $resValId ] = array(
          'id' => $resValId,
          'rType' => $resVal->getter('rTypeId'),
          'rTypeIdName' => $resVal->getter('rTypeIdName'),
          'title' => $resVal->getter( 'title', false ),
          'shortDescription' => $resVal->getter( 'shortDescription', false ),
          'mediumDescription' => $resVal->getter( 'mediumDescription', false ),
          'externalUrl' => $resVal->getter('externalUrl'),
          'urlAlias' => $resVal->getter('urlAlias'),
          'imageId' => $resValImage, // TODO: Deberia ser image
          'image' => $resValImage,
          'imageName' => $resVal->getter('imageName'),
          'imageAKey' => $resVal->getter('imageAKey'),
        );

        if( !empty( $fieldsAuthor[ $resValId ] ) ) {
          $resSonInfo[ $resValId ]['author'] = $fieldsAuthor[ $resValId ];
        }

        //Añadimos rextUrlUrl a los recursos tipo link
        $rextUrlUrl = $resVal->getter('rextUrlUrl');
        if( !empty( $rextUrlUrl ) ){
          $resSonInfo[ $resValId ]['rextUrlUrl'] = $rextUrlUrl;
        }

        // Ampliamos la carga de datos del recurso Base
        if( !empty( $extraFields ) ) {
          foreach( $extraFields as $extraF ) {
            $resSonInfo[ $resValId ][ $extraF ] = $resVal->getter( $extraF );
          }
        }
      } // if(
    } // if(

    return $resSonInfo;
  }







  public function collectionsByType( $collectionArrayInfo, $collectionTypes = false ) {
    $collectionsByType = [];

    foreach( $collectionArrayInfo as $collectionInfo ) {
      if( $collectionTypes && !in_array( $collectionInfo['col']['collectionType'], $collectionTypes ) ) {
        continue;
      }
      if( count( $collectionInfo['res'] ) > 0 ) {
        $collectionsByType[ $collectionInfo['col']['collectionType'] ][] = $collectionInfo;
      }
    }

    return $collectionsByType;
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


  // Alias - Obtiene un bloque de una colección multimedia dada
  public function getMultimediaBlock( $multimedia ) {
    return $this->getCollectionBlock( $multimedia );
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

    if( count($formValues) === 1 ) {
      $elm = current($formValues);
      if (!$elm || $elm === 0){
         $formValues = false;
      }
    }

    if( $formValues !== false ) {
      // Si estamos editando, repasamos y borramos relaciones sobrantes
      if( $baseId ) {
        $relModel = new ResourceTopicModel();
        $relPrevList = $relModel->listItems( [ 'filters' => [ 'resource' => $baseId ], 'cache' => $this->cacheQuery ] );
        if( gettype( $relPrevList ) === 'object' ) {
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










  public function setTaxTerms( $taxGroup, $taxTermIds, $resource ) {
    // error_log(__METHOD__);
    $result = true;


    $relPrevInfo = false;

    $baseResId = is_numeric( $resource ) ? $resource : $resource->getter( 'id' );

    if( $taxTermIds !== false && !is_array( $taxTermIds ) ) {
      $taxTermIds = ( $taxTermIds !== '' &&  is_numeric( $taxTermIds ) ) ? [ $taxTermIds ] : false;
    }

    // Si estamos editando, repasamos y borramos relaciones sobrantes
    if( !empty( $baseResId ) ) {

      $relFilter = [ 'resource' => $baseResId ];

      if( is_numeric( $taxGroup ) ) {
        $relFilter[ 'taxgroup' ] = $taxGroup;
      }
      else {
        $relFilter[ 'idNameTaxgroup' ] = $taxGroup;
      }

      $relModel = new ResourceTaxonomyAllModel();
      $relPrevList = $relModel->listItems([
        'filters' => $relFilter, 'cache' => $this->cacheQuery
      ]);
      if( is_object( $relPrevList ) ) {
        // estaban asignados antes
        $relPrevInfo = [];
        $resTermModel = new ResourceTaxonomytermModel();
        while( $relPrev = $relPrevList->fetch() ){
          $relPrevInfo[ $relPrev->getter( 'id' ) ] = $relPrev->getter( 'idResTaxTerm' );
          if( $taxTermIds === false || !in_array( $relPrev->getter( 'id' ), $taxTermIds ) ) { // desasignar
            // buscamos el término descartado y lo borramos
            $resTerm = $resTermModel->listItems([
              'filters' => [ 'resource' => $baseResId, 'taxonomyterm' =>$relPrev->getter( 'id' ) ],
              'cache' => $this->cacheQuery
            ])->fetch();
            $resTerm->delete();
          }
        }
      }
    }

    // Creamos-Editamos todas las relaciones
    if( $taxTermIds !== false ) {
      $weight = 0;
      foreach( $taxTermIds as $value ) {
        $weight++;
        $info = [ 'resource' => $baseResId, 'taxonomyterm' => $value, 'weight' => $weight ];
        if( $relPrevInfo !== false && isset( $relPrevInfo[ $value ] ) ) { // Update
          $info[ 'id' ] = $relPrevInfo[ $value ];
        }

        $relObj = new ResourceTaxonomytermModel( $info );
        if( !$relObj->save() ) {
          $result = false;
          break;
        }
      }
    }

    return $result;
  } // setTaxTerms( $taxGroup, $taxTermIds, $resource )



  // public function setFormTax( $form, $fieldName, $taxGroup, $taxTermIds, $baseObj ) {
  //   // error_log(__METHOD__);
  //   if( !$this->setTaxTerms( $taxGroup, $taxTermIds, $baseObj ) ) {
  //     $form->addFieldRuleError( $fieldName, false, __( 'Error setting values' ) );
  //   }
  // } // setFormTax( $form, $fieldName, $taxGroup, $taxTermIds, $baseObj )
  public function setFormTax( $form, $fieldName, $taxGroup, $taxTermIds, $baseObj ) {
    $taxError = $this->setTaxValues($taxGroup, $taxTermIds, $baseObj);

    if(!$taxError){
      $form->addFieldRuleError( $fieldName, false, __( 'Error setting values' ) );
    }
  }


  public function setTaxValues( $taxGroup, $taxTermIds, $baseObj ) {
    // error_log(__METHOD__);
    $relPrevInfo = false;
    $baseId = $baseObj->getter( 'id' );

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
      $relPrevList = $relModel->listItems([ 'filters' => $relFilter, 'cache' => $this->cacheQuery ]);
      if( gettype( $relPrevList ) === 'object' ) {
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
    $result = true;
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
          $result = false;
          break;
        }
      }
    }
    return $result;
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
      $relPrevList = $relModel->listItems( [ 'filters' => [ 'resource' => $baseId ], 'cache' => $this->cacheQuery ] );
      if( gettype( $relPrevList ) === 'object' ) {
        // estaban asignados antes
        $relPrevInfo = array();
        $colModel = new CollectionModel();
        while( $relPrev = $relPrevList->fetch() ) {
          $colList = $colModel->listItems( [ 'filters' => [ 'id' => $relPrev->getter('collection') ], 'cache' => $this->cacheQuery ] );
          $collection = ( gettype( $colList ) === 'object' ) ? $colList->fetch() : false;
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

  public function sanitizeText( $text ) {
    // "Aplanamos" caracteres no ASCII7
    $text = str_replace( $this->urlTranslate['from'], $this->urlTranslate['to'], $text );

    // Solo admintimos a-z A-Z 0-9 _ - / El resto pasan a ser -
    $text = preg_replace( '/[^a-z0-9_\-\/]/iu', '-', $text );

    // Limpiamos sobrantes
    // $text = preg_replace( '/--+/u', '-', $text );
    $text = preg_replace( '/-*([_\-\/])-*/u', '${1}', $text );
    $text = trim( $text, '-' );

    return $text;
  }

  public function sanitizeUrl( $url ) {
    // "Aplanamos" caracteres no ASCII7 y limpiamos sobrantes
    $url = $this->sanitizeText( $url );

    // Por si ha quedado algo, pasamos el validador de PHP
    $url = filter_var( $url, FILTER_SANITIZE_URL );

    return $url;
  }

  private function evalFormUrlAlias( $form, $fieldName ) {
    foreach( $form->multilangFieldNames( $fieldName ) as $fieldNameLang ) {
      $url = $form->getFieldValue( $fieldNameLang );
      $error = false;
      if( $url !== null && $url !== '' ) {
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

  private function setFormAdminUrlAlias( $form, $fieldName, $resObj ) {
    cogumelo::debug(__METHOD__.": form, $fieldName, resObj " );
    if( $form->isFieldDefined( $fieldName ) || $form->isFieldDefined( $fieldName.'_'.$form->langDefault ) ) {
      $resId = $resObj->getter('id');
      foreach( $form->langAvailable as $langId ) {
        $url = $form->getFieldValue( $fieldName.'_'.$langId );
        if( $this->setUrlAdminAlias( $resId, $langId, $url ) === false ) {
          $form->addFieldRuleError( $fieldName.'_'.$langId, false, __( 'Error setting URL alias 2' ) );
          break;
        }
      }
    }
  }

  public function getUrlByPattern( $resId, $langId = false, $title = false ) {
    // error_log( "getUrlByPattern( $resId, $langId, $title )" );
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
            $title = $resData[ 'title_'.$langId ];
          }
          else {
            if( isset( $resData[ 'title_'.$this->defLang ] ) && $resData[ 'title_'.$this->defLang ] !== '' ) {
              $title = $resData[ 'title_'.$this->defLang ];
            }
          }
        }
        else {
          if( isset( $resData['title'] ) && $resData['title'] !== '' ) {
            $title = $resData['title'];
          }
          else {
            if( isset( $resData[ 'title_'.$this->defLang ] ) && $resData[ 'title_'.$this->defLang ] !== '' ) {
              $title = $resData[ 'title_'.$this->defLang ];
            }
          }
        }
      }
    }

    if( $title ) {
      $title = mb_strtolower( $this->sanitizeText($title) );
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

    $elemModel = new UrlAliasModel();


    $colision = $elemModel->listCount([
      'filters' => [ 'resourceNot' => $resId, 'lang' => $langId, 'urlFrom' => $urlAlias ],
      'cache' => $this->cacheQuery
    ]);
    if( !empty($colision) ) {
      $urlAlias .= '_'.$resId;
      error_log( 'setUrl() COLISION: '.$urlAlias );
    }


    $aliasArray = array( 'http' => 0, 'canonical' => 1, 'lang' => $langId,
      'urlFrom' => $urlAlias, 'urlTo' => null, 'resource' => $resId
    );

    $elemsList = $elemModel->listItems([
      'filters' => [ 'canonical' => 1, 'resource' => $resId, 'lang' => $langId ],
      'cache' => $this->cacheQuery
    ]);
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


  public function setUrlAdminAlias( $resId, $langId, $urlAlias ) {
    cogumelo::debug(__METHOD__."( $resId, $langId, $urlAlias )" );
    $result = true;

    $aliasId = false;

    $urlAlias = $this->sanitizeUrl( $urlAlias );

    $aliasArray = array( 'label' => 'adminAlias', 'http' => 301, 'canonical' => 0,
      'lang' => $langId, 'urlFrom' => $urlAlias, 'urlTo' => null, 'resource' => $resId
    );

    $elemModel = new UrlAliasModel();
    $elemsList = $elemModel->listItems([
      'filters' => [ 'label' => 'adminAlias', 'http' => 301, 'canonical' => 0, 'resource' => $resId, 'lang' => $langId ],
      'cache' => $this->cacheQuery
    ]);
    $aliasObj = ( gettype( $elemsList ) === 'object' ) ? $elemsList->fetch() : false;
    if( gettype( $aliasObj ) === 'object' ) {
      $aliasId = $aliasObj->getter( 'id' );
      cogumelo::debug(__METHOD__.': Xa existe - '.$aliasId );
    }

    if( empty( $urlAlias ) ) {
      if( $aliasId ) {
        cogumelo::debug(__METHOD__.': Borrando '.$aliasId );
        $aliasObj->delete();
      }
    }
    else {
      if( $aliasId ) {
        $aliasArray[ 'id' ] = $aliasId;
      }
      $elemModel = new UrlAliasModel( $aliasArray );
      if( $elemModel->save() === false ) {
        $result = false;
        error_log(__METHOD__.': ERROR gardando a url' );
      }
      else {
        $result = $elemModel->getter( 'id' );
        cogumelo::debug(__METHOD__.': Creada/Actualizada - '.$result );
      }
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
    $urlList = $urlModel->listItems( [ 'filters' => $filters, 'cache' => $this->cacheQuery ] );

    $urlObj = ( gettype( $urlList ) === 'object' ) ? $urlList->fetch() : false;

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
    // error_log(__METHOD__);

    $viewBlockInfo = array(
      'template' => false,
      'data' => false,
      'ext' => []
    );

    $tempo = microtime(true);

    if( $cache = Cogumelo::GetSetupValue('cache:ResourceController:getViewBlockInfo') ) {
      Cogumelo::log( __METHOD__.' ---- ESTABLECEMOS CACHE A '.$cache, 'cache' );
      $this->cacheQuery = $cache;
    }


    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    if( $resObj=$this->loadResourceObject( $resId ) ) {
      if( $resObj->getter( 'published' ) || $user ) {
        $viewBlockInfo['data'] = $this->getResourceData( $resId );

        if( $rTypeView = $this->getRTypeView( $viewBlockInfo['data']['rTypeId'] ) ) {
          $viewBlockInfo = $rTypeView->getViewBlockInfo( $resId );
        }

        if( !$resObj->getter( 'published' ) ) {
          // Recurso NO publicado
          $viewBlockInfo = array(
            'unpublished' => $viewBlockInfo
          );
        }
      }
    }

    $tempo2 = microtime(true);
    Cogumelo::log( 'TEMPO F: '. sprintf( "%.3f", $tempo2-$tempo) .' getViewBlockInfo('.$resId.') - '. $_SERVER["REQUEST_URI"] );
    return( $viewBlockInfo );
  } // function getViewBlockInfo()





  /**
   * Devuelve modelData con los campos traducibles en el idioma actual
   */
  public function getTranslatedData( $modelData ) {
    if( is_array( $modelData ) && count( $modelData ) > 0 ) {
      foreach( $modelData as $key => $data ) {
        if( strpos($key,'_'.$this->actLang) ) { // existe en el idioma actual
          $key_parts = explode('_'.$this->actLang, $key);
          if( $data && $data !== '' && $data !== null ) {
            $modelData[ $key_parts[0] ] = $data;
          }
          else {
            $modelData[ $key_parts[0] ] = $modelData[$key_parts[0].'_'.$this->defLang];
          }
        }
      }
    }

    return $modelData;
  }

  public function getAllTrData( $objModel ) {
    $allData = [];

    if( is_object( $objModel ) ) {
      $defLang = Cogumelo::getSetupValue( 'lang:default' );
      $allData = $objModel->getAllData( 'onlydata' ); // Cargamos todos los campos "en bruto"

      foreach( $objModel->getCols() as $fieldName => $fieldInfo ) {
        $allData[ $fieldName ] = $objModel->getter( $fieldName );
        // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
        if( ( $allData[ $fieldName ] === '' || $allData[ $fieldName ] === null ) && isset( $allData[ $fieldName.'_'.$defLang ] ) ) {
          $allData[ $fieldName ] = $allData[ $fieldName.'_'.$defLang ];
        }
      }
    }

    return $allData;
  }

  public function getResourceThumbnail( $param ) {
    // error_log( 'getResourceThumbnail :'.print_r($param,true));
    $thumbImg = Cogumelo::getSetupValue('publicConf:vars:media').'/module/geozzy/img/default-multimedia.png';

    if( array_key_exists( 'imageId', $param ) && $param['imageId'] && $param['imageId'] !== 'null' ) {
      if( !isset( $param['imageName'] ) ) {
        $param['imageName'] = $param['imageId'].'.jpg';
      }
      $prof = array_key_exists( 'profile', $param ) ? $param['profile'].'/' : '';

      $urlId = isset( $param['imageAKey'] ) ? $param['imageId'].'-a'.$param['imageAKey'] : $param['imageId'];
      $thumbImg = Cogumelo::getSetupValue('publicConf:vars:mediaHost').'cgmlImg/'.$urlId.
        '/'.$prof.$param['imageName'];
    }
    else {
      if( array_key_exists( 'url', $param ) ){
        $isYoutubeID = $this->ytVidId( $param['url'] );
        if( $isYoutubeID ) {
          $thumbImg = 'https://img.youtube.com/vi/'.$isYoutubeID.'/0.jpg';
        }
      }
    }

    return $thumbImg;
  }

  public function ytVidId( $url ) {
    $p = '#^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$#';
    return (preg_match($p, $url, $coincidencias)) ? $coincidencias[1] : false;
  }


  /**
   *  Etiquetas informativas (labels con datos relevantes)
   **/
  public function getLabelsData( $resViewData ) {
    $labelData = [];

    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    $labelData[] = $user ? 'user_LI' : 'user_LO'; /*  Logged In: LI  ---  Logged Out: LO  */

    if( !empty( $resViewData['id'] ) ) {
      $labelData[] = 'id_'.$resViewData['id'];
    }

    if( !empty( $resViewData['idName'] ) ) {
      $labelData[] = 'idN_'.$resViewData['idName'];
    }

    if( !empty( $resViewData['rTypeIdName'] ) ) {
      $labelData[] = 'rtypeIdN_'.$resViewData['rTypeIdName'];
    }

    if( !empty( $resViewData['taxonomies'] ) ) {
      foreach( $resViewData['taxonomies'] as $idNameTaxgroup => $taxTermArray ) {
        $labelData[] = 'taxN_'.$idNameTaxgroup;
      }
    }

    if( !empty( $resViewData['topic'] ) ) {
      $labelData[] = 'idT_'.$resViewData['topic'];
    }

    return $labelData;
  }



  public function setResourcesToCollection( $colId = false, $newResources ){
    $oldResources = false;

    if( $newResources !== false && !is_array($newResources) ) {
      $newResources = array($newResources);
    }

    $collectionControl = new CollectionModel();
    $collection = $collectionControl->listItems(['filters' => ['id' => $colId] ])->fetch();
    // Si estamos editando, repasamos y borramos recursos sobrantes
    if( $colId ) {
      $CollectionResourcesModel = new CollectionResourcesModel();
      $collectionResourceList = $CollectionResourcesModel->listItems(
        array('filters' => array('collection' => $colId)) );

      if( $collectionResourceList ) {
        // estaban asignados antes
        $oldResources = array();
        while( $oldResource = $collectionResourceList->fetch() ){
          $oldResources[ $oldResource->getter('resource') ] = $oldResource->getter('id');
          if( $newResources === false || !in_array( $oldResource->getter('resource'), $newResources ) ) {
            $oldResource->delete(); // desasignar
          }
        }
      }
    }

    // Creamos-Editamos todas las relaciones con los recursos
    if( $newResources !== false ) {
      $weight = 0;
      foreach( $newResources as $resource ) {
        $weight++;
        if( $oldResources === false || !isset( $oldResources[ $resource ] ) ) {
          $collection->setterDependence( 'id',
            new CollectionResourcesModel( array( 'weight' => $weight,
              'collection' => $colId, 'resource' => $resource)) );
        }
        else {
          $collection->setterDependence( 'id',
            new CollectionResourcesModel( array( 'id' => $oldResources[ $resource ],
              'weight' => $weight, 'collection' => $colId, 'resource' => $resource))
          );
        }
      }
    }

    return $collection;
  }

















  // $this->cloneToRType( $form->getFieldValue('id'), 'rtypeAppBlogPub' );


  public function cloneToRType( $resFromId, $rTypeIdName, $topicIdName = false ) {
    error_log( __METHOD__.': $resFromId: '.$resFromId.' $rTypeIdName: '.$rTypeIdName.' $topicIdName: '.$topicIdName );
    $resToObj = null;

    $error = false;

    $resModel = new ResourceModel();
    $resList = $resModel->listItems([
      // 'affectsDependences' => [ 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ExtraDataModel' ],
      'filters' => [ 'id' => $resFromId ],
      'cache' => $this->cacheQuery,
    ]);
    $resFromObj = ( is_object( $resList ) ) ? $resList->fetch() : null;

    if( !is_object( $resFromObj ) ) {
      $error = __LINE__;
    }
    else {
      // Cargamos todos los campos del recurso Base
      $resData = $resFromObj->getAllData('onlydata');
      // error_log( '$resData: '.json_encode( $resData ) );

      // $formBlockInfo = $this->getFormBlockInfo( 'clone', 'clone', [], $resData );
      // error_log( '$formBlockInfo[data]: '.json_encode( $formBlockInfo['data'] ) );

      $resData['published'] = 0;
      $resData['rTypeId'] = $this->getRTypeIdByIdName( $rTypeIdName );

      unset( $resData['id'] );
    }


    if( !$error ) {
      $resToObj = new ResourceModel( $resData );

      if( !is_object($resToObj) ) {
        $error = __LINE__;
      }
      else {
        $resToObj->transactionStart();
        if( !$resToObj->save() ) {
          $error = __LINE__;
        }
      }
      // error_log( 'saveResult: '.json_encode( $saveResult ) );
    }

    if( !$error ) {
      if( !$this->cloneCollections( $resFromId, $resToObj->getter('id'), ['base', 'multimedia'] ) ) {
        $error = __LINE__;
      }
    }

    if( !$error ) {
      if( empty( $topicIdName ) ) {
        // Copiamos los topics del origen
        if( !$this->cloneTopics( $resFromId, $resToObj->getter('id') ) ) {
          $error = __LINE__;
        }
      }
      else {
        // Establecemos el topic indicado

        $topicModel = new TopicModel();
        $topicList = $topicModel->listItems([
          'filters' => [ 'idName' => $topicIdName ],
          'cache' => $this->cacheQuery
        ]);
        if( !is_object( $topicList ) ) {
          $error = __LINE__;
          error_log(__METHOD__.': ERROR topicList' );
        }
        else {
          if( $topicFromObj = $topicList->fetch() ) {
            $resTopicObj = new ResourceTopicModel([
              'resource' => $resToObj->getter('id'),
              'topic' => $topicFromObj->getter('id')
            ]);
            $resTopicObj->save();
            if( !is_object( $resTopicObj ) ) {
              $error = __LINE__;
              error_log(__METHOD__.': ERROR enlazando Topic: '.$topicIdName );
            }
          }
          else {
            $error = __LINE__;
            error_log(__METHOD__.': ERROR Topic "'.$topicIdName.'" no encontrado' );
          }
        }
      }
    }

    if( !$error ) {
      // error_log( __METHOD__.': Solicito getRTypeCtrl '.$rTypeIdName );
      $rTypeCtrl = $this->getRTypeCtrl( $rTypeIdName );
      if( !$rTypeCtrl ) {
        $error = __LINE__;
      }
      else {
        $rTypeCtrl->cloneTo( $resFromObj, $resToObj );
      }
    }

    if( is_object($resToObj) ) {
      if( !$error ) {
        $resToObj->transactionCommit();
      }
      else {
        $resToObj->transactionRollback();
        error_log( __METHOD__.': ERROR '.$error );
      }
    }


    return( ( !$error ) ? $resToObj : false );
  }


  public function cloneCollections( $resFromId, $resToId, $collectionTypes = true ) {
    Cogumelo::debug( __METHOD__.': $resFromId: '.$resFromId.' $resToId: '.$resToId.' Tipos: '.json_encode($collectionTypes) );
    $result = true;

    if( $collectionTypes !== true && !is_array( $collectionTypes ) ) {
      $collectionTypes = [ $collectionTypes ];
    }

    $resCollections = $this->getCollectionsAll( $resFromId );

    if( is_array( $resCollections ) && count( $resCollections ) > 0 ) {
      $collModel = new CollectionModel();
      $collResModel = new CollectionResourcesModel();
      $resCollModel = new ResourceCollectionsModel();

      foreach( $resCollections as $collType => $collsArray ) {
        if( $collectionTypes === true || in_array( $collType, $collectionTypes ) ) {
          foreach( $collsArray as $collInfo ) {

            // $result = $this->cloneCollectionTo( $resToId, $collInfo['id'] );

            $collFromId = $collInfo['id'];

            $collList = $collModel->listItems( [ 'filters' => [ 'id' => $collFromId ], 'cache' => $this->cacheQuery ] );
            $collFromObj = ( is_object( $collList ) ) ? $collList->fetch() : false;
            if( !is_object( $collFromObj ) ) {
              $result = false;
            }
            else {

              // 1- Cargar y clonar el modelo de la coleccion: geozzy_collection
              // error_log(__METHOD__.': Paso 1');

              $collFromData = $collFromObj->getAllData('onlydata');
              unset( $collFromData['id'] );
              $collToObj = new CollectionModel( $collFromData );
              if( !$collToObj->save() ) {
                $result = false;
              }
              else {
                $collToId = $collToObj->getter('id');

                // 2- Enlazar en la coleccion creada los recursos que tiene que conter: geozzy_collection_resources
                // error_log(__METHOD__.': Paso 2');

                $collResList = $collResModel->listItems( [ 'filters' => [ 'collection' => $collFromId ], 'cache' => $this->cacheQuery ] );
                if( !is_object( $collResList ) ) {
                  $result = false;
                }
                else {
                  while( $collResObj = $collResList->fetch() ) {
                    $collResData = $collResObj->getAllData('onlydata');

                    unset( $collResData['id'] );
                    $collResData['collection'] = $collToId;
                    // error_log(__METHOD__.': Paso 2 - Res: '. $collResData['resource'] );

                    $newCollResObj = new CollectionResourcesModel( $collResData );
                    if( !$newCollResObj->save() ) {
                      $result = false;
                      break;
                    }
                  }
                }

                // 3- Enlazar la coleccion creada desde el recurso indicado: geozzy_resource_collections
                // error_log(__METHOD__.': Paso 3');

                $resCollList = $resCollModel->listItems([
                  'filters' => [ 'resource' => $resFromId, 'collection' => $collFromId ],
                  'cache' => $this->cacheQuery
                ]);
                $resCollFromObj = ( is_object( $resCollList ) ) ? $resCollList->fetch() : false;
                if( !is_object( $resCollFromObj ) ) {
                  $result = false;
                }
                else {
                  $resCollData = $resCollFromObj->getAllData('onlydata');

                  unset( $resCollData['id'] );
                  $resCollData['collection'] = $collToId;
                  $resCollData['resource'] = $resToId;

                  $resCollToObj = new ResourceCollectionsModel( $resCollData );
                  if( !$resCollToObj->save() ) {
                    $result = false;
                  }
                }
              }
            }
          }
        }
      }
    }

    return $result;
  }


  public function cloneTaxonomies( $resFromId, $resToId, $taxIdNames = true ) {
    Cogumelo::debug( __METHOD__.': $resFromId: '.$resFromId.' $resToId: '.$resToId.' Tipos: '.json_encode($taxIdNames) );
    $result = true;

    $cloneTermIds = [];

    if( $taxIdNames !== true && !is_array( $taxIdNames ) ) {
      $taxIdNames = [ $taxIdNames ];
    }

    $filters = [ 'resource' => $resFromId ];
    if( $taxIdNames !== true ) {
      $filters['idNameTaxgroupIn'] = $taxIdNames;
    }
    $resourceTaxAllModel = new ResourceTaxonomyAllModel();
    $resourceTaxAllList = $resourceTaxAllModel->listItems([ 'filters' => $filters, 'cache' => $this->cacheQuery ]);
    if( !is_object( $resourceTaxAllList ) ) {
      $result = false;

      error_log(__METHOD__.': NON listItems 1' );
    }
    else {
      while( $resourceTaxAllObj = $resourceTaxAllList->fetch() ) {
        $cloneTermIds[] = $resourceTaxAllObj->getter('id');
      }
    }

    Cogumelo::debug(__METHOD__.': Clonando cloneTermIds: '. json_encode($cloneTermIds) );

    if( $result && count( $cloneTermIds ) > 0 ) {

      Cogumelo::debug(__METHOD__.': Clonando Terminos' );

      $resourceTaxModel = new ResourceTaxonomytermModel();
      $resourceTaxList = $resourceTaxModel->listItems([
        'filters' => [ 'resource' => $resFromId, 'taxonomytermIn' => $cloneTermIds ],
        'cache' => $this->cacheQuery
      ]);
      if( !is_object( $resourceTaxList ) ) {
        $result = false;

        error_log(__METHOD__.': NON listItems 2' );
      }
      else {
        while( $resourceTaxObj = $resourceTaxList->fetch() ) {
          $resourceTaxData = $resourceTaxObj->getAllData('onlydata');

          Cogumelo::debug(__METHOD__.': Clonando Term: '. $resourceTaxData['taxonomyterm'] );

          unset( $resourceTaxData['id'] );
          $resourceTaxData['resource'] = $resToId;

          $resourceTaxToObj = new ResourceTaxonomytermModel( $resourceTaxData );
          if( !$resourceTaxToObj->save() ) {
            $result = false;
          }
        }
      }
    }

    return $result;
  }


  public function cloneTopics( $resFromId, $resToId ) {
    Cogumelo::debug( __METHOD__.': $resFromId: '.$resFromId.' $resToId: '.$resToId );
    $result = true;


    $topicModel = new ResourceTopicModel();
    $topicList = $topicModel->listItems([
      'filters' => [ 'resource' => $resFromId ],
      'cache' => $this->cacheQuery
    ]);
    if( !is_object( $topicList ) ) {
      $result = false;
      error_log(__METHOD__.': ERROR topicList 2' );
    }
    else {
      while( $topicFromObj = $topicList->fetch() ) {
        $topicData = $topicFromObj->getAllData('onlydata');

        unset( $topicData['id'] );
        $topicData['resource'] = $resToId;

        $topicToObj = new ResourceTopicModel( $topicData );
        if( !$topicToObj->save() ) {
          $result = false;
          error_log(__METHOD__.': NON save' );

          break;
        }
      }
    }


    return $result;
  }


  public function cloneRExtModels( $resFromId, $resToId, $models ) {
    Cogumelo::debug( __METHOD__.': $resFromId: '.$resFromId.' $resToId: '.$resToId.' Tipos: '.json_encode($models) );
    $result = true;


    foreach( $models as $modelName ) {

      Cogumelo::debug(__METHOD__.': Clonando modelName: '.$modelName );

      $rExtModel = new $modelName();
      $rExtList = $rExtModel->listItems([
        'filters' => [ 'resource' => $resFromId ],
        'cache' => $this->cacheQuery
      ]);
      if( !is_object( $rExtList ) ) {
        $result = false;
        error_log(__METHOD__.': NON listItems 2' );

        break;
      }
      else {
        while( $rExtFromObj = $rExtList->fetch() ) {
          $resModelData = $rExtFromObj->getAllData('onlydata');

          // error_log(__METHOD__.': Clonando Model Obj' );

          unset( $resModelData['id'] );
          $resModelData['resource'] = $resToId;

          $resModelToObj = new $modelName( $resModelData );
          if( !$resModelToObj->save() ) {
            $result = false;
            error_log(__METHOD__.': NON save' );

            break;
          }
        }
      }
    }

    return $result;
  }


}
