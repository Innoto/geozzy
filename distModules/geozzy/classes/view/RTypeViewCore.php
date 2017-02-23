<?php
interface RTypeViewInterface {
  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck();

  /**
   * Defino el formulario de edición y creo su Bloque con su TPL
   *
   * @param $formName string Nombre del form
   * @param $urlAction string URL del action
   * @param $valuesArray array Opcional: Valores de los campos del form
   *
   * @return Obj-Template
   **/
  // public function getFormBlock( $formName, $urlAction, $valuesArray = false );

  /**
   * Action del formulario de edición
   */
  // public function actionResourceForm();

  /**
   * Preparamos los datos para visualizar las distintas partes que forman el RType
   *
   * Creamos un Array con todos la información del RType y sus RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RType. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RType co formato 'fieldName' => 'value'
   *   - 'dataForm' => Array con contenidos HTML del formulario del RType
   *   - 'objForm' => FormController
   *   - 'ext' => Array con el resultado del manipulateForm de cada RExt
   * Se cargan todos sin crear ningún Template
   * Ejemplo de respuesta:
   * $formBlockInfo = array(
   *   'template' => array(
   *     'full' => new Template()
   *   ),
   *   'data' => $this->defResCtrl->getResourceData( $resId ),
   *   'dataForm' => array(
   *     'formId' => $form->getId(),
   *     'formOpen' => $form->getHtmpOpen(),
   *     'formFieldsArray' => $form->getHtmlFieldsArray(),
   *     'formFieldsHiddenArray' => array(),
   *     'formFields' => $form->getHtmlFieldsAndGroups(),
   *     'formClose' => $form->getHtmlClose(),
   *     'formValidations' => $form->getScriptCode()
   *   )
   *   'objForm' => $form,
   *   'ext' => array()
   * );
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'objForm' => FormController, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form );

  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId );
}



Cogumelo::load('coreView/View.php');

class RTypeViewCore extends View {

  public $defResCtrl = null;
  public $rTypeModule = null;
  public $rTypeCtrl = null;
  public $rTypeName = 'RTypeNameUnknown';

  public function __construct( ResourceController $defResCtrl, Module $rTypeModule ) {
    // error_log( 'RTypeViewCore: __construct() para '.$rTypeModule->name.' - '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );

    $this->defResCtrl = $defResCtrl;
    $rTypeName = $this->rTypeName = $rTypeModule->name;
    $this->rTypeModule = $rTypeModule;

    parent::__construct();

    $rTypeCtrlClassName = 'RT'.mb_strcut( $rTypeName, 2 ).'Controller';
    $rTypeName::load( 'controller/'.$rTypeCtrlClassName.'.php' );
    $this->rTypeCtrl = new $rTypeCtrlClassName( $defResCtrl );
  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {
    // error_log( 'RTypeViewCore: accessCheck() para '.$this->rTypeName );

    return true;
  }


  /**
   * Preparamos los datos para visualizar las distintas partes que forman el RType
   *
   * Creamos un Array con todos la información del RType y sus RExt:
   *   - 'template' Array de objetos Template ofrecidos por el RType. Por defecto usamos 'full'
   *   - 'data' => Array con todos los datos del RType co formato 'fieldName' => 'value'
   *   - 'dataForm' => Array con contenidos HTML del formulario del RType
   *   - 'objForm' => FormController
   *   - 'ext' => Array con el resultado del manipulateForm de cada RExt
   * Se cargan todos sin crear ningún Template
   * Ejemplo de respuesta:
   * $formBlockInfo = array(
   *   'template' => array(
   *     'full' => new Template()
   *   ),
   *   'data' => $this->defResCtrl->getResourceData( $resId ),
   *   'dataForm' => array(
   *     'formId' => $form->getId(),
   *     'formOpen' => $form->getHtmpOpen(),
   *     'formFieldsArray' => $form->getHtmlFieldsArray(),
   *     'formFieldsHiddenArray' => array(),
   *     'formFields' => $form->getHtmlFieldsAndGroups(),
   *     'formClose' => $form->getHtmlClose(),
   *     'formValidations' => $form->getScriptCode()
   *   )
   *   'objForm' => $form,
   *   'ext' => array()
   * );
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'objForm' => FormController, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    // error_log( __CLASS__.': getFormBlockInfo( $form ) para '.$this->rTypeName );

    $formBlockInfo = $this->rTypeCtrl->getFormBlockInfo( $form );

    return $formBlockInfo;
  }


  /**
   * Visualizamos el Recurso
   *
   * @param $resId int ID del recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( __CLASS__.': getViewBlockInfo('.$resId.') para '.$this->rTypeName );

    $resViewBlockInfo = $this->rTypeCtrl->getViewBlockInfo( $resId );

    return $resViewBlockInfo;
  }

} // class RTypeViewCore extends View
