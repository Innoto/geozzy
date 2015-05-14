<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class CoreAPIView extends View
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


  function resourcesJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/resources.json",
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
                                  "name": "changes",
                                  "description": "Comparison changes to update (accept or reject)",
                                  "dataType": "Opts",
                                  "multiple": true,
                                  "paramType": "body"
                                }

                              ],
                              "summary": "Fetches words in a WordList"
                          }
                      ],
                      "path": "/core/resourcelist",
                      "description": ""
                  }
              ],

              "models": {
                "Opts": {
                  "id": "Opts",
                  "properties": {
                    "keys": {
                      "type": "[string]"
                    },
                    "filters": {
                      "type": "[String]"
                    },
                    "cached": {
                      "type": "Boolean"
                    }
                  }
                }
             
            }




          }










        <?php
  }

  // resources


  function resourceList() {
    geozzy::load('model/ResourceModel.php');
    $resourceModel = new ResourceModel();
    $resourceList = $resourceModel->listItems(  array( 'filters' => array( ) ) );
    $this->syncModelList( $resourceList );
  }

  function resourceTypes() {
    geozzy::load('model/ResourcetypeModel.php');    
    $resourcetypeModel = new ResourcetypeModel( );
    $resourcetypeList = $resourcetypeModel->listItems( ) ;
    $this->syncModelList( $resourcetypeList );
  }

  // Categories

  function categoryList() {
    geozzy::load('model/TaxonomygroupModel.php');    
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));
    $this->syncModelList( $taxGroupList );

  }
  
  function categoryTerms() {
    geozzy::load('model/TaxonomytermModel.php');    
    $taxtermModel = new TaxonomytermModel();
    $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$_GET['group']) ) );
    $this->syncModelList( $taxtermList );
  }

  // Topics

  function topicList() {
    geozzy::load('model/TopicModel.php');    
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems( );
    $this->syncModelList( $topicList );
  }


  function syncModelList( $result ) {

    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($valueobject = $result->fetch() )
    {
      $allData = $valueobject->getAllData();
      echo $c.json_encode( $allData['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';
  }


  function syncModel( $model ) {
    header('Content-type: application/json');
    $data = $model->getAllData();
    echo json_encode( $data['data'] );
  }


}