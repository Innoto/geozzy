<?php

geozzy::load( 'controller/RTypeController.php' );
geozzy::load( 'controller/RExtController.php' );


class ResourceController {

  public $rTypeCtrl = null;

  public function __construct() {
    // error_log( 'ResourceController::__construct' );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();
  }

  /**
     Cargando controlador del RType
   **/
  public function getRTypeCtrl( $rTypeId ) {
    error_log( "GeozzyResourceView: getRTypeCtrl( $rTypeId )" );

    if( !$this->rTypeCtrl ) {

      $rType = new ResourcetypeModel();
      $rTypeList = $rType->listItems( array( 'filters' => array( 'id' => $rTypeId ) ) );

      while ($rTypeName = $rTypeList->fetch()){
        $rTypeIdname = $rTypeName->getter('idName');
      }

      switch( $rTypeIdname ) {
        case 'rtypeHotel': // 'rtypeHotel'
          error_log( "GeozzyResourceView: getRTypeCtrl = rtypeHotel" );
          rtypeHotel::autoIncludes();
          $this->rTypeCtrl = new RTypeHotelController( $this );
          break;
        case 'rtypeRestaurant':
          error_log( "GeozzyResourceView: getRTypeCtrl = RTypeRestaurantController " );
          rtypeRestaurant::autoIncludes();
          $this->rTypeCtrl = new RTypeRestaurantController( $this );
          break;
        case 'rtypeUrl':
          error_log( "GeozzyResourceView: getRTypeCtrl = RTypeUrlController " );
          rtypeRestaurant::autoIncludes();
          $this->rTypeCtrl = new RTypeRestaurantController( $this );
          break;
        default:
          $this->rTypeCtrl = false;
          break;
      }
    }

    return $this->rTypeCtrl;
  }


  /**
     Load basic data values
   *
   * @param $resId integer
   *
   * @return array OR false
   **/
  public function getResourceData( $resId ) {
    // error_log( "ResourceController: getResourceData()" );
    $resourceData = false;

    $langDefault = LANG_DEFAULT;
    global $LANG_AVAILABLE;
    if( isset( $LANG_AVAILABLE ) && is_array( $LANG_AVAILABLE ) ) {
      $langAvailable = array_keys( $LANG_AVAILABLE );
    }

    $recModel = new ResourceModel();
    $recList = $recModel->listItems( array( 'affectsDependences' =>
      array( 'FiledataModel', 'UrlAliasModel', 'ResourceTopicModel', 'ResourceTaxonomytermModel', 'ExtraDataModel' ),
      'filters' => array( 'id' => $resId, 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1 ) ) );
    $recObj = $recList->fetch();

    if( $recObj ) {
      $resourceData = $recObj->getAllData( 'onlydata' );

      // Adapto el campo recursoTipo para mayor claridad
      // $resourceData['rTypeId'] = $resourceData['type'];
      // unset( $resourceData['type'] );

      // Cargo los datos de urlAlias dentro de los del recurso
      $urlAliasDep = $recObj->getterDependence( 'id', 'UrlAliasModel' );
      if( $urlAliasDep !== false ) {
        foreach( $urlAliasDep as $urlAlias ) {
          $urlLang = $urlAlias->getter('lang');
          if( $urlLang ) {
            $resourceData[ 'urlAlias_'.$urlLang ] = $urlAlias->getter('urlFrom');
          }
        }
      }

      // Cargo los datos de image dentro de los del recurso
      $fileDep = $recObj->getterDependence( 'image' );
      if( $fileDep !== false ) {
        foreach( $fileDep as $fileModel ) {
          $resourceData[ 'image' ] = $fileModel->getAllData( 'onlydata' );
        }
      }

      // Cargo los datos de temáticas con las que está asociado el recurso
      $topicsDep = $recObj->getterDependence( 'id', 'ResourceTopicModel');
      if( $topicsDep !== false ) {
        foreach( $topicsDep as $topicRel ) {
          $topicsArray[$topicRel->getter('id')] = $topicRel->getter('topic');
        }
        $resourceData[ 'topics' ] = $topicsArray;
      }

      // Cargo los datos de destacados con los que está asociado el recurso
      // $resourceData[ 'starred' ] = $this->getResTerms( $resId );
      $taxTermDep = $recObj->getterDependence( 'id', 'ResourceTaxonomytermModel');
      if( $taxTermDep !== false ) {
        foreach( $taxTermDep as $taxTerm ) {
          $taxTermArray[$taxTerm->getter('id')] = $taxTerm->getter('taxonomyterm');
        }
        $resourceData[ 'starred' ] = $taxTermArray;
      }

      // Cargo los datos del campo batiburrillo
      $extraDataDep = $recObj->getterDependence( 'id', 'ExtraDataModel');
      if( $extraDataDep !== false ) {
        foreach( $extraDataDep as $extraData ) {
          foreach( $langAvailable as $lang ) {
            $resourceData[ $extraData->getter('name').'_'.$lang ] = $extraData->getter( 'value_'.$lang );
          }
        }
      }
    }

    return $resourceData;
  }


  /**
     Defino el formulario
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Form
   **/
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "ResourceController: getFormObj()" );

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', __( 'Thank you' ) );
    $form->setSuccess( 'redirect', SITE_URL . 'admin#resource/list' );


    $resCollections = array();
    if( isset( $valuesArray[ 'id' ] ) ) {
      $colInfo = $this->getCollectionsInfo( $valuesArray[ 'id' ] );
      if( $colInfo ) {
        $resCollections = $colInfo['options'];
        $valuesArray[ 'collections' ] = $colInfo['values'];
      }
    }

    $fieldsInfo = array(
      'rTypeId' => array(
        'params' => array( 'type' => 'reserved' )
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
      'image' => array(
        'params' => array( 'label' => __( 'Image' ), 'type' => 'file', 'id' => 'imgResource',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => '/imgResource' ),
        'rules' => array( 'minfilesize' => '1024', 'maxfilesize' => '100000', 'accept' => 'image/jpeg' )
      ),
      'urlAlias' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'SEO: URL' ) ),
        'rules' => array( 'maxlength' => '2000' )
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
      'published' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => 'Publicado' ))
      ),
      'topics' => array(
        'params' => array( 'label' => __( 'Topics' ), 'type' => 'checkbox', 'options'=> $this->getOptionsTopic() )
      ),
      'starred' => array(
        'params' => array( 'label' => __( 'Starred' ), 'type' => 'checkbox', 'options'=> $this->getOptionsTax( 'starred' ) )
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    // Valadaciones extra
    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );
    $form->removeValidationRule( 'collections', 'inArray' );

    // Si es una edicion, añadimos el ID y cargamos los datos
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
      $form->loadArrayValues( $valuesArray );
      // error_log( 'GeozzyResourceView getFormObj: ' . print_r( $valuesArray, true ) );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => __( 'Send' ), 'class' => 'gzzAdminToMove' ) );

    // Una vez que lo tenemos definido, guardamos el form en sesion
    $form->saveToSession();

    return( $form );
  } // function getFormObj()



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

    $this->evalFormUrlAlias( $form, 'urlAlias' );
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

    if( !$form->existErrors() && $form->isFieldDefined( 'collections' ) ) {
      $this->setFormCollection( $form, 'collections', $resource );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'starred' ) ) {
      $this->setFormTax( $form, 'starred', 'starred', $form->getFieldValue( 'starred' ), $resource );
    }

    if( !$form->existErrors() ) {
      $this->setFormExtraData( $form, 'datoExtra1', 'datoExtra1', $resource );
    }
    if( !$form->existErrors() ) {
      $this->setFormExtraData( $form, 'datoExtra2', 'datoExtra2', $resource );
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
  private function setFormFiledata( $form, $fieldName, $colName, $resObj ) {
    $fileField = $form->getFieldValue( $fieldName );
    error_log( 'fileInfo: '. print_r( $fileField, true ) );
    $fileFieldValues = false;
    $error = false;

    if( isset( $fileField['status'] ) ) {

      // error_log( 'To Model - fileInfo: '. print_r( $fileField[ 'values' ], true ) );

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
    while( $taxTerm = $taxTermList->fetch() ){
      $taxTerms[ $taxTerm->getter( 'id' ) ] = $taxTerm->getter( 'taxonomyterm' );
    }

    return( count( $taxTerms ) > 0 ? $taxTerms : false );
  }

  /* Devolve as taxonomías asociadas cun listado de términos*/
  public function getTermsGrouped( $termIds ) {

    $taxTermModel =  new TaxonomytermModel();
    $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'idInCSV' => $termIds ) ) );

    $taxTerms = array();
    while( $taxTerm = $taxTermList->fetch() ){
      $taxTerms[ $taxTerm->getter( 'id' ) ] = $taxTerm->getter('taxgroup');
    }

    return( count( $taxTerms ) > 0 ? $taxTerms : false );
  }

  /* Devolve un listado de arrays cos taxterm asociados ao recurso dado e a info da taxonomía a maiores*/
  public function getTaxonomyAll( $termId ) {

    $resourceTaxAllModel =  new ResourceTaxonomyAllModel();
    $taxAllList = $resourceTaxAllModel->listItems(array( 'filters' => array( 'id' => $termId ) ));

    $taxTerms = array();
    while( $taxTerm = $taxAllList->fetch() ){
      $taxTerms[$taxTerm->getter('idTaxterm')] = $taxTerm->getAllData();
    }

    return( count( $taxTerms ) > 0 ? $taxTerms : false );
  }

  public function getCollectionsInfo( $resId ) {
    error_log( "ResourceController: getCollectionsInfo( $resId )" );
    $colInfo = array(
      'options' => array(),
      'values' => array()
    );

    $resourceCollectionModel =  new ResourceCollectionsModel();

    if( isset( $resId ) ) {
      $resCollectionList = $resourceCollectionModel->listItems(
        array(
          'filters' => array( 'resource' => $resId ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'CollectionModel' )
        )
      );

      while( $res = $resCollectionList->fetch() ){
        $collections = $res->getterDependence( 'collection', 'CollectionModel' );
        $colInfo[ 'options' ][ $res->getter( 'collection' ) ] = $collections[ 0 ]->getter( 'title', LANG_DEFAULT );
        $colInfo[ 'values' ][] = $res->getter( 'collection' );
      }
    }

    // error_log( "ResourceController: getCollectionsInfo = ". print_r( $colInfo, true ) );
    return ( count( $colInfo['values'] ) > 0 ) ? $colInfo : false;
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
      $relModel = new ResourceTaxonomytermModel();
      $relFilter = array( 'resource' => $baseId );
      if( is_numeric( $taxGroup ) ) {
        $relFilter[ 'TaxonomygroupModel.id' ] = $taxGroup;
      }
      else {
        $relFilter[ 'TaxonomygroupModel.idName' ] = $taxGroup;
      }
      $relPrevList = $relModel->listItems( array(
        'filters' => $relFilter,
        'affectsDependences' => array( 'TaxonomytermModel' ,'TaxonomygroupModel' ),
        'joinType' => 'RIGHT'
      ));
      if( $relPrevList ) {
        // estaban asignados antes
        $relPrevInfo = array();
        while( $relPrev = $relPrevList->fetch() ){
          $relPrevInfo[ $relPrev->getter( 'taxonomyterm' ) ] = $relPrev->getter( 'id' );
          if( $taxTermIds === false || !in_array( $relPrev->getter( 'taxonomyterm' ), $taxTermIds ) ){ // desasignar
            $relPrev->delete();
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

  private function setFormCollection( $form, $fieldName, $baseObj ) {
    $baseId = $baseObj->getter( 'id' );
    $formValues = $form->getFieldValue( $fieldName );
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
  public function getViewBlock( $resObj ) {
    error_log( "GeozzyResourceView: getViewBlock()" );

    $resBlock = $this->getResourceBlock( $resObj );

    $this->getRTypeCtrl( $resObj->getter( 'rTypeId' ) ); // TODO: Usar rTypeId

    if( $this->rTypeCtrl ) {
      error_log( 'GeozzyResourceView: rTypeCtrl->getViewBlock' );
      $rTypeBlock = $this->rTypeCtrl->getViewBlock( $resObj, $resBlock );
      if( $rTypeBlock ) {
        error_log( 'GeozzyResourceView: resBlock = rTypeBlock' );
        $resBlock = $rTypeBlock;
      }
    }

    return( $resBlock );
  } // function getViewBlock( $resObj )


  public function getResourceBlock( $resObj ) {
    error_log( "GeozzyResourceView: getResourceBlock()" );

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

    $collections = $this->getCollectionsInfo( $resObj->getter('id') );
    error_log( "collections = ". print_r( $collections, true ) );

    if( $collections ) {
      foreach( $collections[ 'values' ] as $collectionId ) {
        $collectionBlock = $this->getCollectionBlock( $collectionId );
        if( $collectionBlock ) {
          $template->addToBlock( 'collections', $collectionBlock );
        }
      }
    }

    $template->setTpl( 'resourceViewBlock.tpl', 'geozzy' );

    return( $template );
  } // function getResourceBlock( $resObj )


  public function getCollectionBlock( $collectionId ) {
    error_log( "GeozzyResourceView: getCollectionBlock()" );

    $template = false;

    /**
      Cargamos os datos da collection e metemolos no tpl para crear un bloque
      Empezado e parado...
      */

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

    /**
      PROBANDO (INI)
      */
    $template = new Template();
    $template->assign( 'title', 'Colección Num. '.$collectionId );
    $template->assign( 'shortDescription', 'Colección Num. '.$collectionId );
    $template->assign( 'image', '<p>'.__('None').'</p>' );
    $template->assign( 'collectionResources', 'Listado dos recursos da colección Num. '.$collectionId );
    /**
      PROBANDO (FIN)
      */

    $template->setTpl( 'resourceCollectionViewBlock.tpl', 'geozzy' );

    return( $template );
  } // function getCollectionBlock( $resObj )


} // class ResourceController
