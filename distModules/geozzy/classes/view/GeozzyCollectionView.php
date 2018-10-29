<?php
Cogumelo::load('coreView/View.php');
geozzy::load('controller/ResourceController.php');


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

    // $form->setSuccess( 'accept', __( 'Thank you' ) );
    // $form->setSuccess( 'redirect', SITE_URL . 'admin#collection/list' );
    // Recursos disponibles
    $valueCollectionId = ( array_key_exists('id', $valuesArray ) ) ? $valuesArray['id'] : false;
    $valueCollectionType = ( array_key_exists('collectionType', $valuesArray ) ) ? $valuesArray['collectionType'] : 'base';
    $valueRTypeFilterParent = ( array_key_exists('filterRTypeParent', $valuesArray ) ) ? $valuesArray['filterRTypeParent'] : false;

    $resOptions = array();
    $elemList = $this->getAvailableResources( $valueCollectionId, $valueCollectionType, $valueRTypeFilterParent );

    if( $elemList && is_array($elemList) && count($elemList) > 0 ) {
      $resControl = new ResourceController();
      foreach( $elemList as $key => $res ) {
        $thumbSettings = array(
          'profile' => 'squareCut',
          'imageId' => $res->getter( 'image' ),
          'imageName' => $res->getter( 'image' ).'.jpg',
          'imageAKey' => $res->getter( 'imageAKey' )
        );
        if( !empty($res->getter( 'rextUrlUrl' )) ){
          $thumbSettings['url'] = $res->getter( 'rextUrlUrl' );
        }
        $elOpt = array(
          'value' => $res->getter( 'id' ),
          'text' => $res->getter( 'title', Cogumelo::getSetupValue( 'lang:default' ) ),
          'data-image' => $resControl->getResourceThumbnail( $thumbSettings )
        );

        $resOptions[ $res->getter( 'id' ) ] = $elOpt;
      }
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
        'params' => array( 'label' => __( 'Description' ), 'type' => 'textarea', 'htmlEditor' => 'true' )
      ),
      'resources' => array(
        'params' => array(
          'label' => __( 'Resources' ),
          'type' => 'select', 'id' => 'collResources',
          'class' => 'cgmMForm-order',
          'multiple' => true,
          'options'=> $resOptions
        ),
        'rules' => array( 'required' => true )
      ),
      'collectionType' => array(
        'params' => array('type' => 'reserved', 'value' => 'base' )
      ),
      'collectionSelect' => array(
        'params' => array('type' => 'reserved' )
      )
    );

    /*OLD*/
    // $setupConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$valueRTypeFilterParent.':'.$valueCollectionType.':manualCreate' );
    // $widgetConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$valueRTypeFilterParent.':'.$valueCollectionType.':widget' );
    // if( !$setupConf || count($setupConf) === 0 ){
    //   $setupConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:'.$valueCollectionType.':manualCreate' );
    //   $widgetConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:'.$valueCollectionType.':widget' );
    //   if( !$setupConf || count($setupConf) === 0 ) {
    //     $setupConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:default:manualCreate' );
    //     $widgetConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:default:widget' );
    //   }
    // }

    $setupConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:default:manualCreate' );
    $widgetConf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:default:widget' );
    $setupConfTemp = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:'.$valueCollectionType.':manualCreate' );
    $widgetConfTemp = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:'.$valueCollectionType.':widget' );
    $setupConf = ( !empty($setupConfTemp) ) ? $setupConfTemp : $setupConf;
    $widgetConf = ( !empty($widgetConfTemp) ) ? $widgetConfTemp : $widgetConf;
    $setupConfTemp = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$valueRTypeFilterParent.':'.$valueCollectionType.':manualCreate' );
    $widgetConfTemp = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$valueRTypeFilterParent.':'.$valueCollectionType.':widget' );
    $setupConf = ( !empty($setupConfTemp) ) ? $setupConfTemp : $setupConf;
    $widgetConf = ( !empty($widgetConfTemp) ) ? $widgetConfTemp : $widgetConf;

    if( in_array("rtypeFile", $setupConf) ){
      $fieldsInfo['addResourceLocal'] = array(
        'params' => array( 'id' => 'addResourceLocal', 'class' => 'widgetCollectionBtn', 'type' => 'button', 'value' => __( 'Upload multimedia ' ))
      );
      if( in_array("fileMultiple", $widgetConf) ){
        $fieldsInfo['addResLocalMultiple'] = array(
          'params' => array( 'id' => 'addResLocalMultiple', 'class' => 'widgetCollectionBtn', 'type' => 'button', 'value' => __( 'Upload multiple files' ))
        );
      }
    }
    if( in_array("rtypeUrl", $setupConf) ){
      $fieldsInfo['addResourceExterno'] = array(
        'params' => array( 'id' => 'addResourceExternal', 'class' => 'widgetCollectionBtn', 'type' => 'button', 'value' => __( 'Add external link' ))
      );
    }

    $fieldsInfo['image'] = array(
      'params' => array( 'label' => __( 'Descriptive image of the gallery (opcional)' ), 'type' => 'file', 'id' => 'imgCollection',
      'placeholder' => 'Escolle unha imaxe', 'destDir' => CollectionModel::$cols['image']['uploadDir']),
      'rules' => array( 'minfilesize' => '1024', 'maxfilesize' => '2097152', 'accept' => 'image/jpeg,image/png' )
    );

    //$this->arrayToForm( $form, $fieldsInfo, $form->langAvailable );
    $form->definitionsToForm( $fieldsInfo );
    $form->setValidationRule( 'title_'.$form->langDefault, 'required' );
    $form->removeValidationRule( 'resources', 'inArray' );

    //Si es una edicion, añadimos el ID y cargamos los datos
    // error_log( 'GeozzyCollectionView getFormObj: ' . print_r( $valuesArray, true ) );
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
      $form->loadArrayValues( $valuesArray );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => __( 'Save' ), 'class' => 'gzzAdminToMove' ) );

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
    // error_log( "GeozzyCollectionView: actionForm()" );

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
      // $elemIdForm = false;

      $valuesArray = $form->getValuesArray();

      if( !empty( $valuesArray[ 'id' ] ) ) {
        // $elemIdForm = $valuesArray[ 'id' ];
        unset( $valuesArray[ 'image' ] );
      }
    }

    if( !$form->existErrors() ) {
      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $collection = new CollectionModel( $valuesArray );
      if( $collection !== false ) {
        $collection->save();
      }
      else {
        $form->addFormError( 'No se ha podido guardar el collection.','formError' );
      }
    }

    $affectsDependences = false;

    $imageFile = $form->getFieldValue( 'image' );
    if( !$form->existErrors() && isset( $imageFile['status'] ) ) {

      $filedataCtrl = new FiledataController();
      $newFiledataObj = false;

      switch( $imageFile['status'] ) {
        case 'LOADED':
          $imageFileValues = $imageFile['values'];
          $newFiledataObj = $filedataCtrl->createNewFile( $imageFileValues );
          // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
          if( $newFiledataObj ) {
            $collection->setter( 'image', $newFiledataObj->getter( 'id' ) );
            $collection->save();
          }
          break;
        case 'REPLACE':
          // error_log( 'To Model - fileInfoPrev: '. print_r( $imageFile[ 'prev' ], true ) );
          $imageFileValues = $imageFile['values'];
          $prevFiledataId = $collection->getter( 'image' );
          $newFiledataObj = $filedataCtrl->createNewFile( $imageFileValues );
          // error_log( 'To Model - newFiledataObj ID: '.$newFiledataObj->getter( 'id' ) );
          if( $newFiledataObj ) {
            $collection->setter( 'image', $newFiledataObj->getter( 'id' ) );
            $collection->save();
            // error_log( 'To Model - deleteFile ID: '.$prevFiledataId );
            $filedataCtrl->deleteFile( $prevFiledataId );
          }
          break;
        case 'DELETE':
          if( $prevFiledataId = $collection->getter( 'image' ) ) {
            // error_log( 'To Model - prevFiledataId: '.$prevFiledataId );
            $filedataCtrl->deleteFile( $prevFiledataId );
            $collection->setter( 'image', null );
            $collection->save();
          }
          break;
        case 'EXIST':
          $imageFileValues = $imageFile[ 'values' ];
          if( $prevFiledataId = $collection->getter( 'image' ) ) {
            // error_log( 'To Model - UPDATE prevFiledataId: '.$prevFiledataId );
            $filedataCtrl->updateInfo( $prevFiledataId, $imageFileValues );
          }
          break;
        default:
          // error_log( 'To Model: DEFAULT='.$imageFile['status'] );
          break;
      }

    }

    // Procesamos o listado de recursos asociados
    if( !$form->existErrors()) {
      $elemId = $collection->getter( 'id' );
      $newResources = $form->getFieldValue( 'resources' );

      $resControl = new ResourceController();
      $affectsDependences = true;
      $collection = $resControl->setResourcesToCollection( $elemId , $newResources );
    }

    if( !$form->existErrors()) {
      if( $collection->save( array( 'affectsDependences' => $affectsDependences ) ) ) {
        $form->addFormError( 'No se ha podido guardar el collection.','formError' );
      }
      else{
        $form->setSuccess( 'jsEval', ' successCollectionForm( { id : "'.$collection->getter('id').'", title: "'.$collection->getter('title_'.$form->langDefault).'", collectionType: "'.$collection->getter('collectionType').'", collectionSelect: "'.$form->getFieldValue('collectionSelect').'" });' );
      }
    }

    $form->sendJsonResponse();

  } // function actionCollectionForm()
  public function getConfCollection( $collectionType, $filterRTypeParent ){
    $conf = false;
    if( $filterRTypeParent && class_exists( $filterRTypeParent ) ) {
      $conf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:'.$filterRTypeParent.':'.$collectionType );
    }
    if( !$conf || count($conf) === 0 ){
      $conf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:'.$collectionType );
      if( !$conf || count($conf) === 0 ) {
        $conf = Cogumelo::getSetupValue( 'mod:geozzy:resource:collectionTypeRules:default:default' );

      }
    }
    return $conf;
  }

  public function getAvailableResources( $collectionId, $collectionType, $filterRTypeParent ) {
    $elemList = array();

    if( $collectionType ){
      $resourceViewModel = new ResourceViewModel();
      $collectionResourcesListViewModel = new CollectionResourcesListViewModel();

      $conf = $this->getConfCollection( $collectionType, $filterRTypeParent );
      $filter = $conf['listOptions'];
      //Recursos Posibles
      if( !empty($filter) && count($filter) > 0 ) {
        // error_log(__METHOD__.': $filter '.json_encode($filter) );
        $elemList = $resourceViewModel->listItems(
          array(
            'filters' => array( 'inRtypeIdName' => $filter )
          )
        )->fetchAll();
      }
      //Recursos Manuales
      if( $collectionId ){
        $colRes = $collectionResourcesListViewModel->listItems(
          array(
            'filters' => array( 'id' => $collectionId )
          )
        )->fetch();

        if( gettype( $colRes ) === 'object' && !empty($colRes->getter('resourceSonList'))){

          $elemManualList = $resourceViewModel->listItems(
            array(
              'filters' => array( 'inId' => explode(',', $colRes->getter('resourceSonList')) )
            )
          )->fetchAll();
          $elemList = array_merge($elemList, $elemManualList);
        }
      }
    }
    return $elemList;
  }
  /*
  public function getAvailableResources( $collectionId, $collectionType, $filterRTypeParent ) {
    $elemList = array();

    $resourceModel = new ResourceModel();
    $collectionResourcesModel = new CollectionResourcesModel();
    $rtypeControl = new ResourcetypeModel();

    if( $collectionType ){
      $conf = $this->getConfCollection( $collectionType, $filterRTypeParent );

      $filter = $conf['listOptions'];
      //Recursos Posibles
      if( !empty($filter) && count($filter) > 0 ) {
        //Se traen los rtypes establecidos en Conf
        $rtypeArray = $rtypeControl->listItems(
          array( 'filters' => array( 'idNameExists' => $filter ) )
        );
        //Creamos un array con los ids de los rtypes
        $filterRtype = array();
        while( $res = $rtypeArray->fetch() ){
          array_push( $filterRtype, $res->getter('id') );
        }
        //Traemos todos los recursos disponibles
        $elemList = $resourceModel->listItems(
          array(
            'filters' => array( 'inRtype' => $filterRtype ),
            'affectsDependences' => array('RExtUrlModel')
          )
        )->fetchAll();
      }

      //Recursos Manuales
      if( $collectionId ){
        $colRes = $collectionResourcesModel->listItems(
          array(
          'filters' => array( 'collection' => $collectionId ),
            'affectsDependences' => array('ResourceModel', 'RExtUrlModel')
          )
        )->fetchAll();

        foreach( $colRes as $key => $value ) {
          $elemList = array_merge($elemList, $value->getterDependence('resource'));
        }
      }

    }
    return $elemList;
  }*/

} // class CollectionView extends Vie
