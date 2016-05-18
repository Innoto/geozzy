<?php

Cogumelo::load( 'coreView/View.php' );
rextFavourite::load( 'controller/RExtFavouriteController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtFavouriteAPIView extends View {

  var $userId = false;
  var $userSession = false;
  var $userAPIAccess = false;

  public function __construct( $base_dir ) {

    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();
    // error_log( 'USER: '.print_r( $userInfo, true ) );

    if( isset( $userInfo['data']['id'] ) ) {
      $this->userId =  $userInfo['data']['id'];
      $this->userSession = $userInfo;
    }

    if( $this->userSession && $this->userSession['data']['login'] === 'superAdmin' ) {
      $this->userAPIAccess = true;
    }

    $this->apiParams = array( 'cmd', 'status' );
    $this->apiCommands = array( 'setStatus', 'getStatus', 'listFavs', 'listResources', 'listUsers' );
    $this->apiFilters = array( 'fav-id', 'res-id', 'user-id' );

    parent::__construct( $base_dir ); // Esto lanza el accessCheck
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {
    //return( $this->userId !== false );
    return( GEOZZY_API_ACTIVE === true );
  }



  public function apiQuery( $urlParams = false ) {
    $result = null;

    $command = ( isset( $_POST['cmd'] ) && in_array( $_POST['cmd'], $this->apiCommands ) ) ? $_POST['cmd'] : null;

    $status = null;
    if( isset( $_POST['status'] ) ) {
      $status = ( $_POST['status'] ) ? 1 : 0; // Manejamos status como 0-1 y no false-true
    }

    $filters = array();
    foreach( $this->apiFilters as $key ) {
      $filters[ $key ] = ( isset( $_POST[ $key ] ) ) ? $_POST[ $key ] : null;
    }

    // error_log( 'filters: '.print_r( $filters, true ) );
    // var_dump( $filters );

    switch( $command ) {
      case 'setStatus':
        $result = $this->apiSetStatus( $status, $filters['res-id'], $filters['user-id'] );
        break;
      case 'getStatus':
        $result = $this->apiGetStatus( $filters['res-id'], $filters['user-id'] );
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


  public function apiSetStatus( $status, $resId, $userId ) {
    $result = null;

    // Si no hay usuario, el de session
    if( $userId === null && $this->userId !== false ) {
      $userId = $this->userId;
    }

    // Solo pueden acceder a otros usuarios si $this->userAPIAccess
    if( !$this->userAPIAccess && $userId !== $this->userId ) {
      $userId = null;
    }

    if( $status !== null && $resId !== null && $userId !== null ) {
      $favCtrl = new RExtFavouriteController();
      if( $favCtrl->setStatus( $resId, $status, $userId ) ) {
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


  public function apiGetStatus( $resId, $userId ) {
    $result = null;

    // Si no hay usuario, el de session
    if( $userId === null && $this->userId !== false ) {
      $userId = $this->userId;
    }

    // Solo pueden acceder a otros usuarios si $this->userAPIAccess
    if( !$this->userAPIAccess && $userId !== $this->userId ) {
      $userId = null;
    }

    if( $resId !== null && $userId !== null ) {
      $favCtrl = new RExtFavouriteController();
      $result = array(
        'result' => 'ok',
        'status' => $favCtrl->getStatus( $resId, $userId )
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
              "name": "fav-id",
              "description": "Get Resource Ids filter by Favourites Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "res-id",
              "description": "Get Favourites Ids filter by Resource Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "user-id",
              "description": "Get Favourites Ids filter by User Id",
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

