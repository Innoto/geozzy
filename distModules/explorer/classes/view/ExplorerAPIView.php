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
                            "description": "( index | data | checksum )",
                            "dataType": "string",
                            "paramType": "path",
                            "defaultValue": "index",
                            "required": true
                          }

                        ],
                        "summary": "Fetches explorer data"
                    }
                ],
                "path": "/explorer/{explorer}/{request}",
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

  function explorer( $param ) {

    require_once APP_BASE_PATH."/conf/geozzyExplorers.php";
    global $GEOZZY_EXPLORERS;

    $params = explode('/', $param[1]);


    if( isset( $GEOZZY_EXPLORERS[ $params[0] ] ) ) {
      $explorerConf = $GEOZZY_EXPLORERS[ $params[0] ];
      header('Content-type: application/json');


      // Include controller
      eval( $explorerConf['module'].'::load("'.$explorerConf['controllerFile'].'");' );

      // constructor;
      $explorer = new $explorerConf['controllerName']();


      if( $params[1] == 'index' ) {
        $explorer->serveIndex();
      }
      else
      if( $params[1] == 'data' ) {
        $explorer->serveData();
      }
      else
      if( $params[1] == 'checksum' ) {
        $explorer->serveChecksum();
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
