<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class RoutesAPIView extends View
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


  public function routesJson() {
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
          "path": "/routes",
          "description": ""
        }
      ]
    }
    <?php
  }



  // explorer
  public function routes(  ) {
    rextRoutes::autoIncludes();

    rextRoutes::load('controller/RoutesController.php');
    $routesControl = new RoutesController();
    $filePath = '/home/pblanco/Descargas/Incio_oural_.gpx';

    $rutaJSON = json_encode(   [ $routesControl->getRoute($filePath) ]);


    header('Content-type: application/json');
    echo $rutaJSON;

    /*
    require_once APP_BASE_PATH."/conf/geozzyExplorers.php"; // Load $GEOZZY_EXPLORERS
    global $GEOZZY_EXPLORERS;

    $validation = array(

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
    }*/
  }

}
