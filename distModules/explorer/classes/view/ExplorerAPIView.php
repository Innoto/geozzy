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
                                "reason": "The resource",
                                "code": 200
                            },
                            {
                                "reason": "Resource not found",
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
                          }

                        ],
                        "summary": "Fetches explorer data"
                    }
                ],
                "path": "/explorer/{explorer}",
                "description": ""
            }
        ]

    }

    <?php
  }





  // resources

  function explorer( $param ) {

    header('Content-type: application/json');


    var_dump( $param);

  }

}
