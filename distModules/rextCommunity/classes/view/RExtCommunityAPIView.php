<?php

Cogumelo::load( 'coreView/View.php' );
rextCommunity::load( 'controller/RExtCommunityController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtCommunityAPIView extends View {

  var $userId = false;
  var $userSession = false;
  var $commCtrl = false;

  public function __construct( $base_dir = false ) {
    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();

    if( isset( $userInfo['data']['id'] ) ) {
      $this->userId = $userInfo['data']['id'];
      $this->userSession = $userInfo;
    }

    $this->apiParams = array( 'cmd', 'status', 'user', 'facebook', 'twitter', 'followUser' );
    $this->apiCommands = array( 'setMyCommunity', 'setShare', 'setFacebook', 'setTwitter',
      'setFollow', 'getCommunityUrl', 'getOtherCommunityView' /*, 'listFollowed'*/ );

    $this->commCtrl = new RExtCommunityController();

    parent::__construct( $base_dir ); // Esto lanza el accessCheck
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {
    // $postUserId = isset( $_POST['user'] ) ? intval( $_POST['user'] ) : null;
    // return( $this->userId !== false && $this->userId === $postUserId );

    // return( GEOZZY_API_ACTIVE === true );

    return( $this->userId !== false || strpos( $_SERVER['REQUEST_URI'], 'doc/community.json' ) !== false );
  }


  /**
   * API router
   */
  public function apiQuery() {
    $result = array(
      'result' => 'error',
      'msg' => 'Command error'
    );

    $command = ( isset( $_POST['cmd'] ) && in_array( $_POST['cmd'], $this->apiCommands ) ) ? $_POST['cmd'] : null;
    $status = isset( $_POST['status'] ) ? $_POST['status'] : null;
    $userId = isset( $_POST['user'] ) ? intval( $_POST['user'] ) : null;
    $facebook = isset( $_POST['facebook'] ) ? $_POST['facebook'] : null;
    $twitter = isset( $_POST['twitter'] ) ? $_POST['twitter'] : null;
    $followUserId = isset( $_POST['followUser'] ) ? $_POST['followUser'] : null;

    if( $this->userId === $userId ) {
      switch( $command ) {
        case 'setMyCommunity':
          $r['share'] = $this->commCtrl->setShare( $status );
          $r['facebook'] = $this->commCtrl->setSocial( 'facebook', $facebook );
          $r['twitter'] = $this->commCtrl->setSocial( 'twitter', $twitter );
          if( $r['share'] !== false || $r['facebook'] !== false || $r['twitter'] !== false ) {
            $result = array( 'result' => 'ok', 'status' => $r );
          }
          break;
        case 'setShare':
          $status = $this->commCtrl->setShare( $status );
          if( $status !== false ) {
            $result = array( 'result' => 'ok', 'status' => $status );
          }
          break;
        case 'setFacebook':
          $status = $this->commCtrl->setSocial( 'facebook', $status );
          if( $status !== false ) {
            $result = array( 'result' => 'ok', 'status' => $status );
          }
          break;
        case 'setTwitter':
          $status = $this->commCtrl->setSocial( 'twitter', $status );
          if( $status !== false ) {
            $result = array( 'result' => 'ok', 'status' => $status );
          }
          break;
        case 'setFollow':
          $status = $this->commCtrl->setFollow( $status, $followUserId );
          if( $status !== false ) {
            $result = array( 'result' => 'ok', 'status' => $status );
          }
          break;
        case 'getOtherCommunityView':
          $view = $this->commCtrl->getOtherCommunityView( $followUserId );
          if( $status !== false ) {
            $result = array( 'result' => 'ok', 'view' => $view );
          }
          break;
        case 'getCommunityUrl':
          $result = array(
            'result' => 'ok',
            'status' => $this->commCtrl->getCommunityUrl( $this->userId )
          );
          break;
        /*
        case 'listFollowed':
          $result = $this->apiListFollowed();
          break;
        */
      }
    }
    elseif( ( $userId === null || $userId === 0 ) && $command === 'getCommunityUrl' ) {
      $result = array(
        'result' => 'ok',
        'status' => $this->commCtrl->getCommunityUrl( $this->userId )
      );
    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'User error'
      );
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $result );
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
              "type": "string",
              "paramType": "form",
              "required": true
            },
            {
              "name": "status",
              "description": "Status",
              "type": "string",
              "paramType": "form",
              "required": false
            },
            {
              "name": "user",
              "description": "User",
              "type": "string",
              "paramType": "form",
              "required": true
            },
            {
              "name": "facebook",
              "description": "facebook",
              "type": "string",
              "paramType": "form",
              "required": false
            },
            {
              "name": "twitter",
              "description": "twitter",
              "type": "string",
              "paramType": "form",
              "required": false
            },
            {
              "name": "followUser",
              "description": "followUser",
              "type": "string",
              "paramType": "form",
              "required": false
            }
          ],
          "summary": "Set community data"
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
