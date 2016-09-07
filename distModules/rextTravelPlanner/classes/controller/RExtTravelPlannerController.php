<?php
geozzy::load( 'controller/RExtController.php' );

class RExtTravelPlannerController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl = false ) {
    // Este RExt funciona tambien como modulo "autonomo"
    if( $defRTypeCtrl !== false ) {
      parent::__construct( $defRTypeCtrl, new rextTravelPlanner(), 'rExtTravelPlanner_' );
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
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextTravelPlanner' );

    return $rExtViewBlockInfo;
  }


  /***************/
  /***  UTILS  ***/
  /***************/


  /**
   * Carga los datos de todos los travelPlanner de un usuario
   *
   * @param $resId integer
   *
   * @return array OR false
   */
/*
  public function getAllFavourites( $user ) {
    $favData = false;

    $favModel = new FavouritesListViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'user' => $user ) ) );
    $favObj = ( $favList ) ? $favList->fetch() : false;
    if( $favObj ) {
      $favData = ( $favObj->getter('resourceList') ) ? explode( ',', $favObj->getter('resourceList') ) : array();
    }

    return $favData;
  }
*/
  /*
  public function getAllFavourites( $user ) {
    $favData = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'user' => $user ) ) );
    if( $favList ) {
      $favData = array();
      while( $favObj = $favList->fetch() ) {
        $favData[] = $rExtObj->getAllData( 'onlydata' );
      }
    }

    return $favData;
  }
  */

  /**
   * Localiza el id de la coleccion de favoritos (false si no existe)
   *
   * @param $user integer
   *
   * @return integer
   */
/*
  public function getCollectionId( $user ) {
    $colId = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'user' => $user ) ) );
    $favObj = ( $favList ) ? $favList->fetch() : false;
    $colId = ( $favObj ) ? $favObj->getter( 'colId' ) : false;

    return $colId;
  }
*/

  /**
   * Carga los datos del favorito (false si no existe)
   *
   * @param $resId integer Id del recurso
   * @param $user integer Id del usuario
   *
   * @return array OR false
   */
/*
  public function getStatusInfo( $resId, $user ) {
    $favData = false;

    $favModel = new FavouritesViewModel();
    $favList = $favModel->listItems( array( 'filters' => array( 'resource' => $resId, 'user' => $user ) ) );
    $favObj = ( $favList ) ? $favList->fetch() : false;
    $favData = ( $favObj ) ? $favObj->getAllData( 'onlydata' ) : false;

    return $favData;
  }
*/

  /**
   * Carga el estado del favorito
   *
   * @param $resId integer Id del recurso
   * @param $user integer Id del usuario
   *
   * @return integer
   */
/*
  public function getStatus( $resId, $user ) {
    if( !is_array( $resId ) ) {
      $status = ( $this->getStatusInfo( $resId, $user ) ) ? 1 : 0;
    }
    else {
      $status = array();
      $favResources = $this->getAllFavourites( $user );
      foreach( $resId as $id ) {
        $status[ $id ] = ( in_array( $id, $favResources ) ) ? 1 : 0;
      }
    }

    return $status;
  }
*/

  /**
   * Establece el estado de favorito indicado en el recurso
   *
   * @param $resId integer Id del recurso
   * @param $status integer Estado 0-1. Se admite false-true
   *
   * @return bool
   */
/*
  public function setStatus( $resId, $newStatus, $user ) {
    $newStatus = ( $newStatus ) ? 1 : 0;

    $favData = $this->getStatusInfo( $resId, $user );
    $preStatus = ( $favData ) ? 1 : 0;
    if( $preStatus === 1 && $newStatus === 0 ) {
      // Estamos con status 1 y queremos status 0
      $crModel = new CollectionResourcesModel( array( 'id' => $favData['id'] ) );
      // error_log( 'Borrando crModel' );
      $crModel->delete();
    }

    if( $preStatus === 0 && $newStatus === 1 ) {
      // Estamos con status 0 y queremos status 1
      $colId = $this->getCollectionId( $user );

      if( !$colId ) {
        // Hai que crear toda la estructura: resource rtypeFavourites, collection, resource-collection
        $favsStructure = $this->newFavouritesStructure( $user );
        $colId = $favsStructure['colId'];

      }

      $crModel = new CollectionResourcesModel( array( 'collection' => $colId, 'resource' => $resId,
        'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
      $crModel->save();
      // error_log( 'Creando crModel' );
    }

    return( $newStatus === $this->getStatus( $resId, $user ) );
  }
*/

  public function newTravelPlannerStructure( $user ) {
    // Hay que crear toda la estructura: resource rtypeTravelPlanner

    // Creamos el recurso de favoritos
    $tpsResInfo = array( 'rTypeId' => $this->getTravelPlannerRTypeId(), 'user' => $user,
      'title' => 'Travel Planner. user '.$user,
      'title_'.Cogumelo::getSetupValue( 'lang:default' ) => 'Travel Planner. user '.$user,
      'published' => true, 'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) );
    $resModel = new ResourceModel( $tpsResInfo );
    $resModel->save();
    $resMainId = $resModel->getter('id');

    // Creamos las URLs del recurso de favoritos
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();
    $allLang = Cogumelo::getSetupValue( 'lang:available' );
    foreach( $allLang as $langKey => $langInfo ) {
      $tpsUrl = $resCtrl->getUrlByPattern( $resMainId, $langKey, $resMainId );
      $resCtrl->setUrl( $resMainId, $langKey, $tpsUrl );
    }

    return( array( 'tpsId' => $resMainId ) );
  }



  public function getTravelPlannerRTypeId() {
    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeTravelPlanner' ) ) );
    $rTypeObj = ( $rTypeList ) ? $rTypeList->fetch() : false;
    $rTypeId = ( $rTypeObj ) ? $rTypeObj->getter( 'id' ) : false;

    return( $rTypeId );
  }


  public function getTravelPlannerUrl( $user ) {


    $tpUrl = false;


    $tpModel = new ResourceModel();
    $tpList = $tpModel->listItems( array( 'filters' => array( 'user' => $user, 'rTypeId' => $this->getTravelPlannerRTypeId() ) ) );
    $tpObj = ( $tpList ) ? $tpList->fetch() : false;
    if( $tpObj ) {
      $tpsId = $tpObj->getter('id');
    }
    else {    
      $tpStructure = $this->newTravelPlannerStructure( $user );
      $tpsId = $tpStructure['tpsId'];
    }
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();

    $tpUrl = $resCtrl->getUrlAlias( $tpsId );

    return $tpUrl;
  }



} // class RExtFavouriteController
