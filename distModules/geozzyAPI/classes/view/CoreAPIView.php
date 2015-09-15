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


  function biJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/bi.json",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Utils  for BI dashboard",
                                      "code": 200
                                  }
                              ],

                              "httpMethod": "GET",
                              "nickname": "resource",
                              "parameters": [
                              ],
                              "summary": ""
                          }
                      ],
                      "path": "/core/bi",
                      "description": ""
                  }
              ]

          }

        <?php
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



    function resourceIndexJson() {
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
                                        "reason": "The resource index",
                                        "code": 200
                                    }
                                ],

                                "httpMethod": "GET",
                                "nickname": "resourceIndex",
                                "parameters": [


                                    {
                                      "name": "taxonomyTerms",
                                      "description": "ids (separed by comma)",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    },
                                    {
                                      "name": "types",
                                      "description": "ids (separed by comma)",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    },
                                    {
                                      "name": "topics",
                                      "description": "ids (separed by comma)",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    },
                                    {
                                      "name": "bounds",
                                      "description": "lat,lng",
                                      "dataType": "string",
                                      "paramType": "path",
                                      "defaultValue": "false",
                                      "required": false
                                    }


                                ],
                                "summary": "Fetches resource list"
                            }
                        ],
                        "path": "/core/resourceIndex/taxonomyTerms/{taxonomyTerms}/types/{types}/topics/{topics}/bounds/{bounds}",
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
                      "path": "/core/categoryterms/id/{id}",
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


  function uiEventListJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/uieventList.json",
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
                              "summary": "Event type list"
                          }
                      ],
                      "path": "/core/uieventlist",
                      "description": ""
                  }
              ]


          }

        <?php
  }



    // resources

    function bi(  ) {
      require_once APP_BASE_PATH."/conf/geozzyBI.php";
      header('Content-type: application/json');
      global $LANG_AVAILABLE, $BI_SITE_SECTIONS, $BI_DEVICES;

      $langs = array(
        'default'=> LANG_DEFAULT,
        'available'=> $LANG_AVAILABLE
      );

      echo json_encode(
        array(
          'languages' => $langs,
          'devices' => $BI_DEVICES,
          'sections' => $BI_SITE_SECTIONS
        )
      );

    }



  // resources

  function resourceList( $param ) {

    geozzy::load('model/ResourceModel.php');
    geozzyAPI::load('controller/apiFiltersController.php');

    $resourceModel = new ResourceModel();
    $resourceList = $resourceModel->listItems( apiFiltersController::resourceListOptions($param) );
    $this->syncModelList( $resourceList );
  }



  function resourceIndex( $param ) {
    geozzyAPI::load('model/ResourceIndexModel.php');
    $resourceModel = new ResourceIndexModel();
    $filters = explode( '/',$param[1] );
    $queryFilters = array();

    // check parameter integrity
    if( $filters[1] == 'taxonomyTerms' && $filters[3] == 'types' && $filters[5] == 'topics' && $filters[7] == 'bounds' ) {


      // taxonomy terms
      if( $filters[2] != 'false' ) {
        $queryFilters['taxonomyterms'] = implode(',', array_map('intval', explode(',',$filters[2] ) ) );
      }

      // types
      if( $filters[4] != 'false' ) {
        $queryFilters['types'] = array_map('intval', explode(',',$filters[4]) );
      }

      // topics
      if( $filters[6] != 'false' ) {
        $queryFilters['topics'] = array_map('intval', explode(',',$filters[6]) );
      }




      if(
        $filters[8] != 'false' &&
        preg_match(
          '#(.*)\ (.*)\,(.*)\ (.*)#',
          urldecode($filters[8]),
          $bounds
        )
      ) {

          if( is_numeric($bounds[1]) && is_numeric($bounds[2]) &&
              is_numeric($bounds[3]) && is_numeric($bounds[4])
          ) {

            $queryFilters['bounds']=  $bounds[1].' '.$bounds[2].','.
                                      $bounds[1].' '.$bounds[4].','.
                                      $bounds[3].' '.$bounds[4].','.
                                      $bounds[3].' '.$bounds[2].','.
                                      $bounds[1].' '.$bounds[2];
          }

      }







      $resourceList = $resourceModel->listItems( array('filters' => $queryFilters, 'groupBy'=>'id') );
      header('Content-type: application/json');
      echo '[';
      $c = '';
      while ($valueobject = $resourceList->fetch() )
      {
        echo $c.$valueobject->getter('id');
        if($c === ''){$c=',';}
      }
      echo ']';
    }


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

  function categoryTerms( $urlParams ) {


    $validation = array('id'=> '#\d+$#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset( $urlParamsList['id'] ) && is_numeric( $urlParamsList['id'] ) ) {
      geozzy::load('model/TaxonomytermModel.php');
      $taxtermModel = new TaxonomytermModel();
      $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$urlParamsList['id'] ) ) );
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


  // UI events
  function uiEventList() {
    require_once APP_BASE_PATH."/conf/geozzyUIEvents.php";
    global  $GEOZZY_UI_EVENTS;

    header('Content-type: application/json');
    echo json_encode( $GEOZZY_UI_EVENTS );
  }


  function syncModelList( $result ) {

    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($valueobject = $result->fetch() )
    {
      $allData = $valueobject->getAllData('onlydata');
      echo $c.json_encode( $allData);
      if($c === ''){$c=',';}
    }
    echo ']';
  }


  function syncModel( $model ) {
    header('Content-type: application/json');
    $data = $model->getAllData('onlydata');
    echo json_encode( $data );


  }




}
