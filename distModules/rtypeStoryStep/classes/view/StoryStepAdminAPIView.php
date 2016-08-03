<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/MailController.php');
geozzy::load('controller/ResourceController.php');

/**
* Clase Master to extend other application methods
*/
class StoryStepAdminAPIView extends View {

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
  public function storySteps( $urlParams ) {

    $validation = array( 'resource' => '#\d+$#' );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);
    $resourceId = isset( $urlParamsList['resource'] ) ? $urlParamsList['resource'] : false;

    //rextStoryStep::load('model/RExtStoryStepModel.php');

    switch( $_SERVER['REQUEST_METHOD'] ) {

      case 'PUT':
        $putData = json_decode(file_get_contents('php://input'), true);
        $resourceModel = new ResourceModel();

        if( is_numeric( $resourceId ) ) {  // UPDATE
          $resource = $resourceModel->listItems(  array( 'filters' => array( 'id'=>$resourceId ) ))->fetch();
        }
        else { // CREATE
          $resource = $resourceModel;
        }

        if( isset( $putData['weight'] ) ) {
          $resource->setter('weight', $putData['weight'] );
        }

        $resource->save();

        $resourceData = $resource->getAllData();
        echo json_encode( $resourceData['data'] );

      break;

      case 'GET':
        if( isset( $resourceId ) && is_numeric( $resourceId ) ) {

          $resController = new ResourceController();
          $collectionArrayInfo = $resController->getCollectionBlockInfo( $resourceId );

          if ($collectionArrayInfo){
            foreach ($collectionArrayInfo as $key => $collectionInfo){
              if ($collectionInfo['col']['collectionType'] == 'steps'){
                $collectionId = $collectionInfo['col']['id'];
              }
            }
          }
          $collectionResources = array();
          if($collectionId){
            $collectionResourcesModel = new CollectionResourcesModel();
            $collectionResources = $collectionResourcesModel->listItems(  array(
              'filters' => array( 'collection'=> $collectionId ),
              'affectsDependences' => array('ResourceModel')
            ) );
          }

          header('Content-Type: application/json; charset=utf-8');
          echo '[';
          $c = '';
          global $C_LANG;

          while( $col = $collectionResources->fetch() ){

            $resource = $col->getterDependence('resource', 'ResourceModel');
            $allData['id'] = $resource[0]->getter('id');
            $allData['title'] = $resource[0]->getter('title');
            echo $c.json_encode($allData);
            $c=',';
          }
          echo ']';
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-Type: application/json; charset=utf-8');
          echo '{}';
        }
      break;
      default:
        header("HTTP/1.0 404 Not Found");
      break;
    }
  }


  public function storyStepsJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "adminStorySteps.json",
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
                "nickname": "storystep",
                "parameters": [
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "id",
                    "paramType": "path",
                    "allowMultiple": false,
                    "defaultValue": "false",
                    "description": "resource id"
                  }
                ],
                "summary": "Fetches story steps"
              }
            ],
            "path": "/admin/adminStorySteps/resource/{id}",
            "description": ""
          }
        ]
      }
    <?php
  }
}
