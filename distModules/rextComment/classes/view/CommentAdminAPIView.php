<?php

require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/MailController.php');

/**
* Clase Master to extend other application methods
*/
class CommentAdminAPIView extends View {

  public function __construct( $baseDir = false ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $access = true;

    if( !$useraccesscontrol->isLogged() ){
      $access = false;
    }

    $access = $useraccesscontrol->checkPermissions( array('admin:access'), 'admin:full');
    if(!$access){
      $access = false;
    }

    if( !$access ) {
      header("HTTP/1.0 401");
      header('Content-Type: application/json; charset=utf-8');
      echo '[]';
      exit;
    }

    return $access;
  }



  // URL: /api/admin/commentsuggestion/
  public function commentsSuggestions( $urlParams ) {

    $validation = array( 'comment' => '#\d+$#', 'resource' => '#\d+$#' );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);
    $commentId = isset( $urlParamsList['comment'] ) ? $urlParamsList['comment'] : false;
    $resourceId = isset( $urlParamsList['resource'] ) ? $urlParamsList['resource'] : false;

    geozzy::load('model/TaxonomygroupModel.php');
    geozzy::load('model/TaxonomytermModel.php');
    rextComment::load('model/CommentModel.php');

    // header('Content-Type: application/json; charset=utf-8');

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':
        $putData = json_decode( file_get_contents('php://input'), true );

        $commentModel = new CommentModel();
        $comment = $commentModel->listItems(  array( 'filters' => array( 'id'=> $putData['id'] ) ))->fetch();
        if($comment){
          if( isset( $putData['published'] ) ) {
            if($putData['published']){
              $comment->setter('published', true );
            }
            else{
              $comment->setter('published', false);
            }
          }
          if( isset( $putData['status'] ) &&  isset($putData['statusIdName'])  ) {

            $taxTermModel = new TaxonomytermModel( );
            $term = $taxTermModel->listItems( array('filters' => array('idName' => $putData['statusIdName'] ) ))->fetch();

            if($term && $term->getter('id') !== $putData['status']){
              $comment->setter('status', $term->getter('id') );

              $tpl = new Template();
              switch ( $term->getter('idName') ) {
                case 'commentDenied':
                  $tpl->setTpl( 'mailCommentDenied.tpl', 'rextComment');
                  $titleMail = __("Gracias por su aportación");
                  break;
                case 'commentValidated':
                  $tpl->setTpl( 'mailCommentValidated.tpl', 'rextComment');
                  $titleMail = __("Gracias por su aportación");
                  break;
              }

              if($comment->getter('user') && is_numeric($comment->getter('user'))){
                $userModel = new UserModel();
                $user = $userModel->listItems( array('filters' => array('id' => $comment->getter('user') ) ) )->fetch();
                $to = $user->getter('email');
              }
              else{
                $to = $comment->getter('anonymousEmail');
              }

              $mailControl = new MailController();
              $mailControl->clear();
              $mailControl->setBodyHtml( $tpl );
              $mailControl->send( array( $to ), $titleMail );

            }
          }
          $comment->save();
          $commentData = $comment->getAllData();
          header('Content-Type: application/json; charset=utf-8');
          echo json_encode( $commentData['data'] );
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-Type: application/json; charset=utf-8');
          echo '[]';
        }
      break;
      case 'DELETE':
        $commentModel = new CommentModel();
        $comment = $commentModel->listItems(
          array(
            'filters' => array('id'=> $commentId)
          )
        );
        if( $comment && $c = $comment->fetch() ) {
          $idResource = $c->getter('resource');
          $c->delete();
          $averageVotesModel = new AverageVotesViewModel();
          $resAverageVotes = $averageVotesModel->listItems( array( 'filters' => array('id' => $idResource ) ))->fetch();

          if(!$resAverageVotes){
            $averageVotes = NULL;
          }else{
            $averageVotes = $resAverageVotes->getter('averageVotes');
          }

          $resourceModel = new ResourceModel( array('id' => $c->getter('resource'), 'averageVotes' => $averageVotes ));
          $resourceModel->save();

          header('Content-Type: application/json; charset=utf-8');
          echo '[]';
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-Type: application/json; charset=utf-8');
          echo '[]';
        }
      break;
      case 'GET':
        if( isset( $resourceId ) && is_numeric( $resourceId ) ) {
          $commentModel = new CommentModel();
          $commentsList = $commentModel->listItems(  array(
            'filters' => array( 'resource'=> $resourceId ),
            'order' => array( 'timeCreation' => -1 ),
            'affectsDependences' => array('UserModel','TaxonomytermModel')
          ) );

          header('Content-Type: application/json; charset=utf-8');
          echo '[';
          $c = '';
          global $C_LANG;

          while ($valueobject = $commentsList->fetch() ) {
            $allData = $valueobject->getAllData('onlydata');
            $user = $valueobject->getterDependence('user');

            if($user){
              $allData['userName'] = $user[0]->getter('name');
              $allData['userEmail'] = $user[0]->getter('email');
              $allData['userVerified'] = $user[0]->getter('verified');
            }
            $ctype = $valueobject->getterDependence('type');
            if($ctype){
              $allData['typeIdName'] = $ctype[0]->getter('idName');
            }
            $suggestType = $valueobject->getterDependence('suggestType');
            if($suggestType){
              $allData['suggestTypeName'] = $suggestType[0]->getter('name');
            }
            $suggestStatus = $valueobject->getterDependence('status');
            if($suggestStatus){
              $allData['statusIdName'] = $suggestStatus[0]->getter('idName');
            }

            echo $c.json_encode($allData);
            $c=',';
          }
          echo ']';
        }
        else {
          header("HTTP/1.0 404 Not Found");
          header('Content-Type: application/json; charset=utf-8');
          echo '{}';
        }
      break;
      default:
        header("HTTP/1.0 404 Not Found");
      break;
    }
  }


  public function commentsSuggestionsJson() {
    header('Content-Type: application/json; charset=utf-8');
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
                    "name": "id",
                    "paramType": "path",
                    "allowMultiple": false,
                    "defaultValue": "false",
                    "description": "resource id"
                  }
                ],
                "summary": "Fetches comments"
              },
              {
                "errorResponses": [
                      {
                          "reason": "Permission denied",
                          "code": 401
                      },
                      {
                          "reason": "Comment updated",
                          "code": 200
                      },
                      {
                          "reason": "Comment not found",
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
                          "description": "comment id"
                      }

                  ],
                  "summary": "Insert or Update category terms"
              },
              {
                "errorResponses": [
                      {
                          "reason": "Permission denied",
                          "code": 401
                      },
                      {
                          "reason": "Comment Deleted",
                          "code": 200
                      },
                      {
                          "reason": "Comment not found",
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
                          "description": "comment id"
                      }

                  ],
                  "summary": "Delete category terms"
              }
            ],
            "path": "/admin/commentsuggestion/resource/{id}",
            "description": ""
          }
        ]
      }
    <?php
  }
}
