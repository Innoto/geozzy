<?php

Cogumelo::load( 'coreView/View.php' );
rextCommunity::load( 'controller/RExtCommunityController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtCommunityAPIView extends View {

  var $userId = false;
  var $userSession = false;
  var $commCtrl = false;

  public function __construct( $base_dir ) {
    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();

    if( isset( $userInfo['data']['id'] ) ) {
      $this->userId = $userInfo['data']['id'];
      $this->userSession = $userInfo;
    }

    $this->apiParams = array( 'cmd', 'status', 'facebook', 'twitter' );
    $this->apiCommands = array( 'setMyCommunity', 'setShare', 'setFacebook', 'setTwitter', 'getCommunityUrl' /*, 'listFollowed'*/ );

    $this->commCtrl = new RExtCommunityController();

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
    $result = array(
      'result' => 'error',
      'msg' => 'Command error'
    );

    $command = ( isset( $_POST['cmd'] ) && in_array( $_POST['cmd'], $this->apiCommands ) ) ? $_POST['cmd'] : null;
    $status = isset( $_POST['status'] ) ? $_POST['status'] : null;
    $facebook = isset( $_POST['facebook'] ) ? $_POST['facebook'] : null;
    $twitter = isset( $_POST['twitter'] ) ? $_POST['twitter'] : null;

    switch( $command ) {
      case 'setMyCommunity':
        $r['status'] = $this->commCtrl->setShare( $status );
        $r['facebook'] = $this->commCtrl->setSocial( 'facebook', $facebook );
        $r['twitter'] = $this->commCtrl->setSocial( 'twitter', $twitter );
        if( $r['status'] !== false || $r['facebook'] !== false || $r['twitter'] !== false ) {
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

