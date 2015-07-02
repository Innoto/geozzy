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


  function docJson() {
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
                        "nickname": "resource",
                        "parameters": [

                          {
                            "name": "explorer",
                            "description": "explorer name",
                            "dataType": "string",
                            "paramType": "path",
                            "defaultValue": "false",
                            "required": false
                          },
                          {
                            "name": "request",
                            "description": "request type ( initial or current )",
                            "dataType": "string",
                            "paramType": "path",
                            "defaultValue": "initial",
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





  // resources

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


      if( $params[1] == 'initial' ) {
        $explorer->serveInitialData();
      }
      else
      if( $params[1] == 'current' ) {
        $explorer->serveCurrentData();
      }





    }
    else {
      header("HTTP/1.0 404 Not Found");
    }


  }

}
