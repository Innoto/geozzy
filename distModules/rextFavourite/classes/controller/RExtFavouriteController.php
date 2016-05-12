<?php


class RExtFavouriteController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl = false ) {
    // Este RExt funciona tambien como modulo "autonomo"
    if( $defRTypeCtrl !== false ) {
      parent::__construct( $defRTypeCtrl, new rextFavourite(), 'rExtFavourite_' );
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

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();
    // error_log( 'USER: '.print_r( $userInfo, true ) );
    $userId = isset( $userInfo['data']['id'] ) ? $userInfo['data']['id'] : false;

    if( $userId ) {
      $rExtData = $this->getStatusInfo( $resId, $userId );
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

    $resId = $this->defResCtrl->resObj->getter('id');

    $rExtViewBlockInfo['template']['full'] = new Template();
    $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
    $rExtViewBlockInfo['template']['full']->assign( 'resId', $resId );
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextFavourite' );

    return $rExtViewBlockInfo;
  }


  /***************/
  /***  UTILS  ***/
  /***************/


  /**
   * Carga los datos de todos los favoritos de un usuario
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getAllFavourites( $favUser ) {
    $favData = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'user' => $favUser ) ) );
    if( $favList ) {
      $favData = array();
      while( $favObj = $favList->fetch() ) {
        $favData[] = $rExtObj->getAllData( 'onlydata' );
      }
    }

    return $favData;
  }


  /**
   * Localiza el id de la coleccion de favoritos (false si no existe)
   *
   * @param $favUser integer
   *
   * @return integer
   */
  public function getCollectionId( $favUser ) {
    $colId = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'user' => $favUser ) ) );
    $favObj = ( $favList ) ? $favList->fetch() : false;
    $colId = ( $favObj ) ? $favObj->getter( 'colId' ) : false;

    return $colId;
  }


  /**
   * Carga los datos del favorito (false si no existe)
   *
   * @param $resId integer Id del recurso
   * @param $favUser integer Id del usuario
   *
   * @return array OR false
   */
  public function getStatusInfo( $resId, $favUser ) {
    $favData = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'resource' => $resId, 'user' => $favUser ) ) );
    $favObj = ( $favList ) ? $favList->fetch() : false;
    $favData = ( $favObj ) ? $favObj->getAllData( 'onlydata' ) : false;

    return $favData;
  }


  /**
   * Carga el estado del favorito
   *
   * @param $resId integer Id del recurso
   * @param $favUser integer Id del usuario
   *
   * @return integer
   */
  public function getStatus( $resId, $favUser ) {
    $status = ( $this->getStatusInfo( $resId, $favUser ) ) ? 1 : 0;

    return $status;
  }


  /**
   * Establece el estado de favorito indicado en el recurso
   *
   * @param $resId integer Id del recurso
   * @param $status integer Estado 0-1. Se admite false-true
   *
   * @return bool
   */
  public function setStatus( $resId, $newStatus, $favUser ) {
    $newStatus = ( $newStatus ) ? 1 : 0;

    $favData = $this->getStatusInfo( $resId, $favUser );
    $preStatus = ( $favData ) ? 1 : 0;

    if( $preStatus === 1 && $newStatus === 0 ) {
      // Estamos con status 1 y queremos status 0
      $crModel = new CollectionResourcesModel( array( 'id' => $favData['id'] ) );
      // error_log( 'Borrando crModel' );
      $crModel->delete();
    }

    if( $preStatus === 0 && $newStatus === 1 ) {
      // Estamos con status 0 y queremos status 1
      $colId = $this->getCollectionId( $favUser );

      if( !$colId ) {
        // Hai que crear toda la estructura previa: res rtypeFavourites, col, rc
        $resModel = new ResourceModel( array( 'rTypeId' => $this->getFavRTypeId(), 'user' => $favUser,
          'published' => true, 'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
        $resModel->save();
        $resMainId = $resModel->getter('id');

        $colModel = new CollectionModel( array( 'idName' => 'FAV_'.$favUser, 'collectionType' => 'favourites',
          'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
        $colModel->save();

        $colId = $colModel->getter('id');

        $rcModel = new ResourceCollectionsModel( array( 'resource' => $resMainId, 'collection' => $colId ) );
        $rcModel->save();
      }

      $crModel = new CollectionResourcesModel( array( 'collection' => $colId, 'resource' => $resId,
        'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
      $crModel->save();
      // error_log( 'Creando crModel' );
    }


    return( $newStatus === $this->getStatus( $resId, $favUser ) );
  }


  public function getFavRTypeId() {
    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeFavourites' ) ) );
    $rTypeObj = ( $rTypeList ) ? $rTypeList->fetch() : false;
    $rTypeId = ( $rTypeObj ) ? $rTypeObj->getter( 'id' ) : false;

    return( $rTypeId );
  }


} // class RExtFavouriteController
