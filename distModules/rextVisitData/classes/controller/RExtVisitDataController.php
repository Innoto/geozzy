<?php
geozzy::load( 'controller/RExtController.php' );

class RExtVisitDataController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextVisitData(), 'rextVisitData_' );
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

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtVisitDataModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
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

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'duration' => array(
        'params' => array( 'label' => __( 'Average visit duration (minutes)' ) ),
        'rules' => array( 'digits' => true )
      ),
      'timetable' => array(
        'params' => array( 'label' => __( 'Visit timetable' ) ),
        'rules' => array( 'maxlength' => 200 )
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
  // parent::getFormBlockInfo( $form );



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

      $rExtModel = new RExtVisitDataModel( $valuesArray );
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
   //parent::getViewBlockInfo( $resId );


} // class RExtVisitDataController
