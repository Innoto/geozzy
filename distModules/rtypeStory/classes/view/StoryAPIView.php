<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class StoryAPIView extends View
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    if( GEOZZY_API_ACTIVE ){
      return( true );
    }
  }


  public function storyJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/explorer.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "The stories",
                  "code": 200
                },
                {
                  "reason": "Story not found",
                  "code": 404
                }
              ],
              "httpMethod": "POST",
              "nickname": "story",
              "parameters": [
                {
                  "name": "story",
                  "description": "story name",
                  "dataType": "string",
                  "paramType": "path",
                  "required": true
                }
              ],
              "summary": "Fetches story steps"
            }
          ],
          "path": "/stories/story/{story}",
          "description": ""
        }
      ]
    }
    <?php
  }

  public function storyListJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/storyList.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "The story list",
                  "code": 200
                },
                {
                  "reason": "Story list not found",
                  "code": 404
                }
              ],
              "httpMethod": "GET",
              "nickname": "storylist",
              "parameters": [
              ],
              "summary": "Fetches story list"
            }
          ],
          "path": "/storyList",
          "description": ""
        }
      ]
    }
    <?php
  }


/*
  // explorer
  public function explorer( $urlParams ) {
    require_once APP_BASE_PATH."/conf/inc/geozzyExplorers.php"; // Load $GEOZZY_EXPLORERS
    global $GEOZZY_EXPLORERS;

    $validation = array(
      'explorer'=> '#(.*)#',
      'request'=> '#(.*)#'
    );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset($urlParamsList['request']) && isset($urlParamsList['explorer']) && isset( $GEOZZY_EXPLORERS[ $urlParamsList['explorer'] ] ) ) {
      $explorerConf = $GEOZZY_EXPLORERS[ $urlParamsList['explorer'] ];

      // Include controller
      eval( $explorerConf['module'].'::load("'.$explorerConf['controllerFile'].'");' );

      // constructor;
      $explorer = new $explorerConf['controllerName']();

      if( $urlParamsList['request'] == 'minimal' ) {
        if( isset($urlParamsList['updatedfrom']) ) {
          header('Content-type: application/json');
          $explorer->serveMinimal( $urlParamsList['updatedfrom'] );
        }
        else {
          header('Content-type: application/json');
          $explorer->serveMinimal();
        }
      }
      else {
        if( $urlParamsList['request'] == 'partial' ) {
          header('Content-type: application/json');
          $explorer->servePartial();
        }
        else {
          header("HTTP/1.0 404 Not Found");
        }
      }
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }
  }

  // explorer list
  public function explorerList() {
    require_once APP_BASE_PATH."/conf/inc/geozzyExplorers.php"; // Load $GEOZZY_EXPLORERS
    global $GEOZZY_EXPLORERS;

    if( count( $GEOZZY_EXPLORERS ) > 0 ) {
      $explListInfo = array();
      foreach( $GEOZZY_EXPLORERS as $explId => $explInfo ) {
        $explListInfo[ $explId ] = array(
          'name' => $explInfo[ 'name' ],
          'mapBounds' => isset( $explInfo[ 'mapBounds' ] ) ? $explInfo[ 'mapBounds' ] : false,
          'filters' => isset( $explInfo[ 'filters' ] ) ? $explInfo[ 'filters' ] : false
        );
      }

      header('Content-type: application/json');
      echo json_encode( $explListInfo );
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }
  }
  */
}
