<?php
geozzy::load( 'controller/RExtController.php' );

class RExtCommunityController extends RExtController implements RExtInterface {

  var $userId = false;
  var $userSession = false;

  public function __construct( $defRTypeCtrl = false ) {
    // Este RExt funciona tambien como modulo "autonomo"
    if( $defRTypeCtrl !== false ) {
      parent::__construct( $defRTypeCtrl, new rextCommunity(), 'rExtCommunity_' );
    }

    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();

    if( isset( $userInfo['data']['id'] ) ) {
      $this->userId = $userInfo['data']['id'];
      $this->userSession = $userInfo;
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


  /**
   * Preparamos la visualización de la comunidad de un usuario
   *
   * @return String HTML
   */
  public function getOtherCommunityView( $otherUserId ) {
    $viewHtml = '';

    $template = null;

    if( $this->userId ) {
      $commObj = $this->getCommunityObj( $otherUserId );
      if( $commObj && $commObj->getter( 'share' ) ) {
        $otherUserInfo = $this->getUsersInfo( $otherUserId, $getFavs = true );

        $template = new Template();
        $template->assign( 'myUserId', $this->userId );
        $template->assign( 'otherUserInfo', $otherUserInfo[ $otherUserId ] );
        $template->setTpl( 'otherUserCommunityViewBlock.tpl', 'rextCommunity' );

        $viewHtml = $template->execToString();
      }
    }

    return $viewHtml;
  }




  /***************/
  /***  UTILS  ***/
  /***************/

  public function getCommunityObj( $commUser = false ) {
    $commObj = false;

    $commUser = ($commUser !== false) ? $commUser : $this->userId;

    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    $commObj = ( $commList ) ? $commList->fetch() : false;

    return $commObj;
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

    /*
    $commModel = new RExtCommunityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'resource' => $resId, 'user' => $commUser ) ) );
    $commObj = ( $commList ) ? $commList->fetch() : false;
    */
    $commObj = $this->getCommunityObj( $commUser );
    $commData = ( $commObj ) ? $commObj->getAllData( 'onlydata' ) : false;

    return $commData;
  }


  /**
   * Establece el estado de community indicado en el usuario
   *
   * @param $status string Estado 0-1. Se admite false-true
   * @param $commUser integer Id del recurso
   *
   * @return bool
   */
  public function setShare( $status, $commUser = false ) {
    $statusResult = false;

    $commObj = $this->getCommunityObj( $commUser );
    if( $commObj ) {
      $commObj->setter( 'share', ($status) ? 1 : 0 );
      $commObj->save();
      $statusResult = ( $commObj->getter( 'share' ) ) ? 1 : 0;
    }

    return( $statusResult );
  }


  /**
   * Establece la cuenta de Facebook en community indicado en el usuario
   *
   * @param $status string Estado 0-1. Se admite false-true
   * @param $commUser integer Id del recurso
   *
   * @return bool
   */
  public function setSocial( $socialNet, $account, $commUser = false ) {
    $accountResult = false;

    // error_log("setSocial( $socialNet, $account, $commUser )");

    $socialNet = strtolower( trim( $socialNet ) );
    $account = ( $socialNet === 'twitter' ) ? trim( $account, ' @' ) : trim( $account );

    if( $socialNet === 'facebook' || $socialNet === 'twitter' ) {
      $commObj = $this->getCommunityObj( $commUser );
      if( $commObj ) {
        $commObj->setter( $socialNet, ( $account !== '' ) ? $account : null );
        $commObj->save();
        $accountResult = $commObj->getter( $socialNet );
        $accountResult = ( $accountResult !== null ) ? $accountResult : '';
      }
    }

    return( $accountResult );
  }

  /**
   * Establece el estado de follow indicado en el usuario
   *
   * @param $status string Estado 0-1. Se admite false-true
   * @param $commUser integer Id del recurso
   *
   * @return bool
   */
  public function setFollow( $status, $followUser, $commUser = false ) {
    $statusResult = false;

    $commUser = ($commUser !== false) ? $commUser : $this->userId;

    $followModel = new RExtCommunityFollowModel();
    $followList = $followModel->listItems( array( 'filters' => array(
      'user' => $commUser, 'follow' => $followUser
    ) ) );
    $followObj = ( $followList ) ? $followList->fetch() : false;

    if( $status ) {
      if( !$followObj ) {
        $followModel = new RExtCommunityFollowModel( array(
          'user' => $commUser,
          'follow' => $followUser,
          'timeCreation' => gmdate( "Y-m-d H:i:s", time() )
        ));
        $followModel->save();
      }
    }
    else {
      if( $followObj ) {
        $followObj->delete();
      }
    }

    // No se virifica
    $statusResult = array(
      'status' => $status,
      'user' => $followUser
    );

    return( $statusResult );
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

  public function getCommRTypeId() {
    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( array( 'filters' => array( 'idName' => 'rtypeCommunity' ) ) );
    $rTypeObj = ( $rTypeList ) ? $rTypeList->fetch() : false;
    $rTypeId = ( $rTypeObj ) ? $rTypeObj->getter( 'id' ) : false;

    return( $rTypeId );
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



  /**
   * UTILS
   */
  public function getCommData( $commId ) {
    $commData = false;

    $commModel = new CommunityViewModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'resourceMain' => $commId ) ) );
    if( $commList ) {
      $commData = array();
      while( $commObj = $commList->fetch() ) {
        $allData = $commObj->getAllData( 'onlydata' );
        if( isset( $allData['id'] ) && $allData['id'] !== null ) { // Por si hay col. pero no recursos
          $commData[] = $allData;
        }
      }
    }

    return $commData;
  }

  public function getCommFollows( $commUser ) {
    // error_log( "getCommFollows( $commUser )" );
    $commFollow = false;

    $commModel = new RExtCommunityFollowModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    if( $commList ) {
      while( $commObj = $commList->fetch() ) {
        $commFollow[] = $commObj->getter( 'follow' );
      }
    }

    return $commFollow;
  }

  public function getCommPropose( $commUser, $commFollows = false ) {
    // error_log( "getCommPropose( $commUser )" );
    $commPropose = false;

    $ignore = is_array( $commFollows ) ? $commFollows : array();
    $ignore[] = $commUser;

    /*
    ** APAÑO TEMPORAL !!!
    */
    /*
    $userModel = new UserModel();
    $userList = $userModel->listItems();
    while( $userObj = $userList->fetch() ) {
      $userId = $userObj->getter('id');
      if( !in_array( $userId, $ignore ) ) {
        $commPropose[] = $userId;
      }
    }
    */

    $afinModel = new RExtCommunityAffinityUserModel();
    $afinList = $afinModel->listItems( array( 'filters' => array( 'id' => $commUser ) ) );
    $afinObj = ( $afinList ) ? $afinList->fetch() : false;
    if( $afinObj ) {
      $afinCSV = $afinObj->getter('affinityList');
      if( $afinCSV ) {
        $commPropose = array_diff( explode( ',', $afinCSV ), $ignore );
      }
    }

    error_log( 'commPropose = '. json_encode( $commPropose ) );

    return $commPropose;
  }

  public function getUsersInfo( $usersIds, $getFavs = false ) {
    $usersInfo = array();

    $usersIds = is_array( $usersIds ) ? $usersIds : array( $usersIds );

    $favCtrl = new RExtFavouriteController();
    $userModel = new UserModel();
    $resModel = new ResourceModel();

    $userList = $userModel->listItems( array( 'filters' => array( 'idIn' => $usersIds, 'active' => 1 ) ) );
    while( $userObj = $userList->fetch() ) {
      $userId = $userObj->getter('id');
      $usersInfo[ $userId ] = array(
        'id' => $userObj->getter('id'),
        'login' => $userObj->getter('login'),
        'name' => $userObj->getter('name'),
        'surname' => $userObj->getter('surname'),
        'email' => $userObj->getter('email'),
        'description' => $userObj->getter('description'),
        'avatarFileId' => $userObj->getter('avatar')
      );

      $commObj = $this->getCommunityObj( $userId );
      $usersInfo[ $userId ]['comm'] = (!$commObj) ? false : array(
        'share' => ( $commObj->getter( 'share' ) ) ? 1 : 0,
        'facebook' => ( $commObj->getter('facebook') !== null ) ? $commObj->getter('facebook') : '',
        'twitter' => ( $commObj->getter('twitter') !== null ) ? $commObj->getter('twitter') : ''
      );

      if( $getFavs ) {
        $usersInfo[ $userId ]['favList'] = $favCtrl->getAllFavourites( $userId );
        $usersInfo[ $userId ]['favs'] = [];
        $resList = $resModel->listItems( array( 'filters' => array(
          'idIn' => $usersInfo[ $userId ]['favList'], 'published' => 1 ) ) );
        while( $resObj = $resList->fetch() ) {
          $usersInfo[ $userId ]['favs'][] = array(
            'id' => $resObj->getter('id'),
            'image' => $resObj->getter('image')
          );
        }
      }
    }

    return $usersInfo;
  }


} // class RExtCommunityController
