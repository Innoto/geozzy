<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class CommentAPIView extends View
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


  public function commentsJson() {
    header('Content-type: application/json');
    ?>
      {
        "resourcePath": "/comments.json",
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
            "path": "/comment/list/resource/{resourceID}",
            "description": ""
          }
        ]
      }
    <?php
  }



  // comments
  public function comments( $urlParams ) {

    $validation = array('resource'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset( $urlParamsList['resource'] ) && is_numeric( $urlParamsList['resource'] ) ) {

      geozzy::load('model/TaxonomygroupModel.php');
      geozzy::load('model/TaxonomytermModel.php');
      $taxModelControl = new TaxonomygroupModel();
      $termModelControl = new TaxonomytermModel();
      // Data Options Comment Type
      $commentTypeTax = $taxModelControl->listItems( array('filters' => array('idName' => 'commentType')) )->fetch();
      $commentTypeTerm = $termModelControl->listItems(
        array('filters' =>
          array(
            'taxgroup' => $commentTypeTax->getter('id'),
            'idNames' => 'comment'
          )
        )
      )->fetch();

      rextComment::load('model/CommentModel.php');
      $commentModel = new CommentModel();
      $commentsList = $commentModel->listItems(  array(
        'filters' => array( 'resource'=> $urlParamsList['resource'], 'published' => 1, 'type' => $commentTypeTerm->getter('id')  ),
        'order' => array( 'timeCreation' => -1 )
      ) );

      header('Content-type: application/json');
      echo '[';
      $c = '';
      global $C_LANG;

      while ($valueobject = $commentsList->fetch() ) {
        $allData = $valueobject->getAllData('onlydata');
        echo $c.json_encode( $allData);
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
