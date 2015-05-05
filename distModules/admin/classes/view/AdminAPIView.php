<?php
require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
geozzy::autoIncludes();
admin::autoIncludes();
user::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class AdminAPIView extends View
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

    if( !GEOZZY_API_ACTIVE ){
     $res = false;
    }
    else 
    if( !$useraccesscontrol->isLogged() ) {
      header("HTTP/1.0 303");
      header('Content-type: application/json');
      echo '{}';
      exit;        
    }

    return $res;    

  }


  function main() {
    header('Content-type: application/json');


    ?>
          {
              "resourcePath": "/admin",
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
                                      "required": false,
                                      "dataType": "int",
                                      "name": "group",
                                      "defaultValue": "",
                                      "paramType": "query",
                                      "allowMultiple": false,
                                      "description": "Category group id"
                                  }

                              ]
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
                                      "required": false,
                                      "dataType": "int",
                                      "name": "id",
                                      "defaultValue": "",
                                      "paramType": "path",
                                      "allowMultiple": false,
                                      "description": "term id"
                                  }

                              ]
                          }                          
                      ],
                      "path": "/admin/categoryterms/{id}",
                      "description": ""
                  }
              ]
          }
        <?php
  }



  function categoryTermsSync( $request ) {


    $id = substr($request[1], 1);

    header('Content-type: application/json');

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':

        $putData = json_decode(file_get_contents('php://input'), true);

        if( is_numeric( $id )) {  // update
          $taxtermModel = new TaxonomytermModel();
          $taxTerm = $taxtermModel->listItems(  array( 'filters' => array( 'id'=>$id ) ))->fetch();
          $taxTerm->setter('name', $putData['name']);
          $taxTerm->save();
        }
        else { // create
          $taxTerm = new TaxonomytermModel( array('name'=> $putData['name'], 'parent'=> null, 'taxgroup'=> $putData['taxgroup']) );
          $taxTerm->save();

        }      

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
        }

        break;
    }

  

  }

  function categoriesSync() {
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      $taxData = $taxGroup->getAllData();
      echo $c.json_encode( $taxData['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';
  
  }


}