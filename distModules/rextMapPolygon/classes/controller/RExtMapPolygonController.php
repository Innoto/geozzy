<?php
geozzy::load( 'controller/RExtController.php' );

class RExtMapPolygonController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rExtMapPolygon(), 'rExtMapPolygon_' );
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

     //$templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
     $templates['full']->setTpl( 'rExtFormBlock.tpl', 'rextMapPolygon' );
     $templates['full']->addClientScript('js/adminRextMapPolygon.js', 'rextMapPolygon');
     //$templates['full']->addClientScript('js/collection/RoadCollection.js', 'rextRoads');
     //$templates['full']->addClientScript('js/model/RoadModel.js', 'rextRoads');
     //$templates['full']->addClientScript('js/adminRextRoads.js', 'rextRoads');
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
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
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


     return $rExtViewBlockInfo;
   }


} // class RExtRoutesController
