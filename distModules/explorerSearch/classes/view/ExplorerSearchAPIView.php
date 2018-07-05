<?php

Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class ExplorerSearchAPIView extends View
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return( true );
  }


  public function explorerSearchJson() {
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
                  "reason": "Explorer search",
                  "code": 200
                }
              ],
              "httpMethod": "POST",
              "nickname": "explorerSearch",
              "parameters": [
                {
                  "name": "searchString",
                  "description": "Search string ",
                  "dataType": "string",
                  "paramType": "path",
                  "defaultValue": "",
                  "required": true
                }
              ],
              "summary": "Fetches public search data"
            }
          ],
          "path": "/explorerSearch",
          "description": ""
        }
      ]
    }
    <?php
  }


  // explorer
  public function explorerSearch(  ) {

    $resArray = [];

    if( !empty( $_POST['searchString']) &&  str_replace(' ', '', $_POST['searchString']) != '') {
      $resResult = (new ResourceModel())->listItems( [ 'filters'=> ['searchString'=> $_POST['searchString'] ] ] );
      if( $resResult !== COGUMELO_ERROR) {
        while( $res = $resResult->fetch() ) {
          $resArray[] = $res->getter('id');
        }
      }
    }
    header('Content-type: application/json');
    print json_encode( $resArray );

  }


}
