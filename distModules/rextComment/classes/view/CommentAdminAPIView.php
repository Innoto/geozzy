<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class CommentAdminAPIView extends View
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $res = true;

    if( !GEOZZY_API_ACTIVE || !$useraccesscontrol->isLogged() ){
     $res = false;
    }

    $access = $useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full');
    if(!$access){
      $res = false;
    }

    if( $res == false ) {
      header("HTTP/1.0 401");
      header('Content-type: application/json');
      echo '[]';
      exit;
    }

    return $res;
  }


  public function commentsSuggestionsJson() {
    header('Content-type: application/json');
    ?>
      {
        "resourcePath": "commentsuggestion.json",
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
                "nickname": "comment",
                "parameters": [
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "resourceID",
                    "paramType": "path",
                    "allowMultiple": false,
                    "defaultValue": "false",
                    "description": "group id"
                  }
                ],
                "summary": "Fetches comments"
              }
            ],
            "path": "/admin/commentsuggestion/list/resource/{resourceID}",
            "description": ""
          }
        ]
      }
    <?php
  }



  // comments
  public function commentsSuggestions( $urlParams ) {

    $validation = array('resource'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset( $urlParamsList['resource'] ) && is_numeric( $urlParamsList['resource'] ) ) {

      geozzy::load('model/TaxonomygroupModel.php');
      geozzy::load('model/TaxonomytermModel.php');


      rextComment::load('model/CommentModel.php');
      $commentModel = new CommentModel();
      $commentsList = $commentModel->listItems(  array(
        'filters' => array( 'resource'=> $urlParamsList['resource'] ),
        'order' => array( 'timeCreation' => -1 ),
        'affectsDependences' => array('UserModel')
      ) );



      header('Content-type: application/json');
      echo '[';
      $c = '';
      global $C_LANG;

      while ($valueobject = $commentsList->fetch() ) {
        $allData = $valueobject->getAllData('onlydata');
        $user = $valueobject->getterDependence('user');
        if($user){
          $allData['userName'] = $user[0]->getter('name');
          $allData['userEmail'] = $user[0]->getter('email');
        }
        echo $c.json_encode($allData);
        $c=',';
      }
      echo ']';

    }
    else {
      header("HTTP/1.0 404 Not Found");
      header('Content-type: application/json');
      echo '{}';
    }
  }


}
