<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class ExplorerAPIView extends View
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


  public function explorerJson() {
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
                  "reason": "The explorer",
                  "code": 200
                },
                {
                  "reason": "Explorer not found",
                  "code": 404
                }
              ],
              "httpMethod": "POST",
              "nickname": "explorer",
              "parameters": [
                {
                  "name": "explorer",
                  "description": "explorer name",
                  "dataType": "string",
                  "paramType": "path",
                  "defaultValue": "default",
                  "required": false
                },
                {
                  "name": "request",
                  "description": "( minimal | partial )",
                  "dataType": "string",
                  "paramType": "path",
                  "defaultValue": "minimal",
                  "required": true
                },
                {
                  "name": "updatedfrom",
                  "description": "updated from (UTC timestamp)",
                  "dataType": "int",
                  "paramType": "form",
                  "defaultValue": "false",
                  "required": false
                },
                {
                  "name": "ids",
                  "description": "ids",
                  "type": "array",
                  "items": {
                    "type": "integer"
                  },
                  "paramType": "form",
                  "required": false
                }
              ],
              "summary": "Fetches explorer data"
            }
          ],
          "path": "/explorer/explorer/{explorer}/request/{request}",
          "description": ""
        }
      ]
    }
    <?php
  }

  public function explorerListJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/explorerList.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "The explorer list",
                  "code": 200
                },
                {
                  "reason": "Explorer list not found",
                  "code": 404
                }
              ],
              "httpMethod": "GET",
              "nickname": "explorerlist",
              "parameters": [
              ],
              "summary": "Fetches explorer list"
            }
          ],
          "path": "/explorerList",
          "description": ""
        }
      ]
    }
    <?php
  }



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
}
