<?php

Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/MailController.php');
geozzy::load('controller/ResourceController.php');

/**
* Clase Master to extend other application methods
*/
class StoryStepAPIView extends View {

  public function __construct( $baseDir = false ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return true;
  }

  public function storySteps( $urlParams ) {

$tempo = microtime(true);

    $validation = array( 'resource' => '#\d+$#', 'step' => '#\d+$#' );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);
    $resourceId = isset( $urlParamsList['resource'] ) ? $urlParamsList['resource'] : false;

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'GET':
        if( isset( $resourceId ) && is_numeric( $resourceId ) ) {

          $resCtrl = new ResourceController();

          story::load('model/AllstoryStepsViewModel.php');
          $resourceModel = new AllstoryStepsViewModel();
          $resources = $resourceModel->listItems([ 'filters'=>[ 'storyId' => $resourceId ]] );

$tempo2 = microtime(true);
error_log(sprintf( "%.3f", $tempo2-$tempo));

          header('Content-Type: application/json; charset=utf-8');
          echo '[';
          $c = '';
          global $C_LANG;

          while( $resource = $resources->fetch()  ){

            $row = array();
            $resourceDataArray = $resource->getAllData('onlydata');

            $row['id'] = $resourceDataArray['id'];

            if( isset($resourceDataArray['loc']) ) {
              Cogumelo::load('coreModel/mysql/MysqlDBUtils.php');
              $loc = MysqlDBUtils::decodeGeometry( [$resourceDataArray['loc']] );

              $row['lat'] = floatval( $loc['data'][0] );
              $row['lng'] = floatval( $loc['data'][1] );
            }
            unset($resourceDataArray['loc']);

            if( isset($resourceDataArray['terms']) ) {
              $row['terms'] = array_map( 'intval', explode(',',$resourceDataArray['terms']) );
            }

            if( isset($resourceDataArray['image']) ) {
              $row['img'] = $resourceDataArray['image'];
              $row['imageName'] = $resourceDataArray['imageName'];
              $row['imageAKey'] = $resourceDataArray['imageAKey'];
            }

            $row['defaultZoom'] = $resource->getter('defaultZoom');
            $row['title'] = $resource->getter('title');
            $row['shortDescription'] = $resource->getter('shortDescription');
            $row['mediumDescription'] = $resource->getter('mediumDescription');
            $row['relatedResource'] = $resource->getter('relatedResource');

            $row['legend'] = $resource->getter('legend');
            $row['KML'] = $resource->getter('KML');
            $row['drawLine'] = $resource->getter('drawLine');
            $row['mapType'] = $resource->getter('mapType');
            $row['dialogPosition'] = $resource->getter('dialogPosition');
            $row['idUrlVideo'] = $resCtrl->ytVidId( $resource->getter('urlVideo') );



            // EVENTS
            $due = strtotime('0000-00-00 00:00:00');
            if( strtotime($resource->getter('initDate')) != $due ) {
              $row['initDate'] = $resource->getter('initDate');
            }
            if( strtotime($resource->getter('endDate')) != $due ) {
              $row['endDate'] = $resource->getter('endDate');
            }

            if( isset($row['endDate']) || isset($row['initDate']) ){
              $row['showTimeline'] = $resource->getter('showTimeline');
            }

            $row['weight'] = $resource->getter('weight');

            echo $c.json_encode($row);
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
        "resourcePath": "storySteps.json",
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
                    "description": "story id"
                  }
                ],
                "summary": "Fetches story steps"
              }
            ],
            "path": "/storySteps/resource/{id}",
            "description": ""
          }
        ]
      }
    <?php
  }
}
