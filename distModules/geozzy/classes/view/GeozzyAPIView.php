<?php


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
    return true;
  }


  public function biJson() {
    header('Content-Type: application/json; charset=utf-8');
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
    header('Content-Type: application/json; charset=utf-8');
    ?>
    {
      "resourcePath": "/resources.json",
      "basePath": "/api",
      "apis": [
        {
          "path": "/core/resourcelist/fields/{fields}/filters/{filters}/filtervalues/{filtervalues}/rtype/{rtype}/rextmodels/{rextmodels}/category/{category}/collection/{collection}/votes/{votes}/updatedfrom/{updatedfrom}/urlAlias/{urlAlias}",
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
                  "description": "Resource Type (rtypeId separed by comma)",
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
                  "name": "votes",
                  "description": "Votes data",
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
                },
                {
                  "name": "urlAlias",
                  "description": "urlAlias relation",
                  "dataType": "string",
                  "paramType": "path",
                  "defaultValue": "false",
                  "required": false
                }
              ],
              "summary": "Fetches resource list"
            }
          ],
          "description": ""
        }
      ]
    }
    <?php
  }


  public function resourceIndexJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/resources.json",
        "basePath": "/api",
        "apis": [
          {
            "path": "/core/resourceIndex/taxonomyterms/{taxonomyterms}/types/{types}/topics/{topics}/bounds/{bounds}",
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
            "description": ""
          }
        ]
      }
    <?php
  }


  public function resourceTypesJson() {
    header('Content-Type: application/json; charset=utf-8');
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

  public function collectionsJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/collections.json",
        "basePath": "/api",
        "apis": [
          {
            "path": "/collections/{collections}/typeNames/{typeNames}/resources/{resources}/options/{options}",
            "description": "",
            "operations": [
              {
                "errorResponses": [
                  {
                    "reason": "Bad Request",
                    "code": 400
                  },
                  {
                    "reason": "Not found",
                    "code": 404
                  }
                ],
                "httpMethod": "GET",
                "nickname": "Get collections",
                "parameters": [
                  {
                    "required": false,
                    "dataType": "int",
                    "name": "collections",
                    "paramType": "path",
                    "allowMultiple": true,
                    "defaultValue": "false",
                    "description": "Collections Ids"
                  },
                  {
                    "required": false,
                    "dataType": "string",
                    "name": "typeNames",
                    "paramType": "path",
                    "allowMultiple": true,
                    "defaultValue": "false",
                    "description": "CollectionType names"
                  },
                  {
                    "required": false,
                    "dataType": "int",
                    "name": "resources",
                    "paramType": "path",
                    "allowMultiple": true,
                    "defaultValue": "false",
                    "description": "Resource Ids"
                  },
                  {
                    "required": false,
                    "dataType": "string",
                    "name": "options",
                    "paramType": "path",
                    "allowMultiple": false,
                    "defaultValue": "false",
                    "description": "Alter results: plain, extend, ..."
                  }
                ],
                "summary": "Get collections"
              }
            ]
          }
        ]
      }
    <?php
  }

  public function starredJson() {
    header('Content-Type: application/json; charset=utf-8');
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
    header('Content-Type: application/json; charset=utf-8');
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
    header('Content-Type: application/json; charset=utf-8');
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
    header('Content-Type: application/json; charset=utf-8');
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

  public function userLoginJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/userLogin.json",
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
                "httpMethod": "POST",
                "nickname": "userLogin",
                "parameters": [
                  {
                    "name": "user",
                    "description": "User login",
                    "type": "string",
                    "paramType": "form",
                    "required": true
                  },
                  {
                    "name": "pass",
                    "description": "Password",
                    "type": "string",
                    "paramType": "form",
                    "required": true
                  }
                ],
                "summary": "User Login"
              }
            ],
            "path": "/core/userlogin",
            "description": ""
          }
        ]
      }
    <?php
  }

  public function userLogoutJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/userLogout.json",
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
                "httpMethod": "POST",
                "nickname": "userLogout",
                "parameters": [],
                "summary": "User Logout"
              }
            ],
            "path": "/core/userlogout",
            "description": ""
          }
        ]
      }
    <?php
  }

  public function cgmlSessionJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/cgml-session.json",
        "basePath": "/",
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
                "nickname": "CogumeloSession",
                "parameters": [],
                "summary": "Get Cogumelo session info"
              }
            ],
            "path": "/cgml-session.json",
            "description": ""
          }
        ]
      }
    <?php
  }

  public function userUnknownPassJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/userUnknownPass.json",
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
                "httpMethod": "POST",
                "nickname": "userUnknownPass",
                "parameters": [
                  {
                    "name": "user",
                    "description": "User login",
                    "type": "string",
                    "paramType": "form",
                    "required": true
                  }
                ],
                "summary": "User unknown password"
              }
            ],
            "path": "/core/userunknownpass",
            "description": ""
          }
        ]
      }
    <?php
  }



  public function userSessionJson() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/userSession.json",
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
                "nickname": "userSession",
                "parameters": [],
                "summary": "User session"
              }
            ],
            "path": "/core/usersession",
            "description": ""
          }
        ]
      }
    <?php
  }

  /*
    public function uiEventListJson() {
      header('Content-Type: application/json; charset=utf-8');
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
    require_once APP_BASE_PATH."/conf/inc/geozzyBI.php";
    header('Content-Type: application/json; charset=utf-8');
    global $BI_SITE_SECTIONS, $BI_DEVICES, $BI_METRICS_EXPLORER, $BI_METRICS_RESOURCE, $BI_GEOZZY_UI_EVENTS, $BI_RECOMMENDS;

    $langs = array(
      'default'=> Cogumelo::getSetupValue( 'lang:default' ),
      'available'=> Cogumelo::getSetupValue( 'lang:available' )
    );

    $biData = array(
      'languages' => $langs,
      'devices' => $BI_DEVICES,
      'sections' => $BI_SITE_SECTIONS,
      'ui_events' => $BI_GEOZZY_UI_EVENTS,
      'metrics' => array(
        'explorer' => $BI_METRICS_EXPLORER,
        'resource' => $BI_METRICS_RESOURCE
      ),
      'recommends' => $BI_RECOMMENDS
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
      $biData['users'] = [];
      if( $userList ) {
        while( $userVo = $userList->fetch() ) {
          $biData['users'][] = array(
            'id' => $userVo->getter('id'),
            'login' => $userVo->getter('login')
          );
        }
      }
    }

    if( isset( $extraParams['virtualBags'] ) && $extraParams['virtualBags'] === 'true' && class_exists( 'FavouritesViewModel' ) ) {

      $favModel = new FavouritesListViewModel();
      $favList = $favModel->listItems( array( 'filters' => array( 'resourceListNotNull' => true ) ) );
      if( $favList ) {
        $biData['virtualBags'] = [];
        while( $favObj = $favList->fetch() ) {
          $favData = $favObj->getAllData( 'onlydata' );
          $biData['virtualBags'][] = array(
            'userId' => $favData['user'],
            'virtualBags' => ( isset( $favData['resourceList'] ) ) ? explode( ',', $favData['resourceList'] ) : []
          );
        }
      }
    }

    echo json_encode( $biData );
  }


  // /resourcelist (Declarado en resources.json)
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
      'votes'=> '#^(true|false)$#',
      'updatedfrom' => '#^(\d+)$#',
      'urlAlias' => '#(.*)#'
    );

    $extraParams = RequestController::processUrlParams( $param, $validation );

    // Validamos el parametro "ids" y, si es correcto, lo añadimos en $extraParams['ids']
    $extraParams['ids'] = false;
    if( isset( $_POST['ids'] ) ) {
      $tmpIds = is_array( $_POST['ids'] ) ? implode( ',', $_POST['ids'] ) : $_POST['ids'];
      $extraParams['ids'] = preg_match( '/^\d+(,\d+)*$/', $tmpIds ) ? $tmpIds : false;
    }


    //$queryParameters = apiFiltersController::resourceListOptions( $param );


    // Cargo la tabla de tipos de recurso (RTypes)
    $infoRTypes = $this->loadInfoRTypes();
    $infoRTypeIdNames = array_column( $infoRTypes, 'id', 'idName' );
    $infoRTypeNameIds = array_column( $infoRTypes, 'idName', 'id' );


    // Cargo la tabla de votos en recursos (comentarios)
    $votesInfo = false;
    if( isset( $extraParams['votes'] ) && $extraParams['votes'] === 'true' ) {
      $commCtrl = new RExtCommentController();
      $votesInfo = $commCtrl->getVotes( $extraParams['ids'] );
    }


    $resourceModel = new ResourceModel();
    $queryParameters = [];


    // Bloqueo recursos no deseados
    if( isset( $infoRTypeIdNames['rtypeFavourites'] ) ) {
      $queryParameters['filters']['notInRtype'] = array( $infoRTypeIdNames['rtypeFavourites'] );
    }


    // Resource types
    if( isset( $extraParams['rtype'] ) && $extraParams['rtype'] !== 'false' ) {
      $paramRtypeIds = explode(',', $extraParams['rtype']);

      $idsToFilter = [];
      foreach( $paramRtypeIds as $paramRtypeId ) {
        $idsToFilter[] = $infoRTypeIdNames[ $paramRtypeId ];
      }

      $queryParameters['filters']['inRtype'] =  $idsToFilter ;
    }

    // Category
    //if( isset( $extraParams['category'] ) && $extraParams['category'] === 'true' ) {
    //  $queryParameters['affectsDependences'][] = 'ResourceTaxonomytermModel';
    //  $queryParameters['affectsDependences'][] = 'ResourceTopicModel';
    //}



    // $queryParameters['affectsDependences'][] = 'ResourceTopicModel';


    global $C_LANG;
    // fields
    $fieldsToFilter = false;
    if( isset( $extraParams['fields'] ) && $extraParams['fields'] !== 'false' ) {
      $fieldsP = explode( ',', $extraParams['fields'] );
      $fieldsToFilter = [];
      foreach( $fieldsP as $fieldName ) {
        $fieldsToFilter[] = $fieldName;
        $fieldsToFilter[] = $fieldName.'_'.$C_LANG;
      }
      $queryParameters['fields'] = apiFiltersController::clearFields( $fieldsToFilter );
    }



    $queryParameters['filters']['published'] = 1;

    // updatedfrom
    if( isset( $extraParams['updatedfrom'] ) ) {
      $queryParameters['filters']['updatedfrom'] = gmdate( 'Y-m-d H:i:s', $extraParams['updatedfrom'] );
    }


    // fields and fieldvalues
    if( isset( $extraParams['filters'], $extraParams['filtervalues'] ) && $extraParams['filters'] !== 'false' && $extraParams['filtervalues'] !== 'false' ) {

      // check if number of parameters and values are the same
      $autoFiltervalues = explode( ',', $extraParams['filtervalues'] );
      $autoFilters = explode( ',', $extraParams['filters'] );

      if( count($autoFiltervalues) === count($autoFilters) && count($autoFilters)>0 ) {


        foreach( $autoFilters as $autoFilterK => $autoFilter ) {

          $queryParameters['filters'][$autoFilter] = $autoFiltervalues[ $autoFilterK ];
        }
      }


    }



    if( $extraParams['ids'] ) {
      $inArray = explode( ',', $extraParams['ids'] );
      if( count( $inArray ) > 1 ) {
        $queryParameters['filters']['ids'] = $inArray;
      }
      else {
        $queryParameters['filters']['id'] = intval( $extraParams['ids'] );
      }
    }

    // error_log( '$queryParameters = '.print_r( $queryParameters, true ) );
    $resourceList = $resourceModel->listItems( $queryParameters );

    if( isset( $extraParams['urlAlias'] ) && $extraParams['urlAlias'] === 'true' ) {
      $urlAliasModel = new UrlAliasModel();
      $urlAliasList = $urlAliasModel->listItems( );
      $urls = [];
      while( $urlAlias = $urlAliasList->fetch() ) {
        $urls[ $urlAlias->getter('resource') ] = $urlAlias->getter('urlFrom');
      }
    }


    header('Content-Type: application/json; charset=utf-8');
    echo '[';
    $c = '';
    while( $valueobject = $resourceList->fetch() ) {
      $allData = [];

      //$allCols = $valueobject->getCols(false);
      $allCols = array( 'id', 'rTypeId', 'title', 'shortDescription', 'mediumDescription', 'content',
        'image', 'loc', 'defaultZoom', 'externalUrl' );
      foreach( $allCols as $col ) {
        if( !$fieldsToFilter || in_array( $col, $fieldsToFilter ) ) {
          $allData[ $col ] = $valueobject->getter( $col );
          if( $col === 'rTypeId' ) {
            $allData[ 'rTypeIdName' ] = isset($infoRTypeNameIds[ $allData[ 'rTypeId' ] ]) ? $infoRTypeNameIds[ $allData[ 'rTypeId' ] ] : null;
          }
        }
      }
      if( isset( $allData['loc'] ) ) {
        $loc = DBUtils::decodeGeometry( $allData['loc'] );
        $allData['loc'] = array( 'lat' => floatval( $loc['data'][0] ) , 'lng' => floatval( $loc['data'][1] ) );
      }


      // URLAlias precargados. TODOS!!!
      if( isset( $extraParams['urlAlias'] ) && $extraParams['urlAlias'] === 'true' ) {
        if( array_key_exists( $valueobject->getter('id'), $urls ) ) {
          $allData['urlAlias'] = $urls[ $valueobject->getter('id') ];
        }
        else{
          $allData['urlAlias'] = '/resource/'.$valueobject->getter('id');
        }
      }


      // Category
      if( isset( $extraParams['category'] ) && $extraParams['category'] === 'true' ) {
        // Cargo los datos de Term del recurso
        $taxTermModel =  new ResourceTaxonomytermModel();
        $taxTermList = $taxTermModel->listItems( array( 'filters' => array( 'resource' => $allData['id'] ) ) );
        if( $taxTermList !== false ) {
          $allData['categoryIds'] = [];
          while( $taxTerm = $taxTermList->fetch() ) {
            $allData['categoryIds'][] = $taxTerm->getter( 'taxonomyterm' );
          }
        }

        // Cargo los datos de Topic del recurso
        $topicsModel = new ResourceTopicModel();
        $topicsList = $topicsModel->listItems( array( 'filters' => array( 'resource' => $allData['id'] ) ) );
        if( $topicsList ) {
          $allData['topicIds'] = [];
          while( $topicVo = $topicsList->fetch() ) {
            $allData['topicIds'][] = $topicVo->getter( 'topic' );
          }
        }
      }


      // Load all REXT related models
      if( isset($extraParams['rextmodels']) && $extraParams['rextmodels'] === 'true') {
        $relatedModels = $valueobject->getRextModels();

        foreach( $relatedModels as $relModelIdName => $relModel ) {
          if( $relModelIdName === 'FavouritesViewModel' ) {
            continue;
          }

          $rexData = array( 'MODELNAME' => $relModelIdName );

          if( method_exists( $relModel, 'getAllData' ) ) {
            $allCols = $relModel->getCols( false );
            foreach( $allCols as $colName => $colInfo ) {
              $rexData[ $colName ] = $relModel->getter( $colName );
            }

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

        if( $collResList !== false ) {
          $allData[ 'collections' ] = [];
          while( $coll = $collResList->fetch() ) {
            $collData = [];
            $k = array( 'id', 'title', 'shortDescription', 'description', 'weight',
              'weightMain', 'resourceSonList' );
            foreach( $k as $key ) {
              $collData[ $key ] = $coll->getter( $key );
            }
            $collType = $coll->getter( 'collectionType' );
            $allData[ 'collections' ][ $collType ][ $collData['id'] ] = $collData;
          }
        }

        $allData[ 'collectionsGeneral' ] = [];
        if( isset( $allData['collections']['base'] ) && count( $allData['collections']['base'] ) > 0 ) {
          foreach( $allData['collections']['base'] as $collId => $coll ) {
            $coll[ 'resourcesData' ] = $this->extendCollBase( $coll['resourceSonList'] );
            $allData[ 'collectionsGeneral' ][] = $coll;
          }
        }

        $allData[ 'collectionsMultimedia' ] = [];
        if( isset( $allData['collections']['multimedia'] ) && count( $allData['collections']['multimedia'] ) > 0 ) {
          foreach( $allData['collections']['multimedia'] as $collId => $coll ) {
            $coll[ 'resourcesData' ] = $this->extendCollMultimedia( $coll['resourceSonList'] );
            $allData[ 'collectionsMultimedia' ][] = $coll;
          }
        }
      }


      // Votes
      if( $votesInfo && isset( $votesInfo[ $allData['id'] ] ) ) {
        $allData['votes'] = array(
          'count' => intval( $votesInfo[ $allData['id'] ]['count'] ),
          'average' => intval( $votesInfo[ $allData['id'] ]['average'] )
        );
      }

      echo $c.json_encode( $allData );
      $c=',';
    } // while
    echo ']';
  }



  // /resourceIndex
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
    header('Content-Type: application/json; charset=utf-8');
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



  // URL: /api/collections
  public function collections( $urlParams = false ) {
    // error_log( 'collections( '.json_encode($urlParams).' )' );

    $collsData = array();

    if( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
      $validation = array( 'collections' => '#^\d+(,\d+)*$#', 'typeNames' => '#^[a-z]+(,[a-z]+)*$#',
        'resources' => '#^\d+(,\d+)*$#', 'options' => '#^[a-z]+(,[a-z]+)*$#' );
      $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );
      $collectionsId = isset( $urlParamsList['collections'] ) ? $urlParamsList['collections'] : false;
      $typeNames = isset( $urlParamsList['typeNames'] ) ? $urlParamsList['typeNames'] : false;
      $resourcesId = isset( $urlParamsList['resources'] ) ? $urlParamsList['resources'] : false;
      if( isset( $urlParamsList['options'] ) && $urlParamsList['options'] !== false ) {
        $options = explode( ',', $urlParamsList['options'] );
      }
      else {
        $options = false;
      }

      $filters = array( 'collectionTypeNotIn' => array( 'favourites' ) );

      if( $collectionsId && $collectionsId !== 'false' ) {
        $inArray = explode( ',', $collectionsId );
        if( count( $inArray ) > 1 ) {
          $filters['idIn'] = $inArray;
        }
        else {
          $filters['id'] = intval( $collectionsId );
        }
      }
      if( $typeNames && $typeNames !== 'false' ) {
        $inArray = explode( ',', $typeNames );
        if( count( $inArray ) > 1 ) {
          $filters['collectionTypeIn'] = $inArray;
        }
        else {
          $filters['collectionType'] = $typeNames;
        }
      }
      if( $resourcesId && $resourcesId !== 'false' ) {
        $inArray = explode( ',', $resourcesId );
        if( count( $inArray ) > 1 ) {
          $filters['resourceMainIn'] = $inArray;
        }
        else {
          $filters['resourceMain'] = intval( $resourcesId );
        }
      }

      $format = 'byType';
      if( $options ) {
        if( in_array( 'plain', $options ) ) {
          $format = 'plain';
        }
      }

      // Cargo los datos de Collections del recurso
      $resCollModel =  new CollectionResourcesListViewModel();
      $collResList = $resCollModel->listItems( array( 'filters' => $filters ) );
      if( $collResList !== false ) {
        while( $coll = $collResList->fetch() ) {
          $collFields = array();
          $fields = array( 'id', 'collectionType', 'title', 'shortDescription', 'description', 'weight',
            'weightMain', 'resourceMain', 'resourceSonList' );
          foreach( $fields as $fieldName ) {
            $collFields[ $fieldName ] = $coll->getter( $fieldName );
          }

          if( $options && in_array( 'extend', $options ) ) {
            switch( $collFields[ 'collectionType' ] ) {
              case 'base':
                $collFields[ 'resourcesData' ] = $this->extendCollBase( $collFields[ 'resourceSonList' ] );
                break;
              case 'multimedia':
                $collFields[ 'resourcesData' ] = $this->extendCollMultimedia( $collFields[ 'resourceSonList' ] );
                break;
            }
          }

          switch( $format ) {
            case 'byType':
              $collsData[ $collFields['collectionType'] ][ $collFields['id'] ] = $collFields;
              break;

            case 'plain':
              $collsData[] = $collFields;
              break;

            default:
              $collsData[ $collFields['collectionType'] ][ $collFields['id'] ] = $collFields;
              break;
          }
        }
      }

      header('Content-Type: application/json; charset=utf-8');
      echo json_encode( $collsData );
    }
    else {
      header("HTTP/1.0 400 Bad Request");
    }
  }



  // Starred

  public function starred() {
    $taxtermModel = new TaxonomytermModel();
    $starredList = $taxtermModel->listItems(array( 'filters' => array( 'TaxonomygroupModel.idName' => 'starred' ),
      'affectsDependences' => array('TaxonomygroupModel'), 'joinType' => 'RIGHT' ));

    geozzy::load('model/StarredResourcesModel.php');
    header('Content-Type: application/json; charset=utf-8');

    echo '[';
    $c = '';
    while( $starred = $starredList->fetch() ) {
      $starData = array();

      $allCols = $starred->getCols(false);
      foreach( $allCols as $key => $col ){
        $starData[ $key ] = $starred->getter( $key );
      }

      $starredResourceModel = new StarredResourcesModel();
      $starredResources = $starredResourceModel->listItems( array('filters'=>array('taxonomyterm'=>$starData['id']),
        'order'=>array('weight'=>1)) );

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
      header('Content-Type: application/json; charset=utf-8');
      echo '{}';
    }
  }

  // Topics
  public function topicList() {
    geozzy::load('model/TopicModel.php');
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems( [ 'order' => [ 'weight' => 1, 'idName' => 1 ] ] );
    $this->syncModelList( $topicList );
  }

  // User login
  public function userLogin() {
    $status = false;

    if( isset( $_POST['user'] ) && isset( $_POST['pass'] ) ) {
      $useraccesscontrol = new UserAccessController();
      $status = $useraccesscontrol->userLogin( $_POST['user'], $_POST['pass'] );
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $status );
  }

  // User logout
  public function userLogout() {
    $useraccesscontrol = new UserAccessController();
    $status = $useraccesscontrol->userLogout();

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $status );
  }

  // User new password
  public function userUnknownPass() {
    $status = false;

    if( isset( $_POST['user'] ) ) {
      geozzyUser::load( 'view/GeozzyUserView.php' );
      $userView = new GeozzyUserView();
      $userVO = $userView->getUserVO( false, $_POST['user'] );
      $userData = ( $userVO ) ? $userVO->getAllData('onlydata') : false;
      if( $userData ) {
        $status = $userView->sendUnknownPassEmail( $userData );
      }
      else {
        error_log( '(Notice) Intento de recuperacion de contraseña con usuario desconocido: '.$_POST['user'] );
      }

      // Ocultamos el estado interno
      $status = true;
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $status );
  }

  // userSession
  public function userSession() {
    $userInfo = false;

    user::autoIncludes();
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();

    if( $user ) {
      $userInfo = array();
      $userInfo['id'] = $user['data']['id'];
      $userInfo['login'] = $user['data']['login'];
      $userInfo['name'] = ( isset( $user['data']['name'] ) ) ? $user['data']['name'] : null;
      $userInfo['surname'] = ( isset( $user['data']['surname'] ) ) ? $user['data']['surname'] : null;
      $userInfo['email'] = ( isset( $user['data']['email'] ) ) ? $user['data']['email'] : null;
      $userInfo['active'] = $user['data']['active'];
      $userInfo['timeLastLogin'] = ( isset( $user['data']['timeLastLogin'] ) ) ? $user['data']['timeLastLogin'] : null;
      $userInfo['timeCreateUser'] = $user['data']['timeCreateUser'];
      if( array_key_exists('avatar', $user['data']) ){
        $userInfo['avatar'] = $user['data']['avatar'];
      }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $userInfo );
  }


  /*
    // UI events
    public function uiEventList() {
      require_once APP_BASE_PATH."/conf/inc/geozzyUIEvents.php";
      global  $GEOZZY_UI_EVENTS;

      header('Content-Type: application/json; charset=utf-8');
      echo json_encode( $GEOZZY_UI_EVENTS );
    }
  */

  public function syncModelList( $result, $lang = false ) {
    header('Content-Type: application/json; charset=utf-8');
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
    header('Content-Type: application/json; charset=utf-8');
    $data = $model->getAllData('onlydata');
    echo json_encode( $data );
  }

  public function loadInfoRTypes() {
    $infoRTypes = array();

    $rTypeModel = new ResourcetypeModel();
    $rTypeList = $rTypeModel->listItems();
    while( $rTypeObj = $rTypeList->fetch() ) {
      $allData = $rTypeObj->getAllData('onlydata');
      $infoRTypes[ $allData['id'] ] = $allData;
    }

    return $infoRTypes;
  }


  private function extendCollBase( $resourceSonList ) {
    Cogumelo::load('coreModel/DBUtils.php');
    $resCollData = array();

    $resIds = explode( ',', $resourceSonList );

    if( count( $resIds ) > 0 ) {
      $resModel =  new ResourceModel();
      $resList = $resModel->listItems( array( 'filters' => array( 'inId' => $resIds, 'published' => 1 ) ) );

      if( $resList !== false ) {
        $resCollData_tmp = array();
        while( $resColl = $resList->fetch() ) {
          $k = array( 'id', 'rTypeId', 'title', 'shortDescription', 'mediumDescription',
            'image', 'loc', 'timeCreation', 'timeLastUpdate', 'weight' );
          foreach( $k as $key ) {
            $resCollData_tmp[ $resColl->getter('id') ][ $key ] = $resColl->getter( $key );
          }
          if( isset( $resCollData_tmp[ $resColl->getter('id') ]['loc'] ) ) {
            $loc = DBUtils::decodeGeometry( $resCollData_tmp[ $resColl->getter('id') ]['loc'] );
            $resCollData_tmp[ $resColl->getter('id') ]['loc'] = array(
              'lat' => floatval( $loc['data'][0] ), 'lng' => floatval( $loc['data'][1] ) );
          }
        }
        /* Reordenamos los recursos de la colección por el orden que traian */
        foreach( $resIds as $id ) {
          if( isset($resCollData_tmp[$id]) ) {
            array_push($resCollData, $resCollData_tmp[$id]);
          }
        }
      }
    }

    return $resCollData;
  }


  private function extendCollMultimedia( $resourceSonList ) {
    $resCollData = array();

    $resIds = explode( ',', $resourceSonList );

    if( count( $resIds ) > 0 ) {

      $resModel =  new ResourceMultimediaViewModel();
      $resList = $resModel->listItems( array( 'filters' => array( 'inId' => $resIds, 'published' => 1 )) );

      if( $resList !== false ) {
        $resCollData_tmp = array();
        while( $resColl = $resList->fetch() ) {
          $k = array( 'id', 'rTypeId', 'title', 'shortDescription', 'image', 'timeCreation',
            'timeLastUpdate', 'weight', 'author', 'file', 'embed', 'url' );
          foreach( $k as $key ) {
            $resCollData_tmp[ $resColl->getter('id') ][ $key ] = $resColl->getter( $key );
          }
        }

        /* Reordenamos los recursos de la colección por el orden que traian */
        foreach( $resIds as $id ) {
          array_push($resCollData, $resCollData_tmp[$id]);
        }
      }
    }

    return $resCollData;
  }

}
