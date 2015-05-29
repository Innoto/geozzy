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
                                  "name": "fields",
                                  "description": "fields (separed by comma)",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "filters",
                                  "description": "filters (separed by comma)",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "filtervalues",
                                  "description": "filtervalues (separed by comma)",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                },
                                {
                                  "name": "rtype",
                                  "description": "Resource Type",
                                  "dataType": "string",
                                  "paramType": "path",
                                  "defaultValue": "false",
                                  "required": false
                                }

                              ],
                              "summary": "Fetches resource list"
                          }
                      ],
                      "path": "/core/resourcelist/fields/{fields}/filters/{filters}/rtype/{rtype}",
                      "description": ""
                  }
              ]

          }

        <?php
  }



  function resourceTypesJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/resourceTypes.json",
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
                              "nickname": "resource",
                              "parameters": [

                              ],
                              "summary": "Fetches resource type list"
                          }
                      ],
                      "path": "/core/resourcetypes",
                      "description": ""
                  }
              ]


          }

        <?php
  }



  function categoryListJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/categoryList.json",
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
                              "nickname": "resource",
                              "parameters": [
                              ],
                              "summary": "Fetches all category groups"
                          }
                      ],
                      "path": "/core/categorylist",
                      "description": ""
                  }
              ]


          }

        <?php
  }

  function categoryTermsJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/categoryTerms.json",
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
                              "nickname": "resource",
                              "parameters": [
                                {
                                  "required": true,
                                  "dataType": "int",
                                  "name": "id",
                                  "paramType": "path",
                                  "allowMultiple": false,
                                  "description": "group id"
                                }
                              ],
                              "summary": "Fetches category terms"
                          }
                      ],
                      "path": "/core/categoryterms/{id}",
                      "description": ""
                  }
              ]


          }

        <?php
  }



  function topicListJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/topicList.json",
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
                              "nickname": "resource",
                              "parameters": [
 
                              ],
                              "summary": "Fetches topics"
                          }
                      ],
                      "path": "/core/topiclist",
                      "description": ""
                  }
              ]


          }

        <?php
  }





  // resources

  function resourceList( $param ) {
    
    geozzy::load('model/ResourceModel.php');
    geozzyAPI::load('controller/apiFiltersController.php');

    $resourceModel = new ResourceModel( array('affectsDependences'=>array('UrlAliasModel') ) );
    $resourceList = $resourceModel->listItems( apiFiltersController::resourceListOptions($param) );
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
  
  function categoryTerms( $path ) {


    if( isset( $path[1] ) && is_numeric( $path[1] ) ) { 
      geozzy::load('model/TaxonomytermModel.php');    
      $taxtermModel = new TaxonomytermModel();
      $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$path[1]) ) );
      $this->syncModelList( $taxtermList );
    }
    else {
      header("HTTP/1.0 404 Not Found");
      header('Content-type: application/json');
      echo '{}';
    }
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
      echo $c.json_encode( $allData);
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