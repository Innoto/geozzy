<?php
geozzy::load( 'controller/RExtController.php' );

class RExtCommunityController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl = false ) {
    // Este RExt funciona tambien como modulo "autonomo"
    if( $defRTypeCtrl !== false ) {
      parent::__construct( $defRTypeCtrl, new rextCommunity(), 'rExtCommunity_' );
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
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextCommunity' );

    return $rExtViewBlockInfo;
  }


  /***************/
  /***  UTILS  ***/
  /***************/


  /**
   * Carga los datos de todos los community de un usuario
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getAllCommunity( $commUser ) {
    $commData = false;

    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    $commObj = ( $commList ) ? $commList->fetch() : false;
    if( $commObj ) {
      $commData = ( $commObj->getter('resourceList') ) ? explode( ',', $commObj->getter('resourceList') ) : array();
    }

    return $commData;
  }
  /*
  public function getAllCommunity( $commUser ) {
    $commData = false;

    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    if( $commList ) {
      $commData = array();
      while( $commObj = $commList->fetch() ) {
        $commData[] = $rExtObj->getAllData( 'onlydata' );
      }
    }

    return $commData;
  }
  */

  /**
   * Localiza el id de la coleccion de community (false si no existe)
   *
   * @param $commUser integer
   *
   * @return integer
   */
  public function getCollectionId( $commUser ) {
    $colId = false;

    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    $commObj = ( $commList ) ? $commList->fetch() : false;
    $colId = ( $commObj ) ? $commObj->getter( 'colId' ) : false;

    return $colId;
  }


  /**
   * Carga los datos del community (false si no existe)
   *
   * @param $resId integer Id del recurso
   * @param $commUser integer Id del usuario
   *
   * @return array OR false
   */
  public function getStatusInfo( $resId, $commUser ) {
    $commData = false;

    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'resource' => $resId, 'user' => $commUser ) ) );
    $commObj = ( $commList ) ? $commList->fetch() : false;
    $commData = ( $commObj ) ? $commObj->getAllData( 'onlydata' ) : false;

    return $commData;
  }


  /**
   * Carga el estado del community
   *
   * @param $resId integer Id del recurso
   * @param $commUser integer Id del usuario
   *
   * @return integer
   */
  public function getStatus( $resId, $commUser ) {
    if( !is_array( $resId ) ) {
      $status = ( $this->getStatusInfo( $resId, $commUser ) ) ? 1 : 0;
    }
    else {
      $status = array();
      $commResources = $this->getAllCommunity( $commUser );
      foreach( $resId as $id ) {
        $status[ $id ] = ( in_array( $id, $commResources ) ) ? 1 : 0;
      }
    }

    return $status;
  }


  /**
   * Establece el estado de community indicado en el recurso
   *
   * @param $resId integer Id del recurso
   * @param $status integer Estado 0-1. Se admite false-true
   *
   * @return bool
   */
  public function setStatus( $resId, $newStatus, $commUser ) {
    $newStatus = ( $newStatus ) ? 1 : 0;

    $commData = $this->getStatusInfo( $resId, $commUser );
    $preStatus = ( $commData ) ? 1 : 0;
    if( $preStatus === 1 && $newStatus === 0 ) {
      // Estamos con status 1 y queremos status 0
      $crModel = new CollectionResourcesModel( array( 'id' => $commData['id'] ) );
      // error_log( 'Borrando crModel' );
      $crModel->delete();
    }

    if( $preStatus === 0 && $newStatus === 1 ) {
      // Estamos con status 0 y queremos status 1
      $colId = $this->getCollectionId( $commUser );

      if( !$colId ) {
        // Hai que crear toda la estructura: resource rtypeCommunity, collection, resource-collection
        $commStructure = $this->newCommunityStructure( $commUser );
        $colId = $commStructure['colId'];
      }

      $crModel = new CollectionResourcesModel( array( 'collection' => $colId, 'resource' => $resId,
        'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
      $crModel->save();
      // error_log( 'Creando crModel' );
    }

    return( $newStatus === $this->getStatus( $resId, $commUser ) );
  }



  public function getCommRTypeId() {
    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeCommunity' ) ) );
    $rTypeObj = ( $rTypeList ) ? $rTypeList->fetch() : false;
    $rTypeId = ( $rTypeObj ) ? $rTypeObj->getter( 'id' ) : false;

    return( $rTypeId );
  }


  public function getCommunityUrl( $commUser ) {
    $commUrl = false;

    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    $commObj = ( $commList ) ? $commList->fetch() : false;
    if( $commObj ) {
      $resId = $commObj->getter('resource');
    }
    else {
      $commStructure = $this->newCommunityStructure( $commUser );
      $resId = $commStructure['resId'];
    }

    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();
    $commUrl = $resCtrl->getUrlAlias( $resId );

    return $commUrl;
  }


  public function newCommunityStructure( $commUser ) {
    // Hai que crear toda la estructura: resource rtypeCommunity, collection, resource-collection

    // Creamos el recurso de community
    $commResInfo = array( 'rTypeId' => $this->getCommRTypeId(), 'user' => $commUser,
      'title' => 'Comm. user '.$commUser,
      'title_'.Cogumelo::getSetupValue( 'lang:default' ) => 'Comm. user '.$commUser,
      'published' => true, 'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) );
    $resModel = new ResourceModel( $commResInfo );
    $resModel->save();
    $resId = $resModel->getter('id');

    // Creamos las URLs del recurso de community
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();
    $allLang = Cogumelo::getSetupValue( 'lang:available' );
    foreach( $allLang as $langKey => $langInfo ) {
      $commUrl = $resCtrl->getUrlByPattern( $resId, $langKey, $resId );
      $resCtrl->setUrl( $resId, $langKey, $commUrl );
    }

    // Creamos el modelo de community
    $commModel = new RExtCommunityModel( array( 'resource' => $resId, 'user' => $commUser,
      'share' => false, 'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
    $commModel->save();
    $commId = $commModel->getter('id');

    return( array( 'commId' => $commId, 'resId' => $resId ) );
  }

} // class RExtCommunityController
