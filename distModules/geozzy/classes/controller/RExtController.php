<?php


interface RExtInterface {

  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId );
  // @todo Esto ten que controlar os idiomas

  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form );

  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form );

  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  public function resFormRevalidate( FormController $form );

  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource );

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo();

} // interface RExtInterface


class RExtController {

  public $rExtName = 'rExt';
  public $prefix = 'rExt_';

  public $defRTypeCtrl = null;
  public $defResCtrl = null;
  public $rExtModule = null;
  public $rExtModel = null;
  public $taxonomies = false;

  public $numericFields = false;


  public function __construct( $defRTypeCtrl, $rExtModule, $prefix = false ){
    // error_log( 'RExtController::__construct' );

    $this->defRTypeCtrl = $defRTypeCtrl;
    $this->defResCtrl = $defRTypeCtrl->defResCtrl;
    $this->rExtName = $rExtModule->name;
    $this->prefix = ( $prefix ) ? $prefix : $this->rExtName.'_';

    $this->rExtModule = $rExtModule;
    if( property_exists( $rExtModule, 'taxonomies' ) && is_array( $rExtModule->taxonomies )
      && count( $rExtModule->taxonomies ) > 0 )
    {
      $this->taxonomies = $rExtModule->taxonomies;
    }
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

    return $rExtData;
  }

  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false
    );

    $prefixedFieldNames = $this->prefixArray( $form->getFieldValue( $this->addPrefix( 'FieldNames' ) ) );

    $formBlockInfo['dataForm'] = array(
      'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
      'formFields' => $form->getHtmlFieldsAndGroups(),
    );

    if( $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->getRExtData();
    }

    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }

  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  public function resFormRevalidate( FormController $form ) {
  }

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
  }

  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo() {
    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    return $rExtViewBlockInfo;
  }


  /*************
    Utilidades
  *************/

  public function getRExtFormValues( $formValuesArray, $numericFields = false ) {
    // error_log( "RExtController: getRExtFormValues()" );
    $valuesArray = array();

    foreach( $formValuesArray as $key => $value ) {
      $newKey = $this->delPrefix( $key );
      if( $newKey !== $key ) {
        if( $numericFields && $formValuesArray[ $key ] === '' && in_array( $newKey, $numericFields ) ) {
          $valuesArray[ $newKey ] = null;
        }
        else {
          $valuesArray[ $newKey ] = $formValuesArray[ $key ];
        }
      }
    }

    return ( count( $valuesArray ) < 1 ) ? false : $valuesArray;
  }

  public function prefixArrayKeys( $valuesArray ) {
    if( is_array( $valuesArray ) ) {
      $prefixArray = array();
      foreach( $valuesArray as $key => $value ) {
        $prefixArray[ $this->addPrefix( $key ) ] = $value;
      }
    }
    else {
      $prefixArray = $valuesArray;
    }

    return $prefixArray;
  }

  public function prefixArray( $valuesArray ) {
    if( is_array( $valuesArray ) ) {
      $prefixArray = array();
      foreach( $valuesArray as $value ) {
        $prefixArray[] = $this->addPrefix( $value );
      }
    }
    else {
      $prefixArray = $valuesArray;
    }

    return $prefixArray;
  }

  public function addPrefix( $text ) {

    return $this->prefix . $text;
  }

  public function delPrefix( $text ) {
    if( strpos( $text, $this->prefix ) === 0 ) {
      $text = substr( $text, strlen( $this->prefix ) );
    }

    return $text;
  }

} // class RExtController
