<?php
geozzy::load( 'controller/RExtController.php' );

class RExtContactController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextContact(), 'rExtContact_' );
  }


  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    // error_log( "RExtContactController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new ContactModel();
    $rExtList = $rExtModel->listItems( [ 'filters' => [ 'resource' => $resId ], 'cache' => $this->cacheQuery ] );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }

    return $rExtData;
  }



  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtContactController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'address' => array(
        'params' => array( 'label' => __( 'Address' ) ),
        'rules' => array( 'maxlength' => 200 )
      ),
      'city' => array(
        'params' => array( 'label' => __( 'City' ) ),
        'rules' => array( 'maxlength' => 60 )
      ),
      'cp' => array(
        'params' => array( 'label' => __( 'Postal code' ) ),
        'rules' => array( 'maxlength' => 8 )
      ),
      'province' => array(
        'params' => array( 'label' => __( 'Province' ) ),
        'rules' => array( 'maxlength' => 60 )
      ),
      'phone' => array(
        'params' => array( 'label' => __( 'Phone' ) ),
        'rules' => array( 'maxlength' => 20 )
      ),
      'email' => array(
        'params' => array( 'label' => __( 'Contact email' ) ),
        'rules' => array( 'maxlength' => 255, 'email' => true)
      ),
      'directions' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'How to arrive' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'timetable' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Opening times' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'url' => array(
        'params' => array( 'label' => __( 'Contact url' ) ),
        'rules' => array( 'maxlength' => 2000, 'url' => true )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );

      // Limpiando la informacion de terms para el form
      if( $this->taxonomies ) {
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
            $taxFieldValues = array();
            foreach( $valuesArray[ $taxFieldName ] as $value ) {
              $taxFieldValues[] = ( is_array( $value ) ) ? $value[ 'id' ] : $value;
            }
            $valuesArray[ $taxFieldName ] = $taxFieldValues;
          }
        }
      }

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
  } // function manipulateForm()



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
    $templates['basic']->setTpl( 'rExtFormBasic.tpl', 'rextContact' );
    $templates['basic']->assign( 'rExt', $formBlockInfo );
    //$templates['basic']->addClientScript('js/initMap.js', 'geozzy');
    $templates['basic']->assign('timetable', $form->multilangFieldNames( 'rExtContact_timetable' ));

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

      // error_log( 'NEW rExtContact: ' . print_r( $valuesArray, true ) );
      $rExtModel = new ContactModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    if( !$form->existErrors() ) {
      $saveResult = $rExtModel->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
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

      if (isset($rExtViewBlockInfo['data']['city']) || isset($rExtViewBlockInfo['data']['province']) || isset($rExtViewBlockInfo['data']['cp'])
          || isset($rExtViewBlockInfo['data']['phone']) || isset($rExtViewBlockInfo['data']['email']) || isset($rExtViewBlockInfo['data']['url'])
          || isset($rExtViewBlockInfo['data']['directions']) || isset($rExtViewBlockInfo['data']['timetable'])){

        $rExtViewBlockInfo['template']['full'] = new Template();
        $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
        $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextContact' );
      }
    }

    return $rExtViewBlockInfo;
  }

} // class RExtContactController
