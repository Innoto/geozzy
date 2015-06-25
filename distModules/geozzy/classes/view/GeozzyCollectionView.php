<?php
Cogumelo::load('coreView/View.php');



class GeozzyCollectionView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
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
    // error_log( "GeozzyCollectionView: getFormObj()" );

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', __( 'Thank you' ) );
    // $form->setSuccess( 'redirect', SITE_URL . 'admin#collection/list' );

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
        'params' => array( 'label' => __( 'Image' ), 'type' => 'file', 'id' => 'imgCollection',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => '/imgCollection' ),
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
      'typeCollection' => array(
        'params' => array( 'type' => 'reserved' )
      ),
      'published' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => 'Publicado' ))
      )
    );

    //$this->arrayToForm( $form, $fieldsInfo, $form->langAvailable );
    $form->definitionsToForm( $fieldsInfo );

    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );

    // Temáticas asociadas
    $topicModel =  new TopicModel();
    $topic = $topicModel->listItems();
    $topics = array();
    while( $n = $topic->fetch() ){
      $topics[ $n->getter('id') ] = $n->getter('name', LANG_DEFAULT);
    }
    $form->setField( 'topics', array( 'type' => 'checkbox', 'label' => __( 'Topics' ), 'options'=> $topics) );

    //Si es una edicion, añadimos el ID y cargamos los datos
    // error_log( 'GeozzyCollectionView getFormObj: ' . print_r( $valuesArray, true ) );
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
    // error_log( "GeozzyCollectionView: getFormBlock()" );

    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $this->template->assign( 'formOpen', $form->getHtmpOpen() );

    $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'collectionFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function getFormBlock()



  /**
    Proceso formulario
  */
  public function actionCollectionForm() {
    error_log( "GeozzyCollectionView: actionCollectionForm()" );

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

    if( !$form->existErrors() ) {
      global $LANG_AVAILABLE;
      $elemIdForm = false;

      $useraccesscontrol = new UserAccessController();
      $user = $useraccesscontrol->getSessiondata();

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) ) {
        $elemIdForm = $valuesArray[ 'id' ];
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
      $recurso = new CollectionModel( $valuesArray );
      if( $recurso === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }

    $saveResult = false;
    $affectsDependences = false;
    $imageFile = $form->getFieldValue( 'image' );
    if( !$form->existErrors() && isset( $imageFile['status'] ) ) {

      error_log( 'To Model - fileInfo: '. print_r( $imageFile[ 'values' ], true ) );

      switch( $imageFile['status'] ) {

        case 'LOADED':
          error_log( 'To Model: '.$imageFile['status'] );
          $affectsDependences = true;
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        case 'REPLACE':
          error_log( 'To Model: '.$imageFile['status'] );
          // error_log( 'To Model - fileInfoPrev: '. print_r( $imageFile[ 'prev' ], true ) );
          $affectsDependences = true;

          // TODO: Falta eliminar o ficheiro anterior
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        case 'DELETE':
          error_log( 'To Model: '.$imageFile['status'] );

          // Apaño
          $recurso->setter( 'image', null );

          /* TODO
          $affectsDependences = true;
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile['values'] ) );
          */
          break;
        case 'EXIST':
          error_log( 'To Model: '.$imageFile['status'] );
          $affectsDependences = true;
          $recurso->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
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

      $collectionTopicModel = new CollectionTopicModel();
      $collectionTopicList = $collectionTopicModel->listItems(
        array('filters' => array('collection' => $elemId)) );

      if( $collectionTopicList ) {
        // estaban asignados antes
        while($oldTopic = $collectionTopicList->fetch()){
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
              new CollectionTopicModel( array('collection' => $elemId, 'topic' => $topic)) );
            $affectsDependences = true;
          }
        }
        else {
          // non estaban asignados antes
          foreach( $newTopics as $topic ) {
            if( !in_array($topic,$oldTopics) ) { //asignar
              $recurso->setterDependence( 'id',
                new CollectionTopicModel( array('collection' => $elemId, 'topic' => $topic)) );
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

      $collectionTaxonomytermModel = new CollectionTaxonomytermModel();

      $starredListPrev = $collectionTaxonomytermModel->listItems( array(
        'filters' => array( 'TaxonomygroupModel.idName' => 'starred', 'collection' => $elemId ),
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
              new CollectionTaxonomytermModel( array('collection' => $elemId, 'taxonomyterm' => $star)) );
            $affectsDependences = true;
          }
        }
        else {
          // non estaban asignados antes
          foreach( $newStarred as $star ) {
            if( !in_array($star,$oldStarred) ) { //asignar
              $recurso->setterDependence( 'id',
                new CollectionTaxonomytermModel( array('collection' => $elemId, 'taxonomyterm' => $star)) );
              $affectsDependences = true;
            }
          }
        }
      }
    }

    if( !$form->existErrors()) {
      $extraDataArray1 = array('collection' => $elemId, 'name' => 'datoExtra1');
      foreach( $form->langAvailable as $langId ) {
        $extraDataArray1['value_'.$langId ] = $form->getFieldValue( 'datoExtra1_'.$langId );
      }
      $recurso->setterDependence( 'id', new extraDataModel( $extraDataArray1 ) );
      $affectsDependences = true;

      $extraDataArray2 = array('collection' => $elemId, 'name' => 'datoExtra2');
      foreach( $form->langAvailable as $langId ) {
        $extraDataArray2['value_'.$langId ] = $form->getFieldValue( 'datoExtra2_'.$langId );
      }
      $recurso->setterDependence( 'id', new extraDataModel( $extraDataArray2 ) );
      $affectsDependences = true;
    }


    if( !$form->existErrors()) {
      $saveResult = $recurso->save( array( 'affectsDependences' => $affectsDependences ) );
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso.','formError' );
      }
    }


    if( !$form->existErrors() ) {
      echo $form->jsonFormOk();
    }
    else {
      $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
      echo $form->jsonFormError();
    }
  } // function actionCollectionForm()

} // class CollectionView extends Vie
