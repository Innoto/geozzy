<?php
geozzy::load( 'controller/RExtController.php' );

class RExtAudioguideController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextAudioguide(), 'rExtAudioguide_' );
  }

  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    $rExtData = false;

    // @todo Esto ten que controlar os idiomas
    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new AudioguideModel();
    $rExtModel->save();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'cache' => $this->cacheQuery ) );

    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }

    $fileData = new FiledataModel();
    // para cada idioma tenemos q traernos los ficheros!!!!!!

    foreach( Cogumelo::getSetupValue('publicConf:vars:langAvailableIds') as $lang ) {
      if( isset($rExtData['audioFile_'.$lang]) ){
        $audioguide[$lang] = $fileData->listItems(array('filters' => array('id' => $rExtData['audioFile_'.$lang]), 'cache' => $this->cacheQuery))->fetch()->getAllData( 'onlydata' );
        $rExtData['audioFile_'.$lang] = $audioguide[$lang];
      }
    }

    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'distance' => array(
        'params' => array( 'label' => __( 'Distance' ) ),
        'type' => 'INT'
      ),
      'audioFile' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Audioguide file' ), 'type' => 'file',
        'placeholder' => __( 'File' ), 'destDir' => AudioguideModel::$cols['audioFile']['uploadDir'] ) ,
        'rules' => array( 'maxfilesize' => '5242880', 'accept' => 'audio/mpeg,audio/mpeg3,audio/mp3,audio/x-mpeg-3' )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );

    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );

      $form->loadArrayValues( $valuesArray );
    }

    // Add RExt info to form
    foreach( $fieldsInfo as $fieldName => $info ) {
      if( isset( $info[ 'translate' ] ) && $info[ 'translate' ] ) {
        $rExtFieldNames = array_merge( $rExtFieldNames, $form->multilangFieldNames( $fieldName ) );
      }
      else {
        $rExtFieldNames[] = $fieldName;
      }
    }

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  }


  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
  */
  public function getFormBlockInfo( FormController $form ) {

    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    $templates['basic'] = new Template();
    $templates['basic']->setTpl( 'rExtFormBlock.tpl', 'rextAudioguide' );
    $templates['basic']->assign( 'rExt', $formBlockInfo );

    // $templates['basic']->addClientStyles('ionrangeslider/css/ion.rangeSlider.css', 'vendor/bower');
    // $templates['basic']->addClientScript('ionrangeslider/js/ion.rangeSlider.js', 'vendor/bower');
    $templates['basic']->addClientScript('js/rextAudioguideAdmin.js', 'rextAudioguide');

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }


  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      $rExtModel = new AudioguideModel( $valuesArray );
      $rExtModel->save();
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
      else{
        foreach( Cogumelo::getSetupValue('publicConf:vars:langAvailableIds') as $lang ) {
          $fileField[$lang] = $this->addPrefix( 'audioFile_'.$lang );
          if( $form->isFieldDefined( $fileField[$lang] ) ) {
            $this->defResCtrl->setFormFiledata( $form, $fileField[$lang], 'audioFile_'.$lang, $rExtModel );
            //$rExtModel->save();
          }
        }
      }

      if( !$form->existErrors() ) {
        $saveResult = $rExtModel->save();
        if( $saveResult === false ) {
          $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
        }
      }

    }
  }


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )


  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
  */
  public function getViewBlockInfo( $resId = false ) {

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      // TODO: esto será un campo da BBDD
      $rExtViewBlockInfo['data'] = $this->defResCtrl->getTranslatedData( $rExtViewBlockInfo['data'] );

      if (isset($rExtViewBlockInfo['data']['audioFile'])){

        $rExtViewBlockInfo['template']['full'] = new Template();
        $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
        $rExtViewBlockInfo['template']['full']->addClientScript('js/rextAudioguide.js', 'rextAudioguide');
        $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextAudioguide' );
      }
    }


    return $rExtViewBlockInfo;
  }

} // class RExtAudioguideController
