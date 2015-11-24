<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class ExplorerAPIView extends View
{
  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    if( GEOZZY_API_ACTIVE ){
     return true;
    }
  }


  function explorerJson() {
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

                        "httpMethod": "GET",
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
                            "paramType": "path",
                            "defaultValue": "false",
                            "required": true
                          }

                        ],
                        "summary": "Fetches explorer data"
                    }
                ],
                "path": "/explorer/explorer/{explorer}/request/{request}/updatedfrom/{updatedfrom}",
                "description": ""
            }
        ]

    }

    <?php
  }

  function explorerListJson() {
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

  function explorer( $urlParams ) {

    require_once APP_BASE_PATH."/conf/geozzyExplorers.php";
    global $GEOZZY_EXPLORERS;


    $validation = array(
      'explorer'=> '#(.*)#',
      'request'=> '#(.*)#',
      'updatedfrom' => '#\d+$#'
    );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);



    if( isset($urlParamsList['request']) && isset($urlParamsList['explorer']) && isset( $GEOZZY_EXPLORERS[ $urlParamsList['explorer'] ] ) ) {
      $explorerConf = $GEOZZY_EXPLORERS[ $urlParamsList['explorer'] ];
      header('Content-type: application/json');


      // Include controller
      eval( $explorerConf['module'].'::load("'.$explorerConf['controllerFile'].'");' );

      // constructor;
      $explorer = new $explorerConf['controllerName']();


      if( $urlParamsList['request'] == 'minimal' ) {
        if( isset($urlParamsList['updatedfrom']) ) {
          $explorer->serveMinimal( $urlParamsList['updatedfrom'] );
        }
        else {
          $explorer->serveMinimal( );
        }

      }
      else
      if( $urlParamsList['request'] == 'partial' ) {
        $explorer->servePartial();
      }
      else {
        header("HTTP/1.0 404 Not Found");
      }





    }
    else {
      header("HTTP/1.0 404 Not Found");
    }


  }


  // explorer list

  function explorerList(  ) {

    require_once APP_BASE_PATH."/conf/geozzyExplorers.php";
    global $GEOZZY_EXPLORERS;




    if( sizeof( $GEOZZY_EXPLORERS ) > 0 ) {
      header('Content-type: application/json');
      echo json_encode($GEOZZY_EXPLORERS);
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }


  }


}
