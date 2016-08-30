<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class StoryAdminAPIView extends View {

  public function __construct( $baseDir = false ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $access = true;

    if( !GEOZZY_API_ACTIVE || !$useraccesscontrol->isLogged() ){
      $access = false;
    }

    $access = $useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full');
    if(!$access){
      $access = false;
    }

    if( !$access ) {
      header("HTTP/1.0 401");
      header('Content-Type: application/json; charset=utf-8');
      echo '[]';
      exit;
    }

    return $access;
  }



  // URL: /api/admin/commentsuggestion/
  public function stories( ) {

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'GET':
        $rtypeModel = new ResourcetypeModel();
        $rtypeEl = $rtypeModel->listItems(  array(
          'filters' => array( 'idName'=> 'rtypeStory' )
        ))->fetch();

        if ($rtypeEl){
          $rtype = $rtypeEl->getter('id');
        }


        $resourceModel = new ResourceModel();
        $storiesList = $resourceModel->listItems(  array(
          'filters' => array( 'rTypeId'=> $rtype ),
          'order' => array( 'timeCreation' => -1 )
        ));

        header('Content-Type: application/json; charset=utf-8');
        echo '[';
        $c = '';
        global $C_LANG;

        while ($valueobject = $storiesList->fetch() ) {
          $allData = $valueobject->getAllData('onlydata');

          echo $c.json_encode($allData);
          $c=',';
        }
        echo ']';
      break;
      default:
        header("HTTP/1.0 404 Not Found");
      break;
    }
  }


  public function storiesJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "adminStories.json",
        "basePath": "/api",
        "apis": [
          {
            "operations": [
              {
                "errorResponses": [
                  {
                    "reason": "Not found",
                    "code": 404
                  }
                ],
                "httpMethod": "GET",
                "nickname": "story",
                "parameters": [
                ],
                "summary": "Fetches stories"
              }
            ],
            "path": "/admin/adminStories",
            "description": ""
          }
        ]
      }
    <?php
  }
}
