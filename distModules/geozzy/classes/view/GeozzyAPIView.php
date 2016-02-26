<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');

/**
 * Clase Master to extend other application methods
 */
class geozzyAPIView extends View {

  public function __construct( $baseDir ) {
    parent::__construct($baseDir);
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {
    if( GEOZZY_API_ACTIVE ){
      return true;
    }
  }


  public function biJson() {
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
                {
                  "name": "users",
                  "description": "Users list",
                  "dataType": "boolean",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                },
                {
                  "name": "virtualBags",
                  "description": "Virtual bags list",
                  "dataType": "boolean",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                }
              ],
              "summary": ""
            }
          ],
          "path": "/core/bi/users/{users}/virtualBags/{virtualBags}",
          "description": ""
        }
      ]
    }
    <?php
  }


  public function resourcesJson() {
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

              "httpMethod": "POST",
              "nickname": "resource",
              "parameters": [
                {
                  "name": "ids",
                  "description": "fields (separed by comma)",
                  "type": "array",
                  "items": {
                    "type": "integer"
                  },
                  "paramType": "form",
                  "required": false
                },
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
                },
                {
                  "name": "rextmodels",
                  "description": "extension Models",
                  "dataType": "boolean",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                },
                {
                  "name": "category",
                  "description": "Category Ids and Topic Ids",
                  "dataType": "boolean",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                },
                {
                  "name": "collection",
                  "description": "Collections data and resources",
                  "dataType": "boolean",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                },
                {
                  "name": "updatedfrom",
                  "description": "updated from (UTC timestamp)",
                  "dataType": "int",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                }
              ],
              "summary": "Fetches resource list"
            }
          ],
          "path": "/core/resourcelist/fields/{fields}/filters/{filters}/rtype/{rtype}/rextmodels/{rextmodels}/category/{category}/collection/{collection}/updatedfrom/{updatedfrom}",
          "description": ""
        }
      ]
    }
    <?php
  }


  public function resourceIndexJson() {
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
                    "name": "taxonomyterms",
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
            "path": "/core/resourceIndex/taxonomyterms/{taxonomyterms}/types/{types}/topics/{topics}/bounds/{bounds}",
            "description": ""
          }
        ]
      }
    <?php
  }


  public function resourceTypesJson() {
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
                "parameters": [],
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


  public function starredJson() {
    header('Content-type: application/json');
    ?>
    {
      "resourcePath": "/starred.json",
      "basePath": "/api",
      "apis": [
        {
          "operations": [
            {
              "errorResponses": [
                {
                  "reason": "Permission denied",
                  "code": 401
                },
                {
                  "reason": "Starred term list",
                  "code": 200
                },
                {
                  "reason": "Starred not found",
                  "code": 404
                }
              ],

              "httpMethod": "GET",
              "nickname": "group",
              "parameters": [],
              "summary": "Get Starred terms"
            }
          ],
          "path": "/core/starred",
          "description": ""
        }
      ]
    }
    <?php
  }


  public function categoryListJson() {
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
                "parameters": [],
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


  public function categoryTermsJson() {
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
                    "required": false,
                    "dataType": "int",
                    "name": "id",
                    "paramType": "path",
                    "allowMultiple": false,
                    "defaultValue": "false",
                    "description": "group id"
                  },
                  {
                    "required": false,
                    "dataType": "string",
                    "name": "name",
                    "paramType": "path",
                    "allowMultiple": false,
                    "defaultValue": "false",
                    "description": "group idName"
                  }
                ],
                "summary": "Fetches category terms"
              }
            ],
            "path": "/core/categoryterms/id/{id}/idname/{name}",
            "description": ""
          }
        ]
      }
    <?php
  }


  public function topicListJson() {
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
                "parameters": [],
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

  /*
    public function uiEventListJson() {
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
  */


  // BI data
  public function bi( $urlParams ) {
    require_once APP_BASE_PATH."/conf/geozzyBI.php";
    header('Content-type: application/json');
    global $LANG_AVAILABLE, $BI_SITE_SECTIONS, $BI_DEVICES, $BI_METRICS_EXPLORER, $BI_METRICS_RESOURCE, $BI_GEOZZY_UI_EVENTS;

    $langs = array(
      'default'=> LANG_DEFAULT,
      'available'=> $LANG_AVAILABLE
    );

    $biData = array(
      'languages' => $langs,
      'devices' => $BI_DEVICES,
      'sections' => $BI_SITE_SECTIONS,
      'ui_events' => $BI_GEOZZY_UI_EVENTS,
      'metrics' => array(
        'explorer' => $BI_METRICS_EXPLORER,
        'resource' => $BI_METRICS_RESOURCE
      )
    );

    // /users/{users}/virtualBags/{virtualBags}
    $validation = array(
      'users' => '#^(true|false)$#',
      'virtualBags'=> '#^(true|false)$#'
    );
    $extraParams = RequestController::processUrlParams( $urlParams, $validation );
    // error_log( 'urlParams: '. print_r( $urlParams, true ) );
    // error_log( 'extraParams: '. print_r( $extraParams, true ) );

    if( isset( $extraParams['users'] ) && $extraParams['users'] === 'true' ) {
      $userMod =  new UserModel();
      $userList = $userMod->listItems( array( 'filters' => array('active' => 1) ) );
      $biData['users'] = array();
      if( $userList ) {
        while( $userVo = $userList->fetch() ) {
          $biData['users'][] = array(
            'id' => $userVo->getter('id'),
            'login' => $userVo->getter('login')
          );
        }
      }
    }

    if( isset( $extraParams['virtualBags'] ) && $extraParams['virtualBags'] === 'true' ) {
      $userMod =  new UserModel();
      $userList = $userMod->listItems( array( 'filters' => array('active' => 1) ) );
      $biData['virtualBags'] = array();
      if( $userList ) {
        while( $userVo = $userList->fetch() ) {
          $biData['virtualBags'][] = array(
            'userId' => $userVo->getter('id'),
            'virtualBags' => array()
          );
        }
      }
    }

    echo json_encode( $biData );
  }


  // resources
  public function resourceList( $param ) {
    Cogumelo::load('coreModel/DBUtils.php');
    geozzy::load('model/ResourceModel.php');
    geozzy::load('controller/apiFiltersController.php');

    // Params: /fields/{fields}/filters/{filters}/rtype/{rtype}/rextmodels/{rextmodels}
    $validation = array(
      'fields' => '#(.*)#',
      'filters'=> '#(.*)#',
      'filtervalues'=> '#(.*)#',
      'rtype' => '#(.*)#',
      'rextmodels'=> '#^(true|false)$#',
      'category'=> '#^(true|false)$#',
      'collection'=> '#^(true|false)$#',
      'updatedfrom' => '#^(\d+)$#'
    );

    $extraParams = RequestController::processUrlParams( $param, $validation );

    //$queryParameters = apiFiltersController::resourceListOptions( $param );

    $resourceModel = new ResourceModel();
    $queryParameters = array( );

    // Resource type
    if( isset( $extraParams['rtype'] ) && $extraParams['rtype'] !== 'false' ) {
      $queryParameters['affectsDependences'] = $resourceModel->dependencesByResourcetype( $extraParams['rtype'] ) ;
    }

    // Category
    //if( isset( $extraParams['category'] ) && $extraParams['category'] === 'true' ) {
    //  $queryParameters['affectsDependences'][] = 'ResourceTaxonomytermModel';
    //  $queryParameters['affectsDependences'][] = 'ResourceTopicModel';
    //}



    // $queryParameters['affectsDependences'][] = 'ResourceTopicModel';



    // fields
    if( isset( $extraParams['fields'] ) && $extraParams['fields'] !== 'false' ) {
      $queryParameters['fields'] = apiFiltersController::clearFields( explode( ',', $extraParams['fields'] ) );
    }

    $queryParameters['filters']['published'] = 1;

    // updatedfrom
    if( isset( $extraParams['updatedfrom'] ) ) {
      $queryParameters['filters']['updatedfrom'] = gmdate( 'Y-m-d H:i:s', $extraParams['updatedfrom'] );
    }

    // fields and fieldvalues
    /*
      if( isset( $extraParams['filters'], $extraParams['filtervalues'] ) && $extraParams['filters'] !== 'false' && $extraParams['filtervalues'] !== 'false' ) {
        $queryParameters['filters'] = array('id' => 10);
      }
    */

    if( isset( $_POST['ids'] ) ) {
      if( is_array( $_POST['ids'] ) ) {
        $queryParameters['filters']['ids'] = array_map( 'intval', $_POST['ids'] );
      }
      else if( intval( $_POST['ids'] ) ) {
        $queryParameters['filters']['ids'] = $_POST['ids'];
      }
    }

    // error_log( '$queryParameters = '.print_r( $queryParameters, true ) );
    $resourceList = $resourceModel->listItems( $queryParameters );

    header('Content-type: application/json');
    echo '[';
    $c = '';
    while( $valueobject = $resourceList->fetch() ) {
      $allData = $valueobject->getAllData( 'onlydata' );

      if( isset( $allData['loc'] ) ) {
        $loc = DBUtils::decodeGeometry( $allData['loc'] );
        $allData['loc'] = array( 'lat' => floatval( $loc['data'][0] ) , 'lng' => floatval( $loc['data'][1] ) );
      }


      // Category
      if( isset( $extraParams['category'] ) && $extraParams['category'] === 'true' ) {
        // Cargo los datos de Term del recurso
        $taxTermModel =  new ResourceTaxonomytermModel();
        $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'resource' => $allData['id'] ) ) );
        if( $taxTermList !== false ) {
          $allData['categoryIds'] = array();
          while( $taxTerm = $taxTermList->fetch() ) {
            $allData['categoryIds'][] = $taxTerm->getter( 'taxonomyterm' );
          }
        }

        // Cargo los datos de Topic del recurso
        $topicsModel = new ResourceTopicModel();
        $topicsList = $topicsModel->listItems( array( 'filters' => array( 'resource' => $allData['id'] ) ) );
        if( $topicsList ) {
          $allData['topicIds'] = array();
          while( $topicVo = $topicsList->fetch() ) {
            $allData['topicIds'][] = $topicVo->getter( 'topic' );
          }
        }
      }


      // Load all REXT related models
      if( $extraParams['rextmodels'] === 'true') {
        $relatedModels = $valueobject->getRextModels();

        foreach( $relatedModels as $relModelIdName => $relModel ) {
          $rexData = array( 'MODELNAME' => $relModelIdName );
          if( method_exists( $relModel, 'getAllData' ) ) {
            $rexData = array_merge( $rexData, $relModel->getAllData( 'onlydata' ) );
            $allData['rextmodels'][] = $rexData;
          }
        }
      }


      // Collections
      if( isset( $extraParams['collection'] ) && $extraParams['collection'] === 'true' ) {
        // Cargo los datos de Collections del recurso
        $resCollModel =  new CollectionResourcesListViewModel();
        $collResList = $resCollModel->listItems( array(
          'filters' => array( 'resourceMain' => $allData['id'] )
        ) );
        // error_log( 'resColl: collResList='.print_r( $collResList, true ) );
        if( $collResList !== false ) {
          $collsOther = array();
          $collsMmedia = array();
          while( $coll = $collResList->fetch() ) {
            // error_log( 'resColl: '.print_r( $coll->getAllData( 'onlydata' ), true ) );
            $collData = array();
            $k = array( 'id', 'title', 'shortDescription', 'description', 'weight',
              'weightMain', 'resourceSonList' );
            foreach( $k as $key ) {
              $collData[ $key ] = $coll->getter( $key );
            }

            if( $coll->getter( 'collectionType' ) == 'multimedia' ) {
              $collsMmedia[ $collData['id'] ] = $collData;
            }
            else {
              $collsOther[ $collData['id'] ] = $collData;
            }
          }
        }

        $allData[ 'collectionsGeneral' ] = array();
        if( count( $collsOther ) > 0 ) {

          foreach( $collsOther as $collId => $coll ) {
            $coll[ 'resourcesData' ] = array();

            $resIds = explode( ',', $coll['resourceSonList'] );
            unset( $coll['resourceSonList'] );

            $resModel =  new ResourceModel();
            $resList = $resModel->listItems( array( 'filters' => array( 'inId' => $resIds, 'published' => 1 ) ) );

            if( $resList !== false ) {
              while( $resColl = $resList->fetch() ) {
                $resCollData = array();
                $k = array( 'id', 'rTypeId', 'title', 'shortDescription', 'mediumDescription',
                  'image', 'loc', 'timeCreation', 'timeLastUpdate', 'weight' );
                foreach( $k as $key ) {
                  $resCollData[ $key ] = $resColl->getter( $key );
                }
                if( isset( $resCollData['loc'] ) ) {
                  $loc = DBUtils::decodeGeometry( $resCollData['loc'] );
                  $resCollData['loc'] = array( 'lat' => floatval( $loc['data'][0] ) , 'lng' => floatval( $loc['data'][1] ) );
                }
                $coll[ 'resourcesData' ][] = $resCollData;
              }
            }

            $allData[ 'collectionsGeneral' ][] = $coll;
          }
        }

        $allData[ 'collectionsMultimedia' ] = array();
        if( count( $collsMmedia ) > 0 ) {

          foreach( $collsMmedia as $collId => $coll ) {
            $coll[ 'resourcesData' ] = array();

            $resIds = explode( ',', $coll['resourceSonList'] );
            unset( $coll['resourceSonList'] );

            $resModel =  new ResourceMultimediaViewModel();
            $resList = $resModel->listItems( array( 'filters' => array( 'inId' => $resIds, 'published' => 1 ) ) );

            if( $resList !== false ) {
              while( $resColl = $resList->fetch() ) {
                $resCollData = array();
                $k = array( 'id', 'rTypeId', 'title', 'shortDescription', 'image', 'timeCreation',
                  'timeLastUpdate', 'weight', 'author', 'file', 'embed', 'url' );
                foreach( $k as $key ) {
                  $resCollData[ $key ] = $resColl->getter( $key );
                }
                $coll[ 'resourcesData' ][] = $resCollData;
              }
            }

            $allData[ 'collectionsMultimedia' ][] = $coll;
          }
        }
      }



      echo $c.json_encode( $allData );
      $c=',';
    } // while
    echo ']';
  }


  public function resourceIndex( $urlParams ) {
    geozzyAPI::load('model/ResourceIndexModel.php');
    $resourceIndexModel = new ResourceIndexModel();

    $validation = array(
      'taxonomyterms'=> '#(.*)#',
      'types'=> '#(.*)#',
      'topics'=> '#(.*)#',
      'bounds'=> '#(.*)#'
    );

    $queryFilters = RequestController::processUrlParams( $urlParams, $validation );
    $queryFiltersFinal = array();


    // taxonomy terms
    if( isset($queryFilters['taxonomyterms']) && $queryFilters['taxonomyterms']!= 'false'  ) {
      $queryFiltersFinal['taxonomyterms'] = implode(',', array_map('intval', explode(',', $queryFilters['taxonomyterms'] ) ) );
    }

    // types
    if(  isset($queryFilters['types']) && $queryFilters['types']!= 'false'  ) {
      $queryFiltersFinal['types'] = array_map('intval', explode(',',$queryFilters['types']) );
    }

    // topics
    if(  isset($queryFilters['topics']) && $queryFilters['topics']!= 'false'  ) {
      $queryFiltersFinal['topics'] = array_map('intval', explode(',', $queryFilters['topics'] ) );
    }

    if( isset($queryFilters['bounds']) && $queryFilters['bounds']!= 'false' &&
      preg_match( '#(.*)\ (.*)\,(.*)\ (.*)#',
        urldecode( $queryFilters['bounds'] ),
        $bounds
      ))
    {
      if( is_numeric($bounds[1]) && is_numeric($bounds[2]) &&
          is_numeric($bounds[3]) && is_numeric($bounds[4]) )
      {

        $queryFiltersFinal['bounds'] = $bounds[1].' '.$bounds[2].','.
          $bounds[1].' '.$bounds[4].','.
          $bounds[3].' '.$bounds[4].','.
          $bounds[3].' '.$bounds[2].','.
          $bounds[1].' '.$bounds[2];
      }
    }

    $queryFiltersFinal['published'] = 1;

    $resourceList = $resourceIndexModel->listItems( array('filters' => $queryFiltersFinal, 'groupBy'=>'id') );
    header('Content-type: application/json');
    echo '[';
    $c = '';
    while( $valueobject = $resourceList->fetch() ) {
      echo $c.$valueobject->getter('id');
      $c=',';
    }
    echo ']';
  }

  public function resourceTypes() {
    geozzy::load('model/ResourcetypeModel.php');
    $resourcetypeModel = new ResourcetypeModel( );
    $resourcetypeList = $resourcetypeModel->listItems( ) ;
    $this->syncModelList( $resourcetypeList );
  }

  // Starred

  public function starred() {
    $taxtermModel = new TaxonomytermModel();
    $starredList = $taxtermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ), 'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ));

    geozzy::load('model/StarredResourcesModel.php');
    header('Content-type: application/json');

    echo '[';

    $c = '';
    while( $starred = $starredList->fetch() ) {
      $starData = $starred->getAllData('onlydata');

      $starredResourceModel = new StarredResourcesModel();
      $starredResources = $starredResourceModel->listItems( array('filters'=>array('taxonomyterm'=>$starData['id']), 'order'=>array('weight'=>1)) );

      while( $starredResource = $starredResources->fetch() ){
        $starData['resources'][] = $starredResource->getAllData('onlydata');
      }

      echo $c.json_encode( $starData );
      $c=',';
    }
    echo ']';
  }


  // Categories

  public function categoryList() {
    geozzy::load('model/TaxonomygroupModel.php');
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));
    $this->syncModelList( $taxGroupList );
  }

  public function categoryTerms( $urlParams ) {

    $validation = array('id'=> '#\d+$#', 'idname'=>'#(.*)#');
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    if( isset( $urlParamsList['id'] ) && is_numeric( $urlParamsList['id'] ) ) {
      geozzy::load('model/TaxonomytermModel.php');
      $taxtermModel = new TaxonomytermModel();
      $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$urlParamsList['id'] ) ) );
      $this->syncModelList( $taxtermList );
    }
    else
    if( isset( $urlParamsList['idname'] ) && $urlParamsList['idname'] != 'false' ) {

      geozzy::load('model/TaxonomytermModel.php');
      $taxtermModel = new TaxonomytermModel();
      $taxtermList = $taxtermModel->listItems(
        array( 'filters' => array( 'TaxonomygroupModel.idName' => $urlParamsList['idname']  )
        ,'order' => array( 'weight' => 1 )
        ,'affectsDependences' => array( 'TaxonomygroupModel' ), 'joinType' => 'RIGHT' ) );

      $this->syncModelList( $taxtermList );
    }
    else {
      header("HTTP/1.0 404 Not Found");
      header('Content-type: application/json');
      echo '{}';
    }
  }

  // Topics
  public function topicList() {
    geozzy::load('model/TopicModel.php');
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems( );
    $this->syncModelList( $topicList );
  }

  /*
    // UI events
    public function uiEventList() {
      require_once APP_BASE_PATH."/conf/geozzyUIEvents.php";
      global  $GEOZZY_UI_EVENTS;

      header('Content-type: application/json');
      echo json_encode( $GEOZZY_UI_EVENTS );
    }
  */

  public function syncModelList( $result, $lang = false ) {
    header('Content-type: application/json');
    echo '[';
    $c = '';
    global $C_LANG;

    while ($valueobject = $result->fetch() ) {
      $allData = array('id' => $valueobject->getter('id'), 'idName' => $valueobject->getter('idName'),
        'name' => $valueobject->getter('name'), 'taxgroup' => $valueobject->getter('taxgroup'),
        'icon' => $valueobject->getter('icon'), 'weight' => $valueobject->getter('weight'));
      //$allData = $valueobject->getAllData('onlydata');
      echo $c.json_encode( $allData);
      $c=',';
    }
    echo ']';
  }

  public function syncModel( $model ) {
    header('Content-type: application/json');
    $data = $model->getAllData('onlydata');
    echo json_encode( $data );
  }
}
