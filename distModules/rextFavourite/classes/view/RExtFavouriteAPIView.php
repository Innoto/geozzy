<?php

Cogumelo::load( 'coreView/View.php' );
rextFavourite::load( 'controller/RExtFavouriteController.php' );


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

    if( $this->userSession && $this->userSession['data']['login'] === 'superAdmin' ) {
      $this->extendAPIAccess = true;
    }

    $this->apiParams = array( 'cmd', 'status' );
    $this->apiCommands = array( 'setStatus', 'getStatus', 'getFavouritesUrl', 'listFavs', 'listResources', 'listUsers' );
    $this->apiFilters = array( 'favouritesId', 'resourceId', 'userId' );

    parent::__construct( $base_dir ); // Esto lanza el accessCheck
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
      case 'listFavs':
        $result = $this->apiListFavs( $filters );
        break;
      case 'listResources':
        $result = $this->apiListResources( $filters );
        break;
      case 'listUsers':
        $result = $this->apiListUsers( $filters );
        break;
      case 'getFavouritesUrl':
        $result = $this->getFavouritesUrl();
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
      $favCtrl = new RExtFavouriteController();
      if( $favCtrl->setStatus( $resourceId, $status, $userId ) ) {
        $result = [
          'result' => 'ok',
          'status' => $status
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

      $favCtrl = new RExtFavouriteController();
      $result = array(
        'result' => 'ok',
        'status' => $favCtrl->getStatus( $resourceId, $userId )
      );
    }

    return $result;
  }


  public function apiListFavs( $filters ) {
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
      if( $filters['favouritesId'] !== null ) {
        $favsArray = explode( ',', $filters['favouritesId'] );
        if( count( $favsArray ) > 1 ) {
          $listFilters['idIn'] = $favsArray;
        }
        else {
          $listFilters['id'] = $filters['favouritesId'];
        }
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
      if( $filters['favouritesId'] !== null ) {
        $favsArray = explode( ',', $filters['favouritesId'] );
        if( count( $favsArray ) > 1 ) {
          $listFilters['idIn'] = $favsArray;
        }
        else {
          $listFilters['id'] = $filters['favouritesId'];
        }
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
          // error_log( 'resourceList: '.$favObj->getter('resourceList') );
        }
      }
    }

    return $result;
  }


  public function apiListUsers( $filters ) {
    $result = [ 'result' => 'error', 'msg' => __('Access denied') ];

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
      if( $filters['favouritesId'] !== null ) {
        $favsArray = explode( ',', $filters['favouritesId'] );
        if( count( $favsArray ) > 1 ) {
          $listFilters['idIn'] = $favsArray;
        }
        else {
          $listFilters['id'] = $filters['favouritesId'];
        }
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

    return $result;
  }


  public function getFavouritesUrl() {
    $result = [ 'result' => 'error', 'msg' => __('Access denied') ];

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->userId !== false ) {
      $favCtrl = new RExtFavouriteController();
      $result = array(
        'result' => 'ok',
        'status' => $favCtrl->getFavouritesUrl( $this->userId )
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
              "description": "Filter by Resource Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "favouritesId",
              "description": "Filter by Favourites Id or array",
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
