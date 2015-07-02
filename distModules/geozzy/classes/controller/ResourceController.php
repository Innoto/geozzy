<?php


class ResourceController {

  public function __construct() {
    error_log( 'ResourceController::__construct' );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();
  }


  /**
    Defino un formulario
  */
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "GeozzyResourceView: getFormObj()" );

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', __( 'Thank you' ) );
    $form->setSuccess( 'redirect', SITE_URL . 'admin#resource/list' );


    // Collections disponibles
    $resOptions = array();
    $resValues = array();
    $collectionModel =  new CollectionModel();
    if($valuesArray['id']){
      $collectionList = $collectionModel->listItems(
        array( 'filters' => array(
          'ResourceCollectionsModel.resource' => $valuesArray['id'] ),
          'affectsDependences' => array('ResourceCollectionsModel'),
          'joinType' => 'RIGHT'
        )
      );
      while( $res = $collectionList->fetch() ){
        $resOptions[ $res->getter( 'id' ) ] = $res->getter( 'title', LANG_DEFAULT );
        $resValues[] = $res->getter( 'id' );
      }
      $valuesArray['collections'] = $resValues;
    }

    // 'image' 'type'=>'FOREIGN','vo' => 'FiledataModel','key' => 'id'
    // 'loc'   'type' => 'GEOMETRY'
    $fieldsInfo = array(
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
          'value' => '<p>ola mundo<br />...probando ;-)</p>', 'htmlEditor' => 'true' )
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
      'typeResource' => array(
        'params' => array( 'type' => 'reserved' )
      ),
      'collections' => array(
        'params' => array( 'label' => __( 'Collections' ), 'type' => 'select', 'id' => 'resourceCollections',
        'multiple' => true, 'options'=> $resOptions )
      ),
      'addCollections' => array(
        'params' => array( 'id' => 'resourceAddCollection', 'type' => 'button', 'value' => __( 'Add Collection' ))
      ),
      'published' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => 'Publicado' ))
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );

    // Temáticas asociadas
    $topicModel =  new TopicModel();
    $topic = $topicModel->listItems();
    $topics = array();
    while($n = $topic->fetch()){
      $topics[ $n->getter('id') ] = $n->getter('name', LANG_DEFAULT);
    }
    $form->setField( 'topics', array( 'type' => 'checkbox', 'label' => __( 'Topics' ), 'options'=> $topics) );

    // Destacados asociados
    $taxTermModel =  new TaxonomyTermModel();
    $starredList = $taxTermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ),
      'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ));
    $starred = array();
    while($star = $starredList->fetch()){
      $starred[ $star->getter('id') ] = $star->getter('name', LANG_DEFAULT);
    }
    $form->setField( 'starred', array( 'type' => 'checkbox', 'label' => __( 'Starred' ), 'options'=> $starred) );

    //Si es una edicion, añadimos el ID y cargamos los datos
    // error_log( 'GeozzyResourceView getFormObj: ' . print_r( $valuesArray, true ) );
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
      $form->loadArrayValues( $valuesArray );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => __( 'Send' ), 'class' => 'gzzAdminToMove' ) );

    // Una vez que lo tenemos definido, guardamos el form en sesion
    $form->saveToSession();

    return( $form );
  } // function getFormObj()



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



  public function resFormRevalidate( $form ) {

    $this->evalFormUrlAlias( $form, 'urlAlias' );
  }



  public function resFormProcess( $form ) {
    $resource = false;

    if( !$form->existErrors() ) {
      $useraccesscontrol = new UserAccessController();
      $user = $useraccesscontrol->getSessiondata();

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) ) {
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

    if( !$form->existErrors() && $form->isFieldDefined( 'starred' ) ) {
      $this->setFormTaxStarred( $form, 'starred', $resource );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'datoExtra1' ) ) {
      $this->setFormExtraData( $form, 'datoExtra1', 'datoExtra1', $resource );
    }
    if( !$form->existErrors() && $form->isFieldDefined( 'datoExtra2' ) ) {
      $this->setFormExtraData( $form, 'datoExtra2', 'datoExtra2', $resource );
    }

    if( !$form->existErrors() && $form->isFieldDefined( 'urlAlias' ) ) {
      $this->setFormUrlAlias( $form, 'urlAlias', $resource );
    }
    /**
      DEPENDENCIAS (END)
    */

    return $resource;
  }



  public function resFormSucess( $form, $resource ) {

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
    Proceso formulario
  */
  public function actionResourceForm() {
    error_log( "GeozzyResourceView: actionResourceForm()" );

    // Se construye el formulario con sus datos y se realizan las validaciones que contiene
    $form = $this->resFormLoad();

    if( !$form->existErrors() ) {
      // Validaciones extra previas a usar los datos del recurso base
      $this->resFormRevalidate( $form );
    }

    // Opcional: Validaciones extra previas de elementos externos al recurso base

    if( !$form->existErrors() ) {
      // Creación-Edición-Borrado de los elementos del recurso base
      $resource = $this->resFormProcess( $form );
    }

    // Opcional: Creación-Edición-Borrado de los elementos externos al recurso base

    if( !$form->existErrors()) {
      // Volvemos a guardar el recurso por si ha sido alterado por alguno de los procesos previos
      $saveResult = $resource->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    // Enviamos el OK-ERROR a la BBDD y al formulario
    $this->resFormSucess( $form, $resource );
  } // function actionResourceForm()





  /**
    Filedata methods
  */
  private function setFormFiledata( $form, $fieldName, $colName, $resObj ) {
    $fileField = $form->getFieldValue( $fieldName );
    $fileFieldValues = false;
    $error = false;

    if( isset( $fileField['status'] ) ) {

      error_log( 'To Model - fileInfo: '. print_r( $fileField[ 'values' ], true ) );

      switch( $fileField['status'] ) {
        case 'LOADED':
          error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = $fileField[ 'values' ];
          break;
        case 'REPLACE':
          error_log( 'To Model: '.$fileField['status'] );
          // error_log( 'To Model - fileInfoPrev: '. print_r( $fileField[ 'prev' ], true ) );
          /**
            TODO: Falta eliminar o ficheiro anterior
          */
          $fileFieldValues = $fileField[ 'values' ];
          break;
        case 'DELETE':
          error_log( 'To Model: '.$fileField['status'] );
          $fileFieldValues = null;
          /**
            TODO: Falta eliminar o ficheiro anterior
          */
          break;
        case 'EXIST':
          error_log( 'To Model: '.$fileField['status'] );
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
        $error = 'File status desconocido';
      }
    }
    else {
      $error = 'File status desconocido';
    }

    if( $error ) {
      $form->addFieldRuleError( $fieldName, false, $error );
    }
  }


  /**
    ExtraData methods
  */
  private function setFormExtraData( $form, $fieldName, $colName, $baseObj ) {
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

  /**
    Taxonomy methods
  */
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

  private function setFormTaxStarred( $form, $fieldName, $baseObj ) {
    $baseId = $baseObj->getter( 'id' );
    $formValues = $form->getFieldValue( $fieldName );
    $relPrevInfo = false;

    if( $formValues !== false && !is_array( $formValues ) ) {
      $formValues = array( $formValues );
    }

    // Si estamos editando, repasamos y borramos relaciones sobrantes
    if( $baseId ) {
      $relModel = new ResourceTaxonomytermModel();
      $relPrevList = $relModel->listItems( array(
        'filters' => array( 'TaxonomygroupModel.idName' => 'starred', 'resource' => $baseId ),
        'affectsDependences' => array( 'TaxonomygroupModel' ),
        'joinType' => 'RIGHT' ));
      if( $relPrevList ) {
        // estaban asignados antes
        $relPrevInfo = array();
        while( $relPrev = $relPrevList->fetch() ){
          $relPrevInfo[ $relPrev->getter( 'taxonomyterm' ) ] = $relPrev->getter( 'id' );
          if( $formValues === false || !in_array( $relPrev->getter( 'taxonomyterm' ), $formValues ) ){ // desasignar
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
    $resId = $resObj->getter('id');
    foreach( $form->langAvailable as $langId ) {
      $url = $form->getFieldValue( $fieldName.'_'.$langId );
      if( $this->setUrl( $resId, $langId, $url ) === false ) {
        $form->addFieldRuleError( $fieldName.'_'.$langId, false, __( 'Error setting URL alias' ) );
        break;
      }
    }
  }

  private function setUrl( $resId, $langId, $urlAlias ) {
    // error_log( "setUrl( $resId, $langId, $urlAlias )" );
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
      // error_log( 'setUrl: Xa existe - '.$elem->getter( 'id' ) );
      $aliasArray[ 'id' ] = $elem->getter( 'id' );
    }

    $elemModel = new UrlAliasModel( $aliasArray );
    if( $elemModel->save() === false ) {
      $result = false;
      // error_log( 'setUrl: ERROR gardando a url' );
    }
    else {
      $result = $elemModel->getter( 'id' );
      // error_log( 'setUrl: Creada/Actualizada - '.$result );
    }

    return $result;
  }



} // class ResourceController
