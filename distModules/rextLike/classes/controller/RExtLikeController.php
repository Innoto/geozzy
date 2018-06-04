<?php
geozzy::load( 'controller/RExtController.php' );

class RExtLikeController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl = false ) {
    error_log(__METHOD__);

    // Este RExt funciona tambien como modulo "autonomo"
    if( $defRTypeCtrl !== false ) {
      parent::__construct( $defRTypeCtrl, new rextLike(), 'rExtLike_' );
    }

    // $cache = Cogumelo::getSetupValue('cache:RExtLikeController');
    // if( $cache !== null ) {
    //   Cogumelo::log( __METHOD__.' ---- ESTABLECEMOS CACHE A '.$cache, 'cache' );
    //   $this->cacheQuery = $cache;
    // }
    /**
     *  TODO - IMPORTANTE: Los modelos de Likes hay que manejarlos SIN CACHEO
     */
  }


  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    error_log(__METHOD__);

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
      // $rExtData = $this->getStatusInfo( $resId, $userId );
      // // $likeObj->getAllData( 'onlydata' )
      $rExtData = [ 'count' => 0, 'value' => 0 ];

      $likeUsers = $this->getLikeUsers( $resId );
      if( !empty( $likeUsers ) ) {
        $rExtData['count'] = sizeof( $likeUsers );
        $rExtData['value'] = in_array( $userId, $likeUsers ) ? 1 : 0;
      }
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
  public function getViewBlockInfo( $resId = false ) {
    error_log(__METHOD__);

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    $resId = $this->defResCtrl->resObj->getter('id');

    $rExtViewBlockInfo['template']['full'] = new Template();
    $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
    $rExtViewBlockInfo['template']['full']->assign( 'resId', $resId );
    $rExtViewBlockInfo['template']['full']->addClientStyles( 'styles/rExtLike.less', 'rextLike' );
    $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextLike' );

    return $rExtViewBlockInfo;
  }


  /***************/
  /***  UTILS  ***/
  /***************/


  /**
   * Carga los datos de todos los likes de un usuario
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getAllLike( $likeUser ) {
    error_log(__METHOD__);

    $likeData = false;

    $likeModel = new LikeListViewModel();
    $likeList = $likeModel->listItems([
      'filters' => [ 'user' => $likeUser ],
      'cache' => false // Sin cache para actualizacion continua
    ]);
    $likeObj = ( $likeList ) ? $likeList->fetch() : false;
    if( $likeObj ) {
      $likeData = ( $likeObj->getter('resourceList') ) ? explode( ',', $likeObj->getter('resourceList') ) : [];
    }

    return $likeData;
  }

  /**
   * Localiza el id de la coleccion de likes (false si no existe)
   *
   * @param $likeUser integer
   *
   * @return integer
   */
  public function getCollectionId( $likeUser ) {
    error_log(__METHOD__);

    $likeObj = $this->getLikesCollection( $likeUser );
    $colId = is_object( $likeObj ) ? $likeObj->getter( 'colId' ) : false;

    return $colId;
  }


  public function getLikesCollection( $likeUser ) {
    error_log(__METHOD__);

    $likeObj = false;

    $likeModel = new LikeViewModel();
    $likeList = $likeModel->listItems([
      'filters' => [ 'user' => $likeUser ],
      'cache' => false // Sin cache para actualizacion continua
    ]);
    $likeObj = is_object( $likeList ) ? $likeList->fetch() : false;

    return $likeObj;
  }


  /**
   * Carga los datos del like (false si no existe)
   *
   * @param $resId integer Id del recurso
   * @param $likeUser integer Id del usuario
   *
   * @return array OR false
   */
  public function getStatusInfo( $resId, $likeUser ) {
    error_log(__METHOD__);

    $likeData = false;

    $likeModel = new LikeViewModel();
    $likeList = $likeModel->listItems([
      'filters' => [ 'resource' => $resId, 'user' => $likeUser ],
      'cache' => false // Sin cache para actualizacion continua
    ]);
    $likeObj = ( $likeList ) ? $likeList->fetch() : false;
    $likeData = ( $likeObj ) ? $likeObj->getAllData( 'onlydata' ) : false;

    return $likeData;
  }


  public function getLikeUsers( $resId ) {
    error_log(__METHOD__);

    $likeUsers = false;

    $likeModel = new LikeUsersListViewModel();
    $likeList = $likeModel->listItems([
      'filters' => [ 'id' => $resId ],
      'cache' => false // Sin cache para actualizacion continua
    ]);
    $likeObj = ( $likeList ) ? $likeList->fetch() : false;
    $likeData = ( $likeObj ) ? $likeObj->getter('userList') : false;

    $likeUsers = !empty( $likeData ) ? explode( ',', $likeData ) : false;

    return $likeUsers;
  }


  /**
   * Carga el estado del like
   *
   * @param $resId integer Id del recurso
   * @param $likeUser integer Id del usuario
   *
   * @return integer
   */
  public function getStatus( $resId, $likeUser ) {
    error_log(__METHOD__);

    if( !is_array( $resId ) ) {
      $status = ( $this->getStatusInfo( $resId, $likeUser ) ) ? 1 : 0;
    }
    else {
      $status = [];
      $likeResources = $this->getAllLike( $likeUser );
      foreach( $resId as $id ) {
        $status[ $id ] = ( in_array( $id, $likeResources ) ) ? 1 : 0;
      }
    }

    return $status;
  }


  /**
   * Establece el estado de like indicado en el recurso
   *
   * @param $resId integer Id del recurso
   * @param $status integer Estado 0-1. Se admite false-true
   *
   * @return bool
   */
  public function setStatus( $resId, $newStatus, $likeUser ) {
    error_log(__METHOD__);

    $result = [ 'count' => 0 ];
    $preStatus = 0;

    $newStatus = !empty( $newStatus ) ? 1 : 0;
    $result['value'] = $newStatus;

    $likeUsers = $this->getLikeUsers( $resId );
    if( !empty( $likeUsers ) ) {
      $result['count'] = sizeof( $likeUsers );
      $preStatus = in_array( $likeUser, $likeUsers ) ? 1 : 0;
    }


    if( $preStatus !== $newStatus ) {
      $likeObj = $this->getLikesCollection( $likeUser );

      if( $newStatus === 0 && $likeObj ) {
        // Estamos con status 1 y queremos status 0
        $crModel = new CollectionResourcesModel( [ 'id' => $likeObj->getter('id') ] );
        // error_log( 'Borrando crModel' );
        $crModel->delete();

        if( $result['count'] > 0 ) {
          $result['count']--;
        }
      }
      else {
        // Estamos con status 0 y queremos status 1
        if( $likeObj ) {
          $likesColId = $likeObj->getter('colId');
        }
        else {
          // Hai que crear toda la estructura: resource rtypeLike, collection, resource-collection
          $likesStructure = $this->newLikeStructure( $likeUser );
          $likesColId = $likesStructure['colId'];
        }

        $crModel = new CollectionResourcesModel( array( 'collection' => $likesColId, 'resource' => $resId,
          'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
        $crModel->save();
        // error_log( 'Creando crModel' );
        
        $result['count']++;
      }
    }


    // $likeData = $this->getStatusInfo( $resId, $likeUser );
    // $preStatus = ( $likeData ) ? 1 : 0;
    // if( $preStatus === 1 && $newStatus === 0 ) {
    //   // Estamos con status 1 y queremos status 0
    //   $crModel = new CollectionResourcesModel( [ 'id' => $likeData['id'] ] );
    //   // error_log( 'Borrando crModel' );
    //   $crModel->delete();
    // }

    // if( $preStatus === 0 && $newStatus === 1 ) {
    //   // Estamos con status 0 y queremos status 1
    //   $colId = $this->getCollectionId( $likeUser );

    //   if( !$colId ) {
    //     // Hai que crear toda la estructura: resource rtypeLike, collection, resource-collection
    //     $likesStructure = $this->newLikeStructure( $likeUser );
    //     $colId = $likesStructure['colId'];
    //   }

    //   $crModel = new CollectionResourcesModel( array( 'collection' => $colId, 'resource' => $resId,
    //     'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
    //   $crModel->save();
    //   // error_log( 'Creando crModel' );
    // }

    // $result = ( $newStatus === $this->getStatus( $resId, $likeUser ) );


    error_log( json_encode($result) );
    return $result;
  }


  public function newLikeStructure( $likeUser ) {
    error_log(__METHOD__);

    // Hai que crear toda la estructura: resource rtypeLike, collection, resource-collection

    // Creamos el recurso de likes
    $likesResInfo = array( 'rTypeId' => $this->getLikeRTypeId(), 'user' => $likeUser,
      'title' => 'Likes. user '.$likeUser,
      'title_'.Cogumelo::getSetupValue( 'lang:default' ) => 'Likes. user '.$likeUser,
      'published' => true, 'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) );
    $resModel = new ResourceModel( $likesResInfo );
    $resModel->save();
    $resMainId = $resModel->getter('id');

    // Creamos las URLs del recurso de likes
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();
    $allLang = Cogumelo::getSetupValue( 'lang:available' );
    foreach( $allLang as $langKey => $langInfo ) {
      $likesUrl = $resCtrl->getUrlByPattern( $resMainId, $langKey, $resMainId );
      $resCtrl->setUrl( $resMainId, $langKey, $likesUrl );
    }

    // Creamos la coleccion de likes
    $colModel = new CollectionModel( array( 'idName' => 'LIKE_'.$likeUser, 'collectionType' => 'likes',
      'timeCreation' => gmdate( 'Y-m-d H:i:s', time() ) ) );
    $colModel->save();
    $colId = $colModel->getter('id');

    // Relacionamos el recurso de likes y su coleccion
    $rcModel = new ResourceCollectionsModel( array( 'resource' => $resMainId, 'collection' => $colId ) );
    $rcModel->save();

    return( array( 'likesId' => $resMainId, 'colId' => $colId ) );
  }



  public function getLikeRTypeId() {
    error_log(__METHOD__);

    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems( [ 'filters' => [ 'idName' => 'rtypeLikes' ], 'cache' => $this->cacheQuery ] );
    $rTypeObj = is_object( $rTypeList ) ? $rTypeList->fetch() : false;
    $rTypeId = is_object( $rTypeObj ) ? $rTypeObj->getter( 'id' ) : false;

    return( $rTypeId );
  }


  public function getLikeUrl( $likeUser ) {
    error_log(__METHOD__);

    $likesUrl = false;

    $likeModel = new LikeListViewModel();
    $likeList = $likeModel->listItems( [ 'filters' => [ 'user' => $likeUser ], 'cache' => $this->cacheQuery ] );
    $likeObj = ( $likeList ) ? $likeList->fetch() : false;
    if( $likeObj ) {
      $likesId = $likeObj->getter('id');
    }
    else {
      $likesStructure = $this->newLikeStructure( $likeUser );
      $likesId = $likesStructure['likesId'];
    }
    geozzy::load( 'controller/ResourceController.php' );
    $resCtrl = new ResourceController();

    $likesUrl = $resCtrl->getUrlAlias( $likesId );

    return $likesUrl;
  }



} // class RExtLikeController
