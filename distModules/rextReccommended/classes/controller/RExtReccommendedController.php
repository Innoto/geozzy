<?php


class RExtReccommendedController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextReccommended(), 'rExtReccommended_' );
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

    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    $rExtFieldNames = array();

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
    // error_log( "RExtMapDirectionsController: resFormProcess()" );
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
  public function getViewBlockInfo() {

    $rExtViewBlockInfo = parent::getViewBlockInfo();

    $resData = $this->defResCtrl->getResourceData();

    if( isset( $resData) ) {

      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextReccommended' );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtMapDirectionsController
