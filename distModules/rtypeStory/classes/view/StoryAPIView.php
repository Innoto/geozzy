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
          "path": "/story/story/{story}",
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



  // story
  public function story( $urlParams ) {

    require_once APP_BASE_PATH."/conf/inc/geozzyStories.php"; // Load $GEOZZY_STORIES
    global $GEOZZY_STORIES;

    $validation = array(
      'story'=> '#(.*)#'
    );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset($urlParamsList['story']) && isset( $GEOZZY_STORIES[ $urlParamsList['story'] ] ) ) {
      $storyConf = $GEOZZY_STORIES[ $urlParamsList['story'] ];
      // include
      eval( $storyConf['module'].'::load("'.$storyConf['controllerFile'].'");' );
      // constructor;
      $explorer = new $storyConf['controllerName']();

      header('Content-type: application/json');
      $explorer->serveMinimal();

    }
    else {
      header("HTTP/1.0 404 Not Found");
    }

  }

  // story list
  public function storyList() {

    require_once APP_BASE_PATH."/conf/inc/geozzyStories.php"; // Load $GEOZZY_STORIES
    global $GEOZZY_STORIES;

    if( count( $GEOZZY_STORIES ) > 0 ) {
      $stListInfo = array();
      foreach( $GEOZZY_STORIES as $stId => $stInfo ) {
        $stListInfo[ $stId ] = array(
          'name' => $stInfo[ 'name' ]
        );
      }

      header('Content-type: application/json');
      echo json_encode( $stListInfo );
    }
    else {
      header("HTTP/1.0 404 Not Found");
    }

  }

}
