<?php


class RExtAppGeozzySampleController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextAppGeozzySample(), 'rExtAppGeozzySample_' );

    // $this->numericFields = array();
  }

  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    $rExtData = [];

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtAppGeozzySampleModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = ($rExtList) ? $rExtList->fetch() : false;

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Añadimos los campos en el idioma actual o el idioma principal
      $resourceExtFields = $rExtObj->getCols();
      $langDefault = Cogumelo::getSetupValue('publicConf:vars:langDefault');
      foreach( $resourceExtFields as $key => $value ) {
        if( !isset( $rExtData[ $key ] ) ) {
          $rExtData[ $key ] = $rExtObj->getter( $key );
          // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
          if( $rExtData[ $key ] === '' && isset( $rExtData[ $key.'_'.$langDefault ] ) ) {
            $rExtData[ $key ] = $rExtData[ $key.'_'.$langDefault ];
          }
        }
      }

    }

    // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
    $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
    if( $termsGroupedIdName !== false ) {
      foreach( $this->taxonomies as $tax ) {
        if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
          $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
        }
      }
    }

    return ( count($rExtData) > 0 ) ? $rExtData : false;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    // error_log( 'rextAppGeozzySample: manipulateForm()' );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'contentGzzSample' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Content Gezzy Sample' ), 'type' => 'textarea', 'htmlEditor' => 'true' ),
        'rules' => array( 'maxlength' => '32000' )
      ),
      'textGzzSample' => array(
        'params' => array( 'label' => __( 'Text Gezzy Sample' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

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

      $rExtModel = new RExtAppGeozzySampleModel( $valuesArray );
      if( gettype($rExtModel) !== 'object' || $rExtModel->save() === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    if( !$form->existErrors() ) {
      // foreach( $this->taxonomies as $tax ) {
      //   $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
      //   if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
      //     $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
      //   }
      // }
    }

    if( !$form->existErrors() ) {
      if( $rExtModel->save() === false ) {
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
    // error_log( 'rextAppGeozzySample: getViewBlockInfo( $resId )' );
    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextAppGeozzySample' );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtAppGeozzySampleController
