<?php

Cogumelo::load( 'coreView/View.php' );
rextTravelPlanner::load( 'controller/RExtTravelPlannerController.php' );
require_once APP_BASE_PATH.'/conf/inc/geozzyAPI.php';


class RExtTravelPlannerAPIView extends View {

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
    $this->apiCommands = array( 'getTravelPlannerUrl', 'getTravelPlanner', 'setTravelPlanner');
    $this->apiFilters = array( 'travelPlannerId', 'resourceId', 'userId' );

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

      case 'setTravelPlanner':
        //$result = $this->getTravelPlannerUrl();
        $result = $this->setTravelPlanner( $_POST['resourceData'] );
        break;
      case 'getTravelPlanner':
        $result = $this->getTravelPlanner();
        break;
      case 'getTravelPlannerUrl':
        $result = $this->getTravelPlannerUrl();
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



  public function getTravelPlannerUrl() {
    $result = null;

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->userId !== false ) {
      $tpCtrl = new RExtTravelPlannerController();
      $result = array(
        'result' => 'ok',
        'status' => $tpCtrl->getTravelPlannerUrl( $this->userId )
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

  public function getTravelPlanner() {
    $result = null;

    // Solo pueden acceder si $this->extendAPIAccess
    if( $this->userId !== false ) {
      $tpCtrl = new RExtTravelPlannerController();

      $tpModel = $tpCtrl->getTravelPlanner( $this->userId );
      $tpModelRext = $tpModel->getterDependence('id', 'TravelPlannerModel')[0];
      $resutl = [];


      $result['id'] = $tpModel->getter('id');
      $result['user'] = $tpModel->getter('user');

      $result['list'] = ( $tpModelRext->getter('travelPlannerJson') )?  $tpModelRext->getter('travelPlannerJson')  : null;

      $result['checkin'] = $tpModelRext->getter('checkIn');
      $result['checkout'] = $tpModelRext->getter('checkOut');

    }
    else {
      $result = array(
        'result' => 'error',
        'msg' => 'Access denied'
      );
    }

    return $result;
  }

  public function setTravelPlanner( $data ) {
    $result = null;

    // Solo pueden acceder si $this->extendAPIAccess
    //var_dump([ $this->userId , intval($result['user']) ]);

    if( $this->userId !== false && $this->userId == intval($data['user']) ) {
      $tpCtrl = new RExtTravelPlannerController();

      $tpModel = $tpCtrl->setTravelPlanner( $data );
      $tpModelRext = $tpModel->getterDependence('id', 'TravelPlannerModel')[0];
      $resutl = [];


      $result['id'] = $tpModel->getter('id');
      $result['user'] = $tpModel->getter('user');

      $result['list'] = ( $tpModelRext->getter('travelPlannerJson') )?  json_decode($tpModelRext->getter('travelPlannerJson'))  : [];

      $result['checkin'] = $tpModelRext->getter('checkIn');
      $result['checkout'] = $tpModelRext->getter('checkOut');

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
  "resourcePath": "/travelplanner.json",
  "basePath": "/api",
  "apis": [
    {
      "operations": [
        {
          "errorResponses": [
            {
              "reason": "Travel planner info",
              "code": 200
            },
            {
              "reason": "Travel planner not found",
              "code": 404
            }
          ],
          "httpMethod": "POST",
          "nickname": "travelplanner",
          "parameters": [
            {
              "name": "cmd",
              "description": "Command ( <?php echo implode( ' | ', $this->apiCommands ); ?> )",
              "type": "integer",
              "paramType": "form",
              "required": true
            },
            {
              "name": "resourceId",
              "description": "Filter by Resource Id",
              "type": "integer",
              "paramType": "form",
              "required": false
            },
            {
              "name": "travelPlannerId",
              "description": "Filter by TravelPlanner Id or array",
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
          "summary": "Fetches travel planner data"
        }
      ],
      "path": "/travelplanner",
      "description": ""
    }
  ]
}
<?php
  } // function apiInfoJson()

}
