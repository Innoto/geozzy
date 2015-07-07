<?php


class RTypeHotelController {

  private $defResCtrl = null;

  public function __construct( $defResCtrl = null ){
    error_log( 'RTypeHotelController::__construct' );

    $this->defResCtrl = $defResCtrl;
  }

  public function getRTypeValues( $resId ) {
    error_log( "RTypeHotelController: getRTypeValues()" );
    $valuesArray = false;

    if( $resId && is_integer( $resId ) ) {
      $valuesArray = false;
    }

    return $valuesArray;
  }


  /**
    Defino el formulario
  */
  public function manipulateForm( $form ) {
    error_log( "RTypeHotelController: manipulateForm()" );

    $rTypeFieldNames = array();

    $fieldsInfo = array(
      'hotelName' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Hotel title' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'hotelShortDescription' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Hotel short description' ) ),
        'rules' => array( 'maxlength' => '100' )
      )
    );

    $form->definitionsToForm( $fieldsInfo );

    // Valadaciones extra
    $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRTypeValues( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $form->setField( 'idHotel', array( 'type' => 'reserved', 'value' => null ) );
      $form->loadArrayValues( $valuesArray );
      // error_log( 'RTypeHotelController getFormObj: ' . print_r( $valuesArray, true ) );
    }

    // Add RType info to form
    foreach( $fieldsInfo as $fieldName => $info ) {
      if( isset( $info[ 'translate' ] ) && $info[ 'translate' ] ) {
        $rTypeFieldNames = array_merge( $rTypeFieldNames, $form->multilangFieldNames( $fieldName ) );
      }
      else {
        $rTypeFieldNames[] = $fieldName;
      }
    }
    // rTypeId ya existe
    $form->setField( 'rTypeName', array( 'type' => 'reserved', 'value' => 'rtypeHotel' ) );
    $form->setField( 'rTypeFieldNames', array( 'type' => 'reserved', 'value' => $rTypeFieldNames ) );
    $form->saveToSession();

    return( $rTypeFieldNames );
  } // function manipulateForm()



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
    Taxonomy/Topic methods
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



} // class RTypeHotelController
