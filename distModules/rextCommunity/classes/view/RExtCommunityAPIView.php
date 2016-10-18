<?php

Cogumelo::load( 'coreView/View.php' );
rextCommunity::load( 'controller/RExtCommunityController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtCommunityAPIView extends View {

  var $userId = false;
  var $userSession = false;
  var $extendAPIAccess = false;

  public function __construct( $base_dir ) {
    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();

    if( isset( $userInfo['data']['id'] ) ) {
      $this->userId = $userInfo['data']['id'];
      $this->userSession = $userInfo;
    }

    if( GEOZZY_API_ACTIVE === true && $this->userSession && $this->userSession['data']['login'] === 'superAdmin' ) {
      $this->extendAPIAccess = true;
    }

    $this->apiParams = array( 'cmd', 'status' );
    $this->apiCommands = array( 'setStatus', 'getStatus', 'getCommunityUrl', 'listComm', 'listResources', 'listUsers' );
    $this->apiFilters = array( 'communityId', 'resourceId', 'userId' );

    parent::__construct( $base_dir ); // Esto lanza el accessCheck
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {
    return( $this->userId !== false );
    // return( GEOZZY_API_ACTIVE === true );
  }


  /**
   * API router
   */
  public function apiQuery() {
    $result = null;
    $error = false;

    $command = ( isset( $_POST['cmd'] ) && in_array( $_POST['cmd'], $this->apiCommands ) ) ? $_POST['cmd'] : null;

    $status = null;
    if( isset( $_POST['status'] ) ) {
      $status = ( $_POST['status'] ) ? 1 : 0; // Manejamos status como 0-1 y no false-true
    }

    $filters = array();
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
      case 'listComm':
        $result = $this->apiListComm( $filters );
        break;
      case 'listResources':
        $result = $this->apiListResources( $filters );
        break;
      case 'listUsers':
        $result = $this->apiListUsers( $filters );
        break;
      case 'getCommunityUrl':
        $result = $this->getCommunityUrl();
        break;
      default:
        $result = array(
          'result' => 'error',
          'msg' => 'Command error'
        );
        break;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $result );
  }


  public function apiSetStatus( $status, $resourceId, $userId ) {
    $result = null;

    // Si no hay usuario, el de session
    if( $userId === null && $this->userId !== false ) {
      $userId = strval( $this->userId );
    }

    // Solo pueden acceder a otros usuarios si $this->extendAPIAccess
    if( !$this->extendAPIAccess && $userId !== strval( $this->userId ) ) {
      $userId = null;
    }

    if( $status !== null && $resourceId !== null && $userId !== null ) {
      $commCtrl = new RExtCommunityController();
      if( $commCtrl->setStatus( $resourceId, $status, $userId ) ) {
        $result = array(
          'result' => 'ok',
          'status' => $status
        );
      }
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Parameters error'
      );
    }

    return $result;
  }


  public function apiGetStatus( $resourceId, $userId ) {
    $result = null;

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

      $commCtrl = new RExtCommunityController();
      $result = array(
        'result' => 'ok',
        'status' => $commCtrl->getStatus( $resourceId, $userId )
      );
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Parameters error'
      );
    }

    return $result;
  }


  public function apiListComm( $filters ) {
    $result = null;

    $access = $this->extendAPIAccess;

    if( !$access && $this->userId !== false ) {
      if( $filters['userId'] === null || $filters['userId'] === strval( $this->userId ) ) {
        $filters['userId'] = $this->userId;
        $access = true;
      }
    }

    // Solo se puede acceder si $this->extendAPIAccess o un usuario a su propios datos
    if( $access ) {
      $listFilters = array();
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
      if( $filters['communityId'] !== null ) {
        $commArray = explode( ',', $filters['communityId'] );
        if( count( $commArray ) > 1 ) {
          $listFilters['idIn'] = $commArray;
        }
        else {
          $listFilters['id'] = $filters['communityId'];
        }
      }

      $commModel = new CommunityListViewModel();
      $commList = $commModel->listItems( array( 'filters' => $listFilters ) );
      if( $commList ) {
        $result = array(
          'result' => 'ok',
          'community' => array()
        );
        while( $commObj = $commList->fetch() ) {
          $commData = $commObj->getAllData( 'onlydata' );
          $result['community'][ $commData['id'] ] = array(
            'id' => $commData['id'],
            'user' => $commData['user'],
            'resourceList' => ( isset( $commData['resourceList'] ) ) ? explode( ',', $commData['resourceList'] ) : array(),
            'timeCreation' => $commData['timeCreation'],
            // 'colId' => $commData['colId'],
            'published' => $commData['published']
          );
        }
      }
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Access denied'
      );
    }

    return $result;
  }


  public function apiListResources( $filters ) {
    $result = null;

    $access = $this->extendAPIAccess;

    if( !$access && $this->userId !== false ) {
      if( $filters['userId'] === null || $filters['userId'] === strval( $this->userId ) ) {
        $filters['userId'] = $this->userId;
        $access = true;
      }
    }

    // Solo se puede acceder si $this->extendAPIAccess o un usuario a su propios datos
    if( $access ) {
      $listFilters = array();
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
      if( $filters['communityId'] !== null ) {
        $commArray = explode( ',', $filters['communityId'] );
        if( count( $commArray ) > 1 ) {
          $listFilters['idIn'] = $commArray;
        }
        else {
          $listFilters['id'] = $filters['communityId'];
        }
      }

      $commModel = new CommunityListViewModel();
      $commList = $commModel->listItems( array( 'filters' => $listFilters ) );
      if( $commList ) {
        $result = array(
          'result' => 'ok',
          'resource' => array()
        );
        while( $commObj = $commList->fetch() ) {
          $communityId = $commObj->getter( 'id' );
          $resourceList = $commObj->getter('resourceList');
          if( $resourceList ) {
            $resourceList = explode( ',', $resourceList );
            foreach( $resourceList as $resourceId ) {
              if( $filters['resourceId'] === null || $filters['resourceId'] === $resourceId ) {
                if( !isset( $result['resource'][ $resourceId ] ) ) {
                  $result['resource'][ $resourceId ] = array(
                    'id' => $resourceId,
                    'community' => array()
                  );
                }
                $result['resource'][ $resourceId ]['community'][] = $communityId;
              }
            }
          }
          // error_log( 'resourceList: '.$commObj->getter('resourceList') );
        }
      }
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Access denied'
      );
    }

    return $result;
  }


  public function apiListUsers( $filters ) {
    $result = null;

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->extendAPIAccess ) {
      $listFilters = array();
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
      if( $filters['communityId'] !== null ) {
        $commArray = explode( ',', $filters['communityId'] );
        if( count( $commArray ) > 1 ) {
          $listFilters['idIn'] = $commArray;
        }
        else {
          $listFilters['id'] = $filters['communityId'];
        }
      }

      $commModel = new CommunityListViewModel();
      $commList = $commModel->listItems( array( 'filters' => $listFilters ) );
      if( $commList ) {
        $result = array(
          'result' => 'ok',
          'user' => array()
        );
        while( $commObj = $commList->fetch() ) {
          $communityId = $commObj->getter( 'id' );
          $userId = $commObj->getter( 'user' );

          if( !isset( $result['user'][ $userId ] ) ) {
            $result['user'][ $userId ] = array(
              'id' => $userId,
              'community' => array()
            );
          }
          $result['user'][ $userId ]['community'][] = $communityId;
        }
      }
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Access denied'
      );
    }

    return $result;
  }


  public function getCommunityUrl() {
    $result = null;

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->userId !== false ) {
      $commCtrl = new RExtCommunityController();
      $result = array(
        'result' => 'ok',
        'status' => $commCtrl->getCommunityUrl( $this->userId )
      );
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Access denied'
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
  "resourcePath": "/community.json",
  "basePath": "/api",
  "apis": [
    {
      "operations": [
        {
          "errorResponses": [
            {
              "reason": "Community info",
              "code": 200
            },
            {
              "reason": "Community not found",
              "code": 404
            }
          ],
          "httpMethod": "POST",
          "nickname": "community",
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
              "description": "Community status ( 0 | 1 )",
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
              "name": "communityId",
              "description": "Filter by Community Id or array",
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
          "summary": "Fetches community data"
        }
      ],
      "path": "/community",
      "description": ""
    }
  ]
}
<?php
  } // function apiInfoJson()

}

