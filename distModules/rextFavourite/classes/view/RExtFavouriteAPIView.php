<?php

Cogumelo::load( 'coreView/View.php' );
rextFavourite::load( 'controller/RExtFavouriteController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtFavouriteAPIView extends View {

  var $userId = false;

  public function __construct( $base_dir ) {

    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();
    // error_log( 'USER: '.print_r( $userInfo, true ) );
    $this->userId = isset( $userInfo['data']['id'] ) ? $userInfo['data']['id'] : false;

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
    $result = false;

    // $validation = array( 'fav-id'=> '#\d+$#', 'res-id'=> '#\d+$#' );
    // $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );

    $filters = array();
    foreach( [ 'fav-id', 'res-id', 'user-id' ] as $key ) {
      if( isset( $_POST[$key] ) ) {
        $filters[$key] = $_POST[$key];
      }
    }

    $status = ( isset( $_POST['status'] ) && $_POST['status'] ) ? 1 : 0; // Manejamos status como 0-1 y no false-true

    $command = ( isset( $_POST['cmd'] ) && in_array( $_POST['cmd'], ['setStatus', 'getStatus', 'listFavs', 'listResources', 'listUsers'] ) ) ? $_POST['cmd'] : false;


    if( isset( $_POST['cmd'] ) ) {
      $result = $this->getterCommand( $_POST['cmd'], $filters );
    }
    elseif( isset( $_POST['res-id'] ) ) {
      $result = $this->listByResId( $_POST['res-id'] );
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $result );
  }



  public function getterCommand( $command, $filters ) {
    $result = null;

    // error_log( 'RExtFavouriteView->setStatus: '.$resource.', '.$status );

    $favCtrl = new RExtFavouriteController();
    switch( $command ) {
      case 'setStatus':
        $favInfo = $favCtrl->getStatus( $filters['res-id'], $filters['user-id'] );

        $status = ( $status ) ? 1 : 0; // Manejamos status como 0-1 y no false-true

        // error_log( 'RExtFavouriteView->setStatus: '.$resource.', '.$status );

        $favCtrl = new RExtFavouriteController();
        if( $favCtrl->setStatus( $resource, $status, $this->userId ) ) {
          $result = array(
            'result' => 'ok',
            'status' => $status
          );
        }

        break;
      case 'getStatus':
        $favInfo = $favCtrl->getStatus( $filters['res-id'], $filters['user-id'] );
        if( $favInfo ) {
          $result = array(
            'result' => 'ok',
            'status' => '1',
            'data' => $favInfo
          );
        }
        else {
          $result = array(
            'result' => 'ok',
            'status' => '0'
          );
        }
        break;
      case 'listFavs':
        # code...
        break;
      case 'listResources':
        # code...
        break;
      case 'listUsers':
        # code...
        break;
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
              "description": "Command ( setStatus | getStatus | listFavs | listResources | listUsers )",
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

