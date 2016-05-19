<?php

Cogumelo::load( 'coreView/View.php' );
rextFavourite::load( 'controller/RExtFavouriteController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtFavouriteAPIView extends View {

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
    $this->apiCommands = array( 'setStatus', 'getStatus', 'listFavs', 'listResources', 'listUsers' );
    $this->apiFilters = array( 'favouritesId', 'resourceId', 'userId' );

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
      case 'listFavs':
        $result = $this->apiListFavs( $filters );
        break;
      case 'listResources':
        $result = $this->apiListResources( $filters );
        break;
      case 'listUsers':
        $result = $this->apiListUsers( $filters );
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
      $favCtrl = new RExtFavouriteController();
      if( $favCtrl->setStatus( $resourceId, $status, $userId ) ) {
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
      $favCtrl = new RExtFavouriteController();
      $result = array(
        'result' => 'ok',
        'status' => $favCtrl->getStatus( $resourceId, $userId )
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


  public function apiListFavs( $filters ) {
    $result = null;

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->extendAPIAccess ) {
      $listFilters = array();
      if( $filters['resourceId'] !== null ) {
        $listFilters['inResourceList'] = $filters['resourceId'];
      }
      if( $filters['userId'] !== null ) {
        $listFilters['user'] = $filters['userId'];
      }
      if( $filters['favouritesId'] !== null ) {
        $listFilters['id'] = $filters['favouritesId'];
      }

      $favModel = new FavouritesListViewModel();
      $favList = $favModel->listItems( array( 'filters' => $listFilters ) );
      if( $favList ) {
        $result = array(
          'result' => 'ok',
          'favourites' => array()
        );
        while( $favObj = $favList->fetch() ) {
          $favData = $favObj->getAllData( 'onlydata' );
          $result['favourites'][ $favData['id'] ] = array(
            'id' => $favData['id'],
            'user' => $favData['user'],
            'resourceList' => ( isset( $favData['resourceList'] ) ) ? explode( ',', $favData['resourceList'] ) : array(),
            'timeCreation' => $favData['timeCreation'],
            // 'colId' => $favData['colId'],
            'published' => $favData['published']
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

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->extendAPIAccess ) {
      $listFilters = array();
      if( $filters['resourceId'] !== null ) {
        $listFilters['inResourceList'] = $filters['resourceId'];
      }
      if( $filters['userId'] !== null ) {
        $listFilters['user'] = $filters['userId'];
      }
      if( $filters['favouritesId'] !== null ) {
        $listFilters['id'] = $filters['favouritesId'];
      }

      $favModel = new FavouritesListViewModel();
      $favList = $favModel->listItems( array( 'filters' => $listFilters ) );
      if( $favList ) {
        $result = array(
          'result' => 'ok',
          'resource' => array()
        );
        while( $favObj = $favList->fetch() ) {
          $favouritesId = $favObj->getter( 'id' );
          $resourceList = $favObj->getter('resourceList');
          if( $resourceList ) {
            $resourceList = explode( ',', $resourceList );
            foreach( $resourceList as $resourceId ) {
              if( $filters['resourceId'] === null || $filters['resourceId'] === $resourceId ) {
                if( !isset( $result['resource'][ $resourceId ] ) ) {
                  $result['resource'][ $resourceId ] = array(
                    'id' => $resourceId,
                    'favourites' => array()
                  );
                }
                $result['resource'][ $resourceId ]['favourites'][] = $favouritesId;
              }
            }
          }
          error_log( 'resourceList: '.$favObj->getter('resourceList') );
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
        $listFilters['user'] = $filters['userId'];
      }
      if( $filters['favouritesId'] !== null ) {
        $listFilters['id'] = $filters['favouritesId'];
      }

      $favModel = new FavouritesListViewModel();
      $favList = $favModel->listItems( array( 'filters' => $listFilters ) );
      if( $favList ) {
        $result = array(
          'result' => 'ok',
          'user' => array()
        );
        while( $favObj = $favList->fetch() ) {
          $favouritesId = $favObj->getter( 'id' );
          $userId = $favObj->getter( 'user' );

          if( !isset( $result['user'][ $userId ] ) ) {
            $result['user'][ $userId ] = array(
              'id' => $userId,
              'favourites' => array()
            );
          }
          $result['user'][ $userId ]['favourites'][] = $favouritesId;
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





  /**
   * API info to swagger
   */
  public function apiInfoJson() {
    header('Content-type: application/json');
?>
{
  "resourcePath": "/favourites.json",
  "basePath": "/api",
  "apis": [
    {
      "operations": [
        {
          "errorResponses": [
            {
              "reason": "Favourites info",
              "code": 200
            },
            {
              "reason": "Favourites not found",
              "code": 404
            }
          ],
          "httpMethod": "POST",
          "nickname": "favourites",
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
              "description": "Favourite status ( 0 | 1 )",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "resourceId",
              "description": "Limit command by Resource Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "favouritesId",
              "description": "Limit command by Favourites Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "userId",
              "description": "Limit command by User Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            }
          ],
          "summary": "Fetches favourites data"
        }
      ],
      "path": "/favourites",
      "description": ""
    }
  ]
}
<?php
  } // function apiInfoJson()

}

