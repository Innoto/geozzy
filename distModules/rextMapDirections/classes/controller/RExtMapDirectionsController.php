<?php


class RExtMapDirectionsController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtMapDirectionsController::__construct' );

    global $C_LANG;
    $this->actLang = $C_LANG;

    parent::__construct( $defRTypeCtrl, new rextMapDirections(), 'rExtMapDirections_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtMapDirectionsController: getRExtData( $resId )" );
    $rExtData = false;

    // error_log( 'RExtMapDirectionsController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtMapDirectionsController: manipulateForm()" );
    $rExtFieldNames = array();

    return( $rExtFieldNames );
  } // function manipulateForm()




  public function getFormBlockInfo( FormController $form ) {
    // error_log( "RExtMapDirectionsController: getFormBlockInfo()" );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false
    );

    return $formBlockInfo;
  }







  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtMapDirectionsController: resFormRevalidate()" );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtMapDirectionsController: resFormProcess()" );
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtMapDirectionsController: resFormSuccess()" );
  }


  /**
    Datos y template por defecto de la extension
   */
  public function getViewBlockInfo() {
    // error_log( "RExtMapDirectionsController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData()
    );

    $resData = $this->defResCtrl->getResourceData();
    if( isset( $resData['locLat'] ) && $resData['locLat'] !== '' ) {
      $rExtViewBlockInfo['data']['title'] = $resData['title'];
      $rExtViewBlockInfo['data']['locLat'] = $resData['locLat'];
      $rExtViewBlockInfo['data']['locLon'] = $resData['locLon'];
      $rExtViewBlockInfo['data']['defaultZoom'] = $resData['defaultZoom'];

      $template = new Template();
      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextMapDirections' );
      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    // error_log( "RExtMapDirectionsController: getViewBlockInfo() = " . print_r( $rExtViewBlockInfo['data'], true ) );

    return $rExtViewBlockInfo;
  }

} // class RExtMapDirectionsController
