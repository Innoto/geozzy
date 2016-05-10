<?php


class RExtFavouriteController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextFavourite(), 'rExtFavourite_' );
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


    $rExtModel = new FavouritesViewModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = ( $rExtList ) ? $rExtList->fetch() : false;

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
  }


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
  }



  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo() {

    $rExtViewBlockInfo = parent::getViewBlockInfo();

    $rExtViewBlockInfo['template']['full'] = new Template();
    $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextFavourite' );

    return $rExtViewBlockInfo;
  }

} // class RExtFavouriteController
