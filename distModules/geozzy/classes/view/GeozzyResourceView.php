<?php
Cogumelo::load('coreView/View.php');



class GeozzyResourceView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
    filedata::autoIncludes();
    //user::autoIncludes();
  }

  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }



  /**
    Defino un formulario
  */
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "GeozzyResourceView: getFormObj()" );

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', __( 'Thank you' ) );
    $form->setSuccess( 'redirect', SITE_URL . 'admin#resource/list' );

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
        'params' => array( 'label' => __( 'Medium description' ), 'type' => 'textarea', 'htmlEditor' => 'true' )
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
      'value' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Extra information' ) ),
        'rules' => array( 'maxlength' => '1000' )
      )
    );

    //$this->arrayToForm( $form, $fieldsInfo, $form->langAvailable );
    $form->definitionsToForm( $fieldsInfo );

    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );

    // Temáticas asociadas
    $topicModel =  new TopicModel();
    $topic = $topicModel->listItems();
    $topics = array();
    while($n = $topic->fetch()){
      $topics[ $n->getter('id') ] = $n->getter('name', LANG_DEFAULT);
    }
    $form->setField( 'topics', array( 'type' => 'checkbox', 'options'=> $topics) );

    // Destacados asociados
    $taxTermModel =  new TaxonomyTermModel();
    $starredList = $taxTermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ), 'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ));
    $starred = array();
    while($star = $starredList->fetch()){
      $starred[ $star->getter('id') ] = $star->getter('name', LANG_DEFAULT);
    }
    $form->setField( 'starred', array( 'type' => 'checkbox', 'options'=> $starred) );

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


  /**
    Defino un formulario con su TPL como Bloque
  */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "GeozzyResourceView: getFormBlock()" );

    $langAvailable = false;
    $this->template->assign( 'JsLangAvailable', 'false' );
    $this->template->assign( 'JsLangDefault', 'false' );
    global $LANG_AVAILABLE;
    if( isset( $LANG_AVAILABLE ) && is_array( $LANG_AVAILABLE ) ) {
      $langAvailable = array_keys( $LANG_AVAILABLE );
      $langDefault = LANG_DEFAULT;
      $tmp = implode( "', '", $langAvailable );
      $this->template->assign( 'JsLangAvailable', "['".$tmp."']" );
      $this->template->assign( 'JsLangDefault', "'".LANG_DEFAULT."'" );
    }

    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $this->template->assign( 'formOpen', $form->getHtmpOpen() );

    $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function getFormBlock()



  /**
    Proceso formulario
  */
  public function actionResourceForm() {
    error_log( "GeozzyResourceView: actionResourceForm()" );

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

    $urlAlias = array();
    if( !$form->existErrors() ) {
      global $LANG_AVAILABLE;
      $elemIdForm = false;

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) ) {
        $elemIdForm = $valuesArray[ 'id' ];
        $valuesArray[ 'timeLastUpdate' ] = date( "Y-m-d H:i:s", time() );
        unset( $valuesArray[ 'image' ] );
      }

      // Validar URLs
      foreach( $LANG_AVAILABLE as $langId => $langValues ) {
        if( $valuesArray[ 'urlAlias_'.$langId ] !== '' ) {
          if( $urlError = $this->urlErrors( $elemIdForm, $langId, $valuesArray[ 'urlAlias_'.$langId ] ) ) {
            $form->addFieldRuleError( 'urlAlias_'.$langId, false, $urlError );
          }
          else {
            $urlAlias[ $langId ] = $valuesArray[ 'urlAlias_'.$langId ];
          }
        }
        else {
          $urlAlias[ $langId ] = null;
        }
      }
    }

    if( !$form->existErrors() ) {
      // error_log( print_r( $valuesArray, true ) );

      $recurso = new ResourceModel( $valuesArray );
      if( $recurso === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    $saveResult = false;
    $affectsDependences = false;
    $imageFile = $form->getFieldValue( 'image' );
    if( !$form->existErrors() && isset( $imageFile['status'] ) ) {
      switch( $imageFile['status'] ) {

        case 'LOADED':
          error_log( 'To Model: '.$imageFile['status'] );
          // error_log( 'To Model - fileInfo: '. print_r( $imageFile[ 'values' ], true ) );
          $affectsDependences = true;
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        case 'REPLACE':
          error_log( 'To Model: '.$imageFile['status'] );
          // error_log( 'To Model - fileInfo: '. print_r( $imageFile[ 'values' ], true ) );
          // error_log( 'To Model - fileInfoPrev: '. print_r( $imageFile[ 'prev' ], true ) );
          $affectsDependences = true;

          // TODO: Falta eliminar o ficheiro anterior
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        case 'DELETE':
          error_log( 'To Model: '.$imageFile['status'] );
          // error_log( 'To Model - fileInfo: '. print_r( $imageFile[ 'values' ], true ) );

          // Apaño
          $recurso->setter( 'image', null );

          /* PENDIENTE
          $affectsDependences = true;
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile['values'] ) );
          */
          break;
        case 'EXIST':
          error_log( 'To Model: '.$imageFile['status'] );
          // En principio, si se mantiene la misma, no se hace nada
          break;
        default:
          error_log( 'To Model: DEFAULT='.$imageFile['status'] );
          break;
      }
    }

    // Procesamos o listado de temáticas asociadas
    if( !$form->existErrors()) {
      $elemId = $recurso->getter( 'id' );
      $newTopics = $form->getFieldValue( 'topics' );

      if( $newTopics !== false && !is_array($newTopics) ) {
        $newTopics = array($newTopics);
      }

      $resourceTopicModel = new ResourceTopicModel();
      $resourceTopicList = $resourceTopicModel->listItems(
        array('filters' => array('resource' => $elemId)) );

      if( $resourceTopicList ) {
        // estaban asignados antes
        while($oldTopic = $resourceTopicList->fetch()){
          $oldTopics[$oldTopic->getter('topic')] = $oldTopic->getter('topic');
          if( $newTopics === false || !in_array( $oldTopic->getter('topic'), $newTopics ) ) {
            $oldTopic->delete(); // desasignar
          }
        }
      }

      if( $newTopics !== false ) {
        if( !isset($oldTopics) ) {
          foreach( $newTopics as $topic ) {
            $recurso->setterDependence( 'id',
              new ResourceTopicModel( array('resource' => $elemId, 'topic' => $topic)) );
            $affectsDependences = true;
          }
        }
        else {
          // non estaban asignados antes
          foreach( $newTopics as $topic ) {
            if( !in_array($topic,$oldTopics) ) { //asignar
              $recurso->setterDependence( 'id',
                new ResourceTopicModel( array('resource' => $elemId, 'topic' => $topic)) );
              $affectsDependences = true;
            }
          }
        }
      }
    }

    // Procesamos o listado de destacados asociados
    if( !$form->existErrors()) {
      $newStarred = $form->getFieldValue( 'starred' );

      if( $newStarred !== false && !is_array($newStarred) ) {
        $newStarred = array($newStarred);
      }

      $resourceTaxonomytermModel = new ResourceTaxonomytermModel();

      $starredListPrev = $resourceTaxonomytermModel->listItems( array(
        'filters' => array( 'TaxonomygroupModel.idName' => 'starred', 'resource' => $elemId ),
        'affectsDependences' => array('TaxonomygroupModel'),
        'joinType' => 'RIGHT' ));

      // estaban asignados antes
      if ($starredListPrev){
        while($oldStar = $starredListPrev->fetch()){
          $oldStarred[$oldStar->getter('taxonomyterm')] = $oldStar->getter('taxonomyterm');
          if( $newStarred === false || !in_array( $oldStar->getter('taxonomyterm'), $newStarred ) ){ // desasignar
            $oldStar->delete();
          }
        }
      }

      if( $newStarred !== false ) {
        if( !isset($oldStarred) ){
          foreach( $newStarred as $star ) {
            $recurso->setterDependence( 'id',
              new ResourceTaxonomytermModel( array('resource' => $elemId, 'taxonomyterm' => $star)) );
            $affectsDependences = true;
          }
        }
        else {
          // non estaban asignados antes
          foreach( $newStarred as $star ) {
            if( !in_array($star,$oldStarred) ) { //asignar
              $recurso->setterDependence( 'id',
                new ResourceTaxonomytermModel( array('resource' => $elemId, 'taxonomyterm' => $star)) );
              $affectsDependences = true;
            }
          }
        }
      }
    }

    if( !$form->existErrors()) {
      $extraDataArray = array('resource' => $elemId, 'name' => 'value');
      foreach ($LANG_AVAILABLE as $key => $lang){
        $extraDataArray['value_'.$key ] = $form->getFieldValue( 'value_'.$key );
      }
      $recurso->setterDependence( 'id',
          new extraDataModel( $extraDataArray ) );
          $affectsDependences = true;
    }


    if( !$form->existErrors()) {
      $saveResult = $recurso->save( array( 'affectsDependences' => $affectsDependences ) );
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }


    if( !$form->existErrors() ) {

      foreach( $urlAlias as $langId => $url ) {
        if( $this->setUrl( $elemId, $langId, $url ) === false ) {
          $form->addFieldRuleError( 'urlAlias_'.$langId, false, __( 'Error setting URL alias' ) );
          break;
        }
      }

    }

    if( !$form->existErrors() ) {
      echo $form->jsonFormOk();
    }
    else {
      $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
      echo $form->jsonFormError();
    }
  } // function actionResourceForm()


  private function urlErrors( $resId, $langId, $urlAlias ) {
    // error_log( "urlErrors( $resId, $langId, $urlAlias )" );
    $error = false;

    //$error = 'URL Alias incompleto';

    return $error;
  }


  private function setUrl( $resId, $langId, $urlAlias ) {
    // error_log( "setUrl( $resId, $langId, $urlAlias )" );
    $result = true;

    if( !isset( $urlAlias ) || $urlAlias === false || $urlAlias === '' ) {
      $urlAlias = '/recurso/'.$resId;
    }

    $aliasArray = array(
      'http' => 0,
      'canonical' => 1,
      'lang' => $langId,
      'urlFrom' => $urlAlias,
      'urlTo' => null,
      'resource' => $resId
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


} // class ResourceView extends Vie
