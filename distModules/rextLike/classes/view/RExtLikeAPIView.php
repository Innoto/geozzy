<?php
Cogumelo::load( 'coreView/View.php' );
rextLike::load( 'controller/RExtLikeController.php' );


class RExtLikeAPIView extends View {

  var $userId = false;
  var $userSession = false;
  var $extendAPIAccess = false;

  public function __construct() {
    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();

    if( isset( $userInfo['data']['id'] ) ) {
      $this->userId = $userInfo['data']['id'];
      $this->userSession = $userInfo;
    }

    if( $this->userSession && $this->userSession['data']['login'] === 'superAdmin' ) {
      $this->extendAPIAccess = true;
    }

    $this->apiParams = array( 'cmd', 'status' );
    $this->apiCommands = array( 'setStatus', 'getStatus', 'getLikeUrl', 'listLikes', 'listResources', 'listUsers' );
    $this->apiFilters = array( 'likeId', 'resourceId', 'userId' );

    parent::__construct(); // Esto lanza el accessCheck
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {
    return( $this->userId !== false );
  }


  /**
   * API router
   */
  public function apiQuery() {
    $result = [ 'result' => 'error', 'msg' => __('Command error') ];

    $command = ( isset( $_POST['cmd'] ) && in_array( $_POST['cmd'], $this->apiCommands ) ) ? $_POST['cmd'] : null;

    $status = null;
    if( isset( $_POST['status'] ) ) {
      $status = ( $_POST['status'] ) ? 1 : 0; // Manejamos status como 0-1 y no false-true
    }

    $filters = [];
    foreach( $this->apiFilters as $key ) {
      $filters[ $key ] = ( isset( $_POST[ $key ] ) ) ? $_POST[ $key ] : null;
    }

    switch( $command ) {
      case 'setStatus':
        $result = $this->apiSetStatus( $status, $filters['resourceId'], $filters['userId'] );
        break;
      case 'getStatus':
        $result = $this->apiGetStatus( $filters['resourceId'], $filters['userId'] );
        break;
      case 'listLikes':
        $result = $this->apiListLikes( $filters );
        break;
      case 'listResources':
        $result = $this->apiListResources( $filters );
        break;
      case 'listUsers':
        $result = $this->apiListUsers( $filters );
        break;
      case 'getLikeUrl':
        $result = $this->getLikeUrl();
        break;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $result );
  }


  public function apiSetStatus( $status, $resourceId, $userId ) {
    // error_log( __METHOD__.': '.$status.' - '.$resourceId.' - '.$userId );
    $result = [ 'result' => 'error', 'msg' => __('Parameter error') ];

    // Si no hay usuario, el de session
    if( $userId === null && $this->userId !== false ) {
      $userId = strval( $this->userId );
    }

    // Solo pueden acceder a otros usuarios si $this->extendAPIAccess
    if( !$this->extendAPIAccess && $userId !== strval( $this->userId ) ) {
      $userId = null;
    }

    if( $status !== null && $resourceId !== null && $userId !== null ) {
      $likeCtrl = new RExtLikeController();
      if( $ctrlResult = $likeCtrl->setStatus( $resourceId, $status, $userId ) ) {
        $result = [
          'result' => 'ok',
          'status' => $ctrlResult
        ];
      }
      else {
        $result = [
          'result' => 'error',
          'status' => __('Error updating')
        ];
      }
    }

    return $result;
  }


  public function apiGetStatus( $resourceId, $userId ) {
    $result = [ 'result' => 'error', 'msg' => __('Parameter error') ];

    // Si no hay usuario, el de session
    if( $userId === null && $this->userId !== false ) {
      $userId = strval( $this->userId );
    }

    // Solo pueden acceder a otros usuarios si $this->extendAPIAccess
    if( !$this->extendAPIAccess && $userId !== strval( $this->userId ) ) {
      $userId = null;
    }

    if( $resourceId !== null && $userId !== null ) {
      $resArray = explode( ',', $resourceId );
      if( count( $resArray ) > 1 ) {
        $resourceId = $resArray;
      }

      $likeCtrl = new RExtLikeController();
      $result = array(
        'result' => 'ok',
        'status' => $likeCtrl->getStatus( $resourceId, $userId )
      );
    }

    return $result;
  }


  public function apiListLikes( $filters ) {
    $result = [ 'result' => 'error', 'msg' => __('Access denied') ];

    $access = $this->extendAPIAccess;

    if( !$access && $this->userId !== false ) {
      if( $filters['userId'] === null || $filters['userId'] === strval( $this->userId ) ) {
        $filters['userId'] = $this->userId;
        $access = true;
      }
    }

    // Solo se puede acceder si $this->extendAPIAccess o un usuario a su propios datos
    if( $access ) {
      $listFilters = [];
      if( $filters['resourceId'] !== null ) {
        $listFilters['inResourceList'] = $filters['resourceId'];
      }
      if( $filters['userId'] !== null ) {
        $userArray = explode( ',', $filters['userId'] );
        if( count( $userArray ) > 1 ) {
          $listFilters['userIn'] = $userArray;
        }
        else {
          $listFilters['user'] = $filters['userId'];
        }
      }
      if( $filters['likeId'] !== null ) {
        $likesArray = explode( ',', $filters['likeId'] );
        if( count( $likesArray ) > 1 ) {
          $listFilters['idIn'] = $likesArray;
        }
        else {
          $listFilters['id'] = $filters['likeId'];
        }
      }

      $likeModel = new LikeListViewModel();
      $likeList = $likeModel->listItems( array( 'filters' => $listFilters ) );
      if( $likeList ) {
        $result = array(
          'result' => 'ok',
          'like' => []
        );
        while( $likeObj = $likeList->fetch() ) {
          $likeData = $likeObj->getAllData( 'onlydata' );
          $result['like'][ $likeData['id'] ] = array(
            'id' => $likeData['id'],
            'user' => $likeData['user'],
            'resourceList' => ( isset( $likeData['resourceList'] ) ) ? explode( ',', $likeData['resourceList'] ) : [],
            'timeCreation' => $likeData['timeCreation'],
            // 'colId' => $likeData['colId'],
            'published' => $likeData['published']
          );
        }
      }
    }

    return $result;
  }


  public function apiListResources( $filters ) {
    $result = [ 'result' => 'error', 'msg' => __('Access denied') ];

    $access = $this->extendAPIAccess;

    if( !$access && $this->userId !== false ) {
      if( $filters['userId'] === null || $filters['userId'] === strval( $this->userId ) ) {
        $filters['userId'] = $this->userId;
        $access = true;
      }
    }

    // Solo se puede acceder si $this->extendAPIAccess o un usuario a su propios datos
    if( $access ) {
      $listFilters = [];
      if( $filters['resourceId'] !== null ) {
        $listFilters['inResourceList'] = $filters['resourceId'];
      }
      if( $filters['userId'] !== null ) {
        $userArray = explode( ',', $filters['userId'] );
        if( count( $userArray ) > 1 ) {
          $listFilters['userIn'] = $userArray;
        }
        else {
          $listFilters['user'] = $filters['userId'];
        }
      }
      if( $filters['likeId'] !== null ) {
        $likesArray = explode( ',', $filters['likeId'] );
        if( count( $likesArray ) > 1 ) {
          $listFilters['idIn'] = $likesArray;
        }
        else {
          $listFilters['id'] = $filters['likeId'];
        }
      }

      $likeModel = new LikeListViewModel();
      $likeList = $likeModel->listItems( array( 'filters' => $listFilters ) );
      if( $likeList ) {
        $result = array(
          'result' => 'ok',
          'resource' => []
        );
        while( $likeObj = $likeList->fetch() ) {
          $likeId = $likeObj->getter( 'id' );
          $resourceList = $likeObj->getter('resourceList');
          if( $resourceList ) {
            $resourceList = explode( ',', $resourceList );
            foreach( $resourceList as $resourceId ) {
              if( $filters['resourceId'] === null || $filters['resourceId'] === $resourceId ) {
                if( !isset( $result['resource'][ $resourceId ] ) ) {
                  $result['resource'][ $resourceId ] = array(
                    'id' => $resourceId,
                    'like' => []
                  );
                }
                $result['resource'][ $resourceId ]['like'][] = $likeId;
              }
            }
          }
          // error_log( 'resourceList: '.$likeObj->getter('resourceList') );
        }
      }
    }

    return $result;
  }


  public function apiListUsers( $filters ) {
    $result = [ 'result' => 'error', 'msg' => __('Access denied') ];

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->extendAPIAccess ) {
      $listFilters = [];
      if( $filters['resourceId'] !== null ) {
        $listFilters['inResourceList'] = $filters['resourceId'];
      }
      if( $filters['userId'] !== null ) {
        $userArray = explode( ',', $filters['userId'] );
        if( count( $userArray ) > 1 ) {
          $listFilters['userIn'] = $userArray;
        }
        else {
          $listFilters['user'] = $filters['userId'];
        }
      }
      if( $filters['likeId'] !== null ) {
        $likesArray = explode( ',', $filters['likeId'] );
        if( count( $likesArray ) > 1 ) {
          $listFilters['idIn'] = $likesArray;
        }
        else {
          $listFilters['id'] = $filters['likeId'];
        }
      }

      $likeModel = new LikeListViewModel();
      $likeList = $likeModel->listItems( array( 'filters' => $listFilters ) );
      if( $likeList ) {
        $result = array(
          'result' => 'ok',
          'user' => []
        );
        while( $likeObj = $likeList->fetch() ) {
          $likeId = $likeObj->getter( 'id' );
          $userId = $likeObj->getter( 'user' );

          if( !isset( $result['user'][ $userId ] ) ) {
            $result['user'][ $userId ] = array(
              'id' => $userId,
              'like' => []
            );
          }
          $result['user'][ $userId ]['like'][] = $likeId;
        }
      }
    }

    return $result;
  }


  public function getLikeUrl() {
    $result = [ 'result' => 'error', 'msg' => __('Access denied') ];

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->userId !== false ) {
      $likeCtrl = new RExtLikeController();
      $result = array(
        'result' => 'ok',
        'status' => $likeCtrl->getLikeUrl( $this->userId )
      );
    }

    return $result;
  }





  /**
   * API info to swagger
   */
  public function apiInfoJson() {
    header('Content-Type: application/json; charset=utf-8');
?>
{
  "resourcePath": "/like.json",
  "basePath": "/api",
  "apis": [
    {
      "operations": [
        {
          "errorResponses": [
            {
              "reason": "Like info",
              "code": 200
            },
            {
              "reason": "Like not found",
              "code": 404
            }
          ],
          "httpMethod": "POST",
          "nickname": "like",
          "parameters": [
            {
              "name": "cmd",
              "description": "Command ( <?php echo implode( ' | ', $this->apiCommands ); ?> )",
              "type": "integer",
              "paramType": "form",
              "required": true
            },
            {
              "name": "status",
              "description": "Like status ( 0 | 1 )",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "resourceId",
              "description": "Filter by Resource Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "likeId",
              "description": "Filter by Like Id or array",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "userId",
              "description": "Filter by User Id or array",
              "type": "integer",
              "paramType": "form",
              "required": false
            }
          ],
          "summary": "Fetches like data"
        }
      ],
      "path": "/like",
      "description": ""
    }
  ]
}
<?php
  } // function apiInfoJson()

}
