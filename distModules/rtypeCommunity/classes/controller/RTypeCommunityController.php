<?php

class RTypeCommunityController extends RTypeController implements RTypeInterface {

  public function __construct( $defResCtrl ){
    parent::__construct( $defResCtrl, new rtypeCommunity() );
  }


  /**
   * Alteramos el objeto form. del recursoBase para adaptarlo a las necesidades del RType
   *
   * @param $form FormController Objeto form. del recursoBase
   *
   * @return array $rTypeFieldNames
   */
  public function manipulateForm( FormController $form ) {
  } // function manipulateForm()


  /**
   * Preparamos los datos para visualizar el formulario del Recurso
   *
   * @param $form FormController
   *
   * @return Array $formBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array, 'ext' => array }
   */
  public function getFormBlockInfo( FormController $form ) {
    return false;
  }


  /**
   * Validaciones extra previas a usar los datos del recurso
   *
   * @param $form FormController Objeto form. del recurso
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edicion-Borrado de los elementos del recurso segun el RType
   *
   * @param $form FormController Objeto form. del recurso
   * @param $resource ResourceModel Objeto form. del recurso
   */
  // parent::resFormProcess( $form, $resource );


  /**
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource );


  /**
   * Preparamos los datos para visualizar el Recurso con sus cambios y sus extensiones
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'ext' => array }
   */
  public function getViewBlockInfo() {

    // Preparamos los datos para visualizar el Recurso con sus extensiones
    $viewBlockInfo = parent::getViewBlockInfo();

    // $template = new Template();
    $template = $viewBlockInfo['template']['full'];
    $template->setTpl( 'rTypeViewBlock.tpl', 'rtypeCommunity' );

    $template->addClientScript( 'js/rExtCommunityController.js', 'rextCommunity' );

    $myInfo = $this->getUsersInfo( $viewBlockInfo['data']['user'] )[ $viewBlockInfo['data']['user'] ];
    $template->assign( 'myInfo', $myInfo );

    $commFollows = $this->getCommFollows( $viewBlockInfo['data']['user'] );
    $commFollowsInfo = ( $commFollows ) ? $this->getUsersInfo( $commFollows ) : false;
    $template->assign( 'commFollowsInfo', $commFollowsInfo );

    $commPropose = $this->getCommPropose( $viewBlockInfo['data']['user'], $commFollows );
    $commProposeInfo = ( $commPropose ) ? $this->getUsersInfo( $commPropose ) : false;
    $template->assign( 'commProposeInfo', $commProposeInfo );

    // $template->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );
    $viewBlockInfo['template']['full'] = $template;

    return $viewBlockInfo;
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
    error_log( "getCommFollows( $commUser )" );
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
    error_log( "getCommPropose( $commUser )" );
    $commPropose = false;

    $ignore = is_array( $commFollows ) ? $commFollows : array();
    $ignore[] = $commUser;

    /*
    ** APAÑO TEMPORAL !!!
    */
    $userModel = new UserModel();
    $userList = $userModel->listItems();
    while( $userObj = $userList->fetch() ) {
      $userId = $userObj->getter('id');
      if( !in_array( $userId, $ignore ) ) {
        $commPropose[] = $userId;
      }
    }

    /*
    $commModel = new RExtCommunityAffinityModel();
    $commList = $commModel->listItems( array( 'filters' => array( 'user' => $commUser ) ) );
    if( $commList ) {
      while( $commObj = $commList->fetch() ) {
        $commPropose[] = $commObj->getter( 'Propose' );
      }
    }
    */

    return $commPropose;
  }

  public function getUsersInfo( $usersIds ) {
    $usersInfo = array();

    $usersIds = is_array( $usersIds ) ? $usersIds : array( $usersIds );

    $favCtrl = new RExtFavouriteController();
    $userModel = new UserModel();
    $resModel = new ResourceModel();
    $commModel = new RExtCommunityModel();

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
        'avatarFileId' => $userObj->getter('avatar'),
        'favList' => $favCtrl->getAllFavourites( $userId )
      );

      $commList = $commModel->listItems( array( 'filters' => array( 'user' => $userId ) ) );
      $commObj = ($commList) ? $commList->fetch() : false;
      $usersInfo[ $userId ]['comm'] = (!$commObj) ? false : array(
        'facebook' => $commObj->getter('facebook'),
        'twitter' => $commObj->getter('twitter')
      );

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

    return $usersInfo;
  }

} // class RTypeBlogController
