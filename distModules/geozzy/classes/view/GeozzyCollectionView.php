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



/*    $collectionResourcesModel =  new CollectionResourcesModel();

    if( isset( $valuesArray[ 'id' ] ) ) {
      $colResourcesList = $collectionResourcesModel->listItems(
        array(
          'filters' => array( 'collection' => $valuesArray[ 'id' ] ),
          'order' => array( 'weight' => 1 ),
          'affectsDependences' => array( 'ResourceModel' )
        )
      );

      while( $res = $colResourcesList->fetch() ){
        $resources = $res->getterDependence( 'resource', 'ResourceModel' );
        //$colInfo[ 'options' ][ $res->getter( 'collection' ) ] = $collections[ 0 ]->getter( 'title', LANG_DEFAULT );
        ///$colInfo[ 'values' ][] = $res->getter( 'collection' );


        $elOpt = array(
          'value' => $res->getter( 'resource' ),
          'text' => $resources[ 0 ]->getter( 'title', LANG_DEFAULT ),
          'data-image' => '/cgmlformfilews/'. $resources[ 0 ]->getter( 'image' )
        );
        $resOptions[ $res->getter( 'resource' ) ] = $elOpt;

      }
    }
*/



    // Recursos disponibles

    $resourceModel =  new ResourceModel();
    $elemList = $resourceModel->listItems();

    $resOptions = array();
    while( $res = $elemList->fetch() ){

      $elOpt = array(
        'value' => $res->getter( 'id' ),
        'text' => $res->getter( 'title', LANG_DEFAULT ),
        'data-image' => '/cgmlformfilews/'.$res->getter( 'image' )
      );
      $resOptions[ $res->getter( 'id' ) ] = $elOpt;
    }

    $fieldsInfo = array(
      'title' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Title' ) ),
        'rules' => array( 'maxlength' => '240' )
      ),
      'shortDescription' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Short description' ) ),
        'rules' => array( 'maxlength' => '240' )
      ),
      'description' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Description' ), 'type' => 'textarea',
          'value' => '<p>ola mundo<br />...probando ;-)</p>', 'htmlEditor' => 'true' )
      ),
      'image' => array(
        'params' => array( 'label' => __( 'Image' ), 'type' => 'file', 'id' => 'imgCollection',
        'placeholder' => 'Escolle unha imaxe', 'destDir' => '/imgCollection' ),
        'rules' => array( 'minfilesize' => '1024', 'maxfilesize' => '100000', 'accept' => 'image/jpeg' )
      ),
      'resources' => array(
        'params' => array( 'label' => __( 'Resources' ), 'type' => 'select', 'id' => 'collResources',
        'class' => 'cgmMForm-order',
        'multiple' => true, 'options'=> $resOptions ),
        'rules' => array( 'required' => true )
      )
    );

    //$this->arrayToForm( $form, $fieldsInfo, $form->langAvailable );
    $form->definitionsToForm( $fieldsInfo );
    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );

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
  public function actionForm() {
    error_log( "GeozzyCollectionView: actionForm()" );

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

      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) ) {
        $elemIdForm = $valuesArray[ 'id' ];
        unset( $valuesArray[ 'image' ] );
      }
    }

    if( !$form->existErrors() ) {
      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $collection = new CollectionModel( $valuesArray );
      if( $collection === false ) {
        $form->addFormError( 'No se ha podido guardar el collection.','formError' );
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
          $collection->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        case 'REPLACE':
          error_log( 'To Model: '.$imageFile['status'] );
          // error_log( 'To Model - fileInfoPrev: '. print_r( $imageFile[ 'prev' ], true ) );
          $affectsDependences = true;

          // TODO: Falta eliminar o ficheiro anterior
          $collection->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        case 'DELETE':
          error_log( 'To Model: '.$imageFile['status'] );

          // Apaño
          $collection->setter( 'image', null );

          /* TODO
          $affectsDependences = true;
          $collection->setterDependence( 'image', new FiledataModel( $imageFile['values'] ) );
          */
          break;
        case 'EXIST':
          error_log( 'To Model: '.$imageFile['status'] );
          $affectsDependences = true;
          $collection->setterDependence( 'image', new FiledataModel( $imageFile[ 'values' ] ) );
          break;
        default:
          error_log( 'To Model: DEFAULT='.$imageFile['status'] );
          break;
      }
    }

    // Procesamos o listado de recursos asociados
    if( !$form->existErrors()) {
      $elemId = $collection->getter( 'id' );
      $newResources = $form->getFieldValue( 'resources' );
      $oldResources = false;

      if( $newResources !== false && !is_array($newResources) ) {
        $newResources = array($newResources);
      }

      // Si estamos editando, repasamos y borramos recursos sobrantes
      if( $elemId ) {
        $CollectionResourcesModel = new CollectionResourcesModel();
        $collectionResourceList = $CollectionResourcesModel->listItems(
          array('filters' => array('collection' => $elemId)) );

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
        $affectsDependences = true;
        $weight = 0;
        foreach( $newResources as $resource ) {
          $weight++;
          if( $oldResources === false || !isset( $oldResources[ $resource ] ) ) {
            $collection->setterDependence( 'id',
              new CollectionResourcesModel( array( 'weight' => $weight,
                'collection' => $elemId, 'resource' => $resource)) );
          }
          else {
            $collection->setterDependence( 'id',
              new CollectionResourcesModel( array( 'id' => $oldResources[ $resource ],
                'weight' => $weight, 'collection' => $elemId, 'resource' => $resource))
            );
          }
        }
      }
    }


    /*
    if( !$form->existErrors()) {
      $extraDataArray1 = array('collection' => $elemId, 'name' => 'datoExtra1');
      foreach( $form->langAvailable as $langId ) {
        $extraDataArray1['value_'.$langId ] = $form->getFieldValue( 'datoExtra1_'.$langId );
      }
      $collection->setterDependence( 'id', new extraDataModel( $extraDataArray1 ) );
      $affectsDependences = true;

      $extraDataArray2 = array('collection' => $elemId, 'name' => 'datoExtra2');
      foreach( $form->langAvailable as $langId ) {
        $extraDataArray2['value_'.$langId ] = $form->getFieldValue( 'datoExtra2_'.$langId );
      }
      $collection->setterDependence( 'id', new extraDataModel( $extraDataArray2 ) );
      $affectsDependences = true;
    }
    */


    if( !$form->existErrors()) {
      $saveResult = $collection->save( array( 'affectsDependences' => $affectsDependences ) );
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el collection.','formError' );
      }else{
        $form->setSuccess( 'jsEval', ' successCollectionForm( { id : "'.$collection->getter('id').'", title: "'.$collection->getter('title_'.$form->langDefault).'" });' );
      }
    }

    $form->sendJsonResponse();

  } // function actionCollectionForm()

} // class CollectionView extends Vie
