<?php


interface RExtInterface {

  /**
   * Carga los datos de los elementos de la extension
   *
   * A implementar en el controller que extiende.
   * Creamos un Array con todos los datos del RExt co formato 'fieldName' => 'value'
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
   * A implementar en el controller que extiende.
   * Alteramos el objeto $form:
   *   - Añadimos campos, valores y reglas del RExt
   *   - Podemos alterar campos, valores y reglas existentes (Peligroso!!!)
   *   - IMPORTANTE: Guardar la lista de campos del RExt en el campo 'reserved' 'FieldNames'
   *     Esto permite que el método del RExt getRExtFormValues() separe sus campos del resto
   * Nota: Todos los campos de RExt llevan un prefijo que los marca para evitar conflictos
   *   Para añadir dichos prefijos, disponemos de los métodos addPrefix, prefixArray y prefixArrayKeys
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form );

  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * Implementado y extensible
   * Creamos un Array con toda la información del RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RExt. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RExt co formato 'fieldName' => 'value'
   *   - 'dataForm' => Array con contenidos HTML del formulario del RExt
   * Ejemplo resumido:
   * $formBlockInfo = array(
   *   'template' => array(
   *     'full' => new Template()
   *   ),
   *   'data' => $this->getRExtData(),
   *   'dataForm' => array(
   *     'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
   *     'formFields' => $form->getHtmlFieldsAndGroups(),
   *   )
   * );
   * $formBlockInfo['template']['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
   * $formBlockInfo['template']['full']->assign( 'rExtName', $this->rExtName );
   * $formBlockInfo['template']['full']->assign( 'rExt', $formBlockInfo );
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form );

  /**
   * Validaciones extra previas a usar los datos
   *
   * Implementado vacío y extensible. Normalmente no hay que hacer nada
   * Permite revisar los datos del formulario despues del submit y añadir errores al $form:
   *   - $form->addFormError( 'Msg. de error global del formulario' );
   *   - $form->addFieldError( $fieldName, 'Msg. de error para un campo del formulario' );
   *
   * @param $form FormController
   */
  public function resFormRevalidate( FormController $form );

  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * Hay que crear/guardar los datos del RExt: Modelos, términos, ...
   * Si hay errores, es necesario registrarlos en $form para parar el proceso y notificarlos:
   *   - $form->addFormError( 'Msg. de error global del formulario' );
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource );

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * Implementado vacío y extensible. Normalmente no se usa.
   * Puede servir para alterar los 'success' del $form:
   *   - $form->setSuccess( $successName, $successParam = true )
   *     successName options:
   *       jsEval : Ejecuta el texto indicado con un eval
   *       accept : Muestra el texto como un alert
   *       redirect : Pasa a la url indicada con un window.location.replace
   *       reload : window.location.reload
   *       resetForm : Borra el formulario
   *   - $form->removeSuccess( $successName = false )
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource );

  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * Implementado sin Template y extensible.
   * Creamos un Array con todos la información del RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RExt. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RExt co formato 'fieldName' => 'value'
   * Por defecto (RExt sin Template):
   * $rExtViewBlockInfo = array(
   *   'template' false,
   *   'data' => $this->getRExtData(),
   * );
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId );

} // interface RExtInterface


class RExtController {

  // Nombre del RExt
  public $rExtName = 'rExt';
  // Prefijo para los campos del RExt
  public $prefix = 'rExt_';

  // Acceso al controller del RType 'padre'
  public $defRTypeCtrl = null;
  // Acceso al controller del Resource 'padre'
  public $defResCtrl = null;
  // Acceso a 'la configuración' del RExt
  public $rExtModule = null;
  public $rExtModel = null;

  // Datos de taxonomias en 'la configuración' del RExt
  public $taxonomies = false;
  // Datos de los modelos en 'la configuración' del RExt
  public $models = false;
  // Datos de las colecciones en 'la configuración' del RExt
  public $collections = false;

  // Campos del RExt que son de tipo numérico y que se inicializan a null si no tienen valor
  public $numericFields = false;

  public $cacheQuery = false; // false, true or time in seconds (0: never expire)


  /**
   * Constructor de RExtController que inicializa sus atributos
   *
   * El constructor del controller que extiende lanza este con los parámetros adecuados
   * Se inicializan: defRTypeCtrl, defResCtrl, rExtName, prefix, rExtModule y taxonomies
   * Si existen campos numéricos, el otro tambien tiene que inicializar el atributo numericFields
   *
   * @param Object $defRTypeCtrl Controller del RType 'padre'
   * @param Object $rExtModule Acceso a 'la configuración' del RExt
   * @param String $prefix Prefijo para los campos del RExt
   */
  public function __construct( $defRTypeCtrl, $rExtModule, $prefix = false ){
    // error_log( __METHOD__.': '.$rExtModule->name.' - '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

    if( $defRTypeCtrl ) {
      $this->defRTypeCtrl = $defRTypeCtrl;
      $this->defResCtrl = $defRTypeCtrl->defResCtrl;
      $this->cacheQuery = isset( $defRTypeCtrl->cacheQuery ) ? $defRTypeCtrl->cacheQuery : false;
    }
    $this->rExtName = $rExtModule->name;
    $this->prefix = ( $prefix ) ? $prefix : $this->rExtName.'_';

    $this->rExtModule = $rExtModule;

    if( property_exists( $rExtModule, 'taxonomies' ) && is_array( $rExtModule->taxonomies ) && count( $rExtModule->taxonomies ) > 0 ) {
      $this->taxonomies = $rExtModule->taxonomies;
    }

    if( property_exists( $rExtModule, 'models' ) && is_array( $rExtModule->models ) && count( $rExtModule->models ) > 0 ) {
      $this->models = $rExtModule->models;
    }

    if( property_exists( $rExtModule, 'collections' ) && is_array( $rExtModule->collections ) && count( $rExtModule->collections ) > 0 ) {
      $this->collections = $rExtModule->collections;
    }
  }


  /**
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * Implementado y extensible
   * Creamos un Array con toda la información del RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RExt. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RExt co formato 'fieldName' => 'value'
   *   - 'dataForm' => Array con contenidos HTML del formulario del RExt
   * Ejemplo resumido:
   * $formBlockInfo = array(
   *   'template' => array(
   *     'full' => new Template()
   *   ),
   *   'data' => $this->getRExtData(),
   *   'dataForm' => array(
   *     'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
   *     'formFields' => $form->getHtmlFieldsAndGroups(),
   *   )
   * );
   * $formBlockInfo['template']['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
   * $formBlockInfo['template']['full']->assign( 'rExtName', $this->rExtName );
   * $formBlockInfo['template']['full']->assign( 'rExt', $formBlockInfo );
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    // error_log( __METHOD__.': '. $this->rExtName );

    $formBlockInfo = array(
      'template' => array(
        'full' => new Template()
      ),
      'data' => false,
      'dataForm' => false
    );

    $prefixedFieldNames = $this->prefixArray( $form->getFieldValue( $this->addPrefix( 'FieldNames' ) ) );

    $formBlockInfo['dataForm'] = array(
      'formId' => $form->getId(),
      'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
      'formFields' => $form->getHtmlFieldsAndGroups(),
    );

    if( $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->getRExtData();
    }

    $formBlockInfo['template']['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $formBlockInfo['template']['full']->assign( 'rExtName', $this->rExtName );
    $formBlockInfo['template']['full']->assign( 'rExt', $formBlockInfo );

    return $formBlockInfo;
  }

  /**
   * Validaciones extra previas a usar los datos
   *
   * Implementado vacío y extensible. Normalmente no hay que hacer nada
   * Permite revisar los datos del formulario despues del submit y añadir errores al $form:
   *   - $form->addFormError( 'Msg. de error global del formulario' );
   *   - $form->addFieldError( $fieldName, 'Msg. de error para un campo del formulario' );
   *
   * @param $form FormController
   */
  public function resFormRevalidate( FormController $form ) {
  }

  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * Implementado vacío y extensible. Normalmente no se usa.
   * Puede servir para alterar los 'success' del $form:
   *   - $form->setSuccess( $successName, $successParam = true )
   *     successName options:
   *       jsEval : Ejecuta el texto indicado con un eval
   *       accept : Muestra el texto como un alert
   *       redirect : Pasa a la url indicada con un window.location.replace
   *       reload : window.location.reload
   *       resetForm : Borra el formulario
   *   - $form->removeSuccess( $successName = false )
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
  }

  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * Implementado sin Template y extensible
   * Creamos un Array con todos la información del RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RExt. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RExt co formato 'fieldName' => 'value'
   *
   * $rExtViewBlockInfo = array(
   *   'template' false,
   *   'data' => $this->getRExtData(),
   * );
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData( $resId ) // TODO: Esto ten que controlar os idiomas
    );

    return $rExtViewBlockInfo;
  }


  /*************
    Utilidades
  *************/

  /**
   * Separamos los valores de los campos del Form que son de este RExt del resto y les sacamos en prefijo
   *
   * @param Array $formValuesArray Valores de todos los campos del Form
   * @param Array $numericFields Nombre de los campos de tipo numerico para inicializarlos a 'null' si no existen
   *
   * @return Array $valuesArray Valores los campos del Form que son de este RExt con sus nombres sin el prefijo
   */
  public function getRExtFormValues( $formValuesArray, $numericFields = false ) {
    // error_log( "RExtController: getRExtFormValues()" );
    $valuesArray = array();

    if( $numericFields ) {
      $this->numericFields = $numericFields;
    }

    foreach( $formValuesArray as $key => $value ) {
      $newKey = $this->removePrefix( $key );
      if( $newKey !== $key ) {
        if( $this->numericFields && $formValuesArray[ $key ] === '' && in_array( $newKey, $this->numericFields ) ) {
          $valuesArray[ $newKey ] = null;
        }
        else {
          $valuesArray[ $newKey ] = $formValuesArray[ $key ];
        }
      }
    }

    return ( count( $valuesArray ) < 1 ) ? false : $valuesArray;
  }

  /**
   * Añade el prefijo del RExt
   *
   * @param String $text Texto que necesita prefijo
   *
   * @return String Texto con el prefijo
   */
  public function addPrefix( $text ) {

    return $this->prefix . $text;
  }

  /**
   * Quita el prefijo del RExt
   *
   * @param String $text Texto que le sobra el prefijo
   *
   * @return String Texto sin el prefijo
   */
  public function removePrefix( $text ) {
    if( strpos( $text, $this->prefix ) === 0 ) {
      $text = substr( $text, strlen( $this->prefix ) );
    }

    return $text;
  }

  /**
   * Añade el prefijo del RExt a los valores del array
   *
   * @param Array $valuesArray Array que tiene nombres de campos
   *
   * @return Array Array que tiene los valores con el prefijo del RExt
   */
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

  /**
   * Añade el prefijo del RExt a los keys del array
   *
   * @param Array $valuesArray Array que tiene como keys nombres de campos
   *
   * @return Array Array que tiene los keys con el prefijo del RExt
   */
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





  public function cloneTo( $resFromObj, $resToObj ) {
    error_log( __METHOD__.': '.$this->rExtName );
    $result = true;

    if( $result && !empty( $this->collections ) ) {
      if( !$this->defResCtrl->cloneCollections( $resFromObj->getter('id'), $resToObj->getter('id'), $this->collections ) ) {
        $result = false;
      }
    }

    if( $result && !empty( $this->taxonomies ) ) {
      if( !$this->defResCtrl->cloneTaxonomies( $resFromObj->getter('id'), $resToObj->getter('id'), array_keys( $this->taxonomies ) ) ) {
        $result = false;
      }
    }

    if( $result && !empty( $this->models ) ) {
      if( !$this->defResCtrl->cloneRExtModels( $resFromObj->getter('id'), $resToObj->getter('id'), $this->models ) ) {
        $result = false;
      }
    }

    return $result;
  }


} // class RExtController
