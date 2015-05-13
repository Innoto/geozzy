<?php
require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
geozzy::autoIncludes();
admin::autoIncludes();
user::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class AdminDataAPIView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {

    $useraccesscontrol = new UserAccessController();
    $res = true;

    if( !GEOZZY_API_ACTIVE || !$useraccesscontrol->isLogged() ){
     $res = false;
    }


    if( $res == false ) {
      header("HTTP/1.0 303");
      header('Content-type: application/json');
      echo '{}';
      exit;
    }

    return $res;

  }


  function categoryTermsJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/admin/categoryterms",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Permission denied",
                                      "code": 303
                                  },
                                  {
                                      "reason": "Category term list",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "GET",
                              "nickname": "group",
                              "parameters": [

                                  {
                                      "required": true,
                                      "dataType": "int",
                                      "name": "group",
                                      "defaultValue": "",
                                      "paramType": "query",
                                      "allowMultiple": false,
                                      "description": "Category group id"
                                  }

                              ],
                              "summary": "Get Category terms"
                          },
                          {
                            "errorResponses": [
                                  {
                                      "reason": "Permission denied",
                                      "code": 303
                                  },
                                  {
                                      "reason": "Category term Deleted",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "PUT",
                              "nickname": "id",
                              "parameters": [

                                  {
                                      "required": true,
                                      "dataType": "int",
                                      "name": "id",
                                      "defaultValue": "",
                                      "paramType": "path",
                                      "allowMultiple": false,
                                      "description": "term id"
                                  }

                              ],
                              "summary": "Insert or Update category terms"
                          },
                          {
                            "errorResponses": [
                                  {
                                      "reason": "Permission denied",
                                      "code": 303
                                  },
                                  {
                                      "reason": "Category term Deleted",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "DELETE",
                              "nickname": "id",
                              "parameters": [

                                  {
                                      "required": true,
                                      "dataType": "int",
                                      "name": "id",
                                      "defaultValue": "",
                                      "paramType": "path",
                                      "allowMultiple": false,
                                      "description": "term id"
                                  }

                              ],
                              "summary": "Delete category terms"
                          }
                      ],
                      "path": "/admin/categoryterms/{id}",
                      "description": ""
                  }
              ]
          }
        <?php
  }

  function categoriesJson() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/admin/categories",
              "basePath": "/api",
              "apis": [
                  {
                      "operations": [
                          {
                              "errorResponses": [
                                  {
                                      "reason": "Permission denied",
                                      "code": 303
                                  },
                                  {
                                      "reason": "Category term list",
                                      "code": 200
                                  },
                                  {
                                      "reason": "Category not found",
                                      "code": 404
                                  }
                              ],

                              "httpMethod": "GET",
                              "nickname": "group",
                              "parameters": [



                              ],
                              "summary": "Get Category terms"
                          }
                      ],
                      "path": "/admin/categories",
                      "description": ""
                  }
              ]
          }
    <?php
  }



  function categoryTerms( $request ) {


    $id = substr($request[1], 1);

    header('Content-type: application/json');

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':

        $putData = json_decode(file_get_contents('php://input'), true);
        $taxtermModel = new TaxonomytermModel();

        if( is_numeric( $id )) {  // UPDATE
          $taxTerm = $taxtermModel->listItems(  array( 'filters' => array( 'id'=>$id ) ))->fetch();
        }
        else { // CREATE
          $taxTerm = $taxtermModel;
        }

        if( isset( $putData['name'] ) ) {
          $taxTerm->setter('name', $putData['name'] );
        }

        if( isset( $putData['parent'] ) ) {
          $taxTerm->setter('parent', $putData['parent'] );
        }

        if( isset( $putData['weight'] ) ) {
          $taxTerm->setter('weight', $putData['weight'] );
        }

        if( isset( $putData['taxgroup'] ) ) {
          $taxTerm->setter('taxgroup', $putData['taxgroup'] );
        }

        $taxTerm->save();

        $termData = $taxTerm->getAllData();
        echo json_encode( $termData['data'] );

        break;
      case 'GET':

        if( array_key_exists( 'group', $_GET ) && is_numeric( $_GET['group'] ) ){

          $taxtermModel = new TaxonomytermModel();
          $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$_GET['group']) ) );
          echo '[';
          $c = '';
          while ($taxTerm = $taxtermList->fetch() )
          {
            $termData = $taxTerm->getAllData();
            echo $c.json_encode( $termData['data'] );
            if($c === ''){$c=',';}
          }
          echo ']';

        }
        else{
          header("HTTP/1.0 404 Not Found");
        }

        break;

      case 'DELETE':
        $taxM = new TaxonomytermModel();
        $taxTerm = $taxM->listItems(
                                    array(
                                            'filters' => array('id'=> $id, 'TaxonomygroupModel.editable'=>1),
                                            'affectsDependences' => array('TaxonomygroupModel'),
                                            'joinType' => 'INNER'
                                        )
                                    );
        if( $taxTerm && $t = $taxTerm->fetch() ) {
          $t->delete();
          header('Content-type: application/json');
          echo '{}';
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-type: application/json');
          echo '{}';
        }

        break;
    }



  }

}
