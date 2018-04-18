<?php

Cogumelo::load('coreView/View.php');

/**
* Clase Master to extend other application methods
*/
class CommentAPIView extends View {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return( true );
  }



  // URL: /api/comment/list/ (Adrian)
  public function commentList( $urlParams ) {

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
        'order' => array( 'timeCreation' => -1 ),
        'affectsDependences' => array('UserModel')
      ) );



      header('Content-Type: application/json; charset=utf-8');
      echo '[';
      $c = '';
      global $C_LANG;

      while( $valueobject = $commentsList->fetch() ) {
        $allData = $valueobject->getAllData('onlydata');
        $user = $valueobject->getterDependence('user');

        if( $user ) {

          if(!empty($user[0]->getter('avatar'))){
            $filedataControl = new FiledataModel();
            $avatar = $filedataControl->listItems( array('filters' => ['id' => $user[0]->getter('avatar')]))->fetch();
            $allData['userAvatarName'] = $avatar->getter('name');
            $allData['userAvatarId'] = $avatar->getter('id');
            $allData['userAvatarAKey'] = $avatar->getter('aKey');
          }

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
      header('Content-Type: application/json; charset=utf-8');
      echo '[]';
    }
  }


  // URL: /api/comments
  public function comments( $urlParams = false ) {
    // error_log( 'comments( '.json_encode($urlParams).' )' );
    // error_log( 'comments POST: '.json_encode($_POST) );

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'GET':
        $validation = array( 'comments' => '#^\d+(,\d+)*$#', 'resources' => '#^\d+(,\d+)*$#', 'options' => '#^(permissions|votes)$#' );
        $urlParamsList = RequestController::processUrlParams( $urlParams, $validation );
        $commentsId = isset( $urlParamsList['comments'] ) ? $urlParamsList['comments'] : false;
        $resourcesId = isset( $urlParamsList['resources'] ) ? $urlParamsList['resources'] : false;
        $options = isset( $urlParamsList['options'] ) ? $urlParamsList['options'] : false;

        if( $options ) {
          $this->sendJsonCommentsOptions( $resourcesId, $options );
        }
        else {
          $this->sendJsonCommentsInfo( $commentsId, $resourcesId );
        }
      break;
      //case 'PUT':
      //break;
      case 'POST':
        $this->postComment( $_POST );
      break;
      default:
        header("HTTP/1.0 400 Bad Request");
      break;
    }
  }



  private function postComment( $params ) {
    $result = array(
      'header' => 'HTTP/1.0 400 Bad Request',
      'json' => false
    );
    $valid = true; // Estado de revision

    $valuesArray = array(
      'timeCreation' => date( 'Y-m-d H:i:s', time() )
    );

    // error_log( json_encode($params) );

    $resId = isset( $params['resource'] ) ? intval( $params['resource'] ) : false;

    if( !$resId || $resId < 1 ) {
      $valid = false;
      $result = array(
        'header' => 'HTTP/1.0 400 Bad Request',
        'json' => '{ "error": "Invalid resource Id" }'
      );
    }

    $commentCtrl = new RExtCommentController();
    $permissions = false;

    if( $valid ) { // Tipo y permisos
      // $permission = $commentCtrl->getCommentPermissions( $resId );
      $permissionsInfo = $commentCtrl->getPermissions( $resId );
      $permissions = isset( $permissionsInfo[ $resId ] ) ? $permissionsInfo[ $resId ] : false;

      $typeIdName = isset( $params['type'] ) ? $params['type'] : false;

      if( isset( $permissions['ctype'] ) && in_array( $typeIdName, $permissions['ctype'] ) ) {
        $commentTypeTerms = $commentCtrl->getCommentTypeTerms();
        foreach( $commentTypeTerms as $termId => $termIdName ) {
          if( $termIdName === $typeIdName ) {
            $valuesArray['type'] = intval( $termId );
            break;
          }
        }
      }

      if( isset( $valuesArray['type'] ) && $typeIdName === 'suggest' ) {
        if( isset( $params['suggestTypeId'] ) ) {
          $sType = intval( $params['suggestTypeId'] );
          $suggestTypeTerms = $commentCtrl->getSuggestTypeTerms();
          if( in_array( $sType, array_keys( $suggestTypeTerms ) ) ) {
            $valuesArray['suggestType'] = $sType;
          }
        }
      }

      if( !isset( $valuesArray['type'] )
        || ( $typeIdName === 'suggest' && !isset( $valuesArray['suggestType'] ) ) ) {
        $valid = false;
        $result = array(
          'header' => 'HTTP/1.0 400 Bad Request',
          'json' => '{ "error": "Invalid type" }'
        );
      }
    }

    if( $valid ) { // Usuario
      // Get user session
      $useraccesscontrol = new UserAccessController();
      $userSess = $useraccesscontrol->getSessiondata();
      $userID = ($userSess) ? $userSess['data']['id'] : null;

      form::load('controller/FormValidators.php');
      $valCtrl = new FormValidators();

      if( !$userID ) {
        if( isset( $permissions['anonymous'] ) && $permissions['anonymous'] === true
          && isset( $params['anonymousName'], $params['anonymousEmail'] )
          && mb_strlen( $params['anonymousName'] ) > 3
          && mb_strlen( $params['anonymousEmail'] ) > 8
          && $valCtrl->val_email( $params['anonymousEmail'] )
          )
        {
          $valuesArray['anonymousName'] = $params['anonymousName'];
          $valuesArray['anonymousEmail'] = $params['anonymousEmail'];
        }
        else {
          $valid = false;
          $result = array(
            'header' => 'HTTP/1.0 400 Bad Request',
            'json' => '{ "error": "Invalid user" }'
          );
        }
      }
    }

    if( $valid ) { // Contenidos
      if( isset( $params['content'] ) && mb_strlen( $params['content'] ) > 5 ) {
        $valuesArray['content'] = $params['content'];
      }

      if( $typeIdName === 'comment' ) {
        if( isset( $params['rate'] ) && intval( $params['rate'] ) > 0 && intval( $params['rate'] ) <= 100 ) {
          $valuesArray['rate'] = intval( $params['rate'] );
        }
      }

      if( !isset( $valuesArray['content'] ) && !isset( $valuesArray['rate'] ) ) {
        $valid = false;
        $result = array(
          'header' => 'HTTP/1.0 400 Bad Request',
          'json' => '{ "error": "Invalid content" }'
        );
      }
    }



    if( $valid ) { // Guardar
      $valuesArray['published'] = ($typeIdName === 'comment') ? $commentCtrl->commentPublish( $resId ) : true;
      $valuesArray['user'] = $userID;
      $valuesArray['resource'] = $params['resource'];

      $comment = new CommentModel( $valuesArray );
      if( $comment->save() ) {
        header( 'HTTP/1.0 201 OK' );
        header( 'Content-type: application/json; charset=utf-8' );
        echo '{ "id": "'.$comment->getter('id').'" }';
      }
      else {
        $valid = false;
        $result = array(
          'header' => 'HTTP/1.0 500 Internal Server Error',
          'json' => '{ "error": "Internal Server Error" }'
        );
      }
    }

    if( !$valid ) { // Enviar error
      header( $result['header'] );
      if( $result['json'] ) {
        header( 'Content-type: application/json; charset=utf-8' );
        echo $result['json'];
        // error_log( 'RESULT JSON: '.$result['json'] );
      }
    }
  }


  private function sendJsonCommentsInfo( $commentsId, $resourcesId ) {

    // error_log( "sendJsonCommentsInfo( $commentsId, $resourcesId )" );

    rextComment::load('model/CommentModel.php');
    $commentModel = new CommentModel();
    $commentCtrl = new RExtCommentController();

    $filters = array( 'published' => 1, 'type' => $commentCtrl->getCommentTypeTermId( 'comment' ) );

    if( $resourcesId ) {
      $inArray = explode( ',', $resourcesId );
      if( count( $inArray ) > 1 ) {
        $filters['resourceIn'] = $inArray;
      }
      else {
        $filters['resource'] = intval( $resourcesId );
      }
    }
    if( $commentsId ) {
      $inArray = explode( ',', $commentsId );
      if( count( $inArray ) > 1 ) {
        $filters['idIn'] = $inArray;
      }
      else {
        $filters['id'] = intval( $commentsId );
      }
    }

    $commentsList = $commentModel->listItems(  array(
      'filters' => $filters,
      'order' => array( 'timeCreation' => -1 ),
      'affectsDependences' => array('UserModel')
    ) );

    header('Content-type: application/json; charset=utf-8');
    echo '[';
    $c = '';
    while( $valueobject = $commentsList->fetch() ) {
      $allData = $valueobject->getAllData('onlydata');
      $user = $valueobject->getterDependence('user');

      if($user){
        if(!empty($user[0]->getter('avatar'))){
          $filedataControl = new FiledataModel();
          $avatar = $filedataControl->listItems( array('filters' => ['id' => $user[0]->getter('avatar')]))->fetch();
          $allData['userAvatarName'] = $avatar->getter('name');
          $allData['userAvatarId'] = $avatar->getter('id');
          $allData['userAvatarAKey'] = $avatar->getter('aKey');
        }

        $allData['userName'] = $user[0]->getter('name');
        $allData['userEmail'] = $user[0]->getter('email');
      }
      echo $c.json_encode($allData);
      $c=',';
    }
    echo ']';
  }


  private function sendJsonCommentsOptions( $resourcesId, $options ) {
    $commentsOptions = null;

    // error_log( "sendJsonCommentsOptions( $resourcesId )" );

    $commentCtrl = new RExtCommentController();

    switch( $options ) {
      case 'permissions':
        $commentsOptions = $commentCtrl->getPermissions( $resourcesId );
        break;
      case 'votes':
        $commentsOptions = $commentCtrl->getVotes( $resourcesId );
        break;
    }

    header('Content-type: application/json; charset=utf-8');
    if( count( $commentsOptions ) > 0 ) {
      echo json_encode( $commentsOptions );
    }
    else {
      echo '{}';
    }
  }


  // Swagger

  public function commentListDescription() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "basePath": "/api",
        "resourcePath": "/commentList.json",
        "apis": [
          {
            "path": "/comment/list/resource/{resourceID}",
            "description": "",
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
            ]
          }
        ]
      }
    <?php
  }

  public function commentsDescription() {
    header('Content-Type: application/json; charset=utf-8');
    ?>
      {
        "resourcePath": "/comments.json",
        "basePath": "/api",
        "apis": [
          {
            "path": "/comments/{comments}/resources/{resources}/options/{options}",
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
                "nickname": "Get comments",
                "parameters": [
                  {
                    "required": false,
                    "dataType": "int",
                    "name": "comments",
                    "paramType": "path",
                    "allowMultiple": true,
                    "defaultValue": "false",
                    "description": "Comment Ids"
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
                    "description": "Get alternative info: false|permissions|votes"
                  }
                ],
                "summary": "Fetches comments"
              }
            ]
          },
          {
            "path": "/comments",
            "description": "",
            "operations": [
              {
                "errorResponses": [
                  {
                    "reason": "Bad Request",
                    "code": 400
                  },
                  {
                    "reason": "Unauthorized",
                    "code": 401
                  },
                  {
                    "reason": "Forbidden",
                    "code": 403
                  },
                  {
                    "reason": "Not found",
                    "code": 404
                  },
                  {
                    "reason": "Internal Server Error",
                    "code": 500
                  },
                  {
                    "reason": "OK - New resource has been created",
                    "code":  201
                  }
                ],
                "httpMethod": "POST",
                "nickname": "Post comment",
                "parameters": [
                  {
                    "required": true,
                    "dataType": "int",
                    "name": "resource",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": false,
                    "description": "resource id"
                  },
                  {
                    "required": true,
                    "dataType": "string",
                    "name": "type",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": "comment",
                    "description": "type: comment/suggest"
                  },
                  {
                    "required": false,
                    "dataType": "string",
                    "name": "content",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": false,
                    "description": "Comment text"
                  },
                  {
                    "required": false,
                    "dataType": "int",
                    "name": "rate",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": false,
                    "description": "rate: 1 to 100 (type=comment)"
                  },
                  {
                    "required": false,
                    "dataType": "int",
                    "name": "suggestTypeId",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": false,
                    "description": "suggestType Id (type=suggest)"
                  },
                  {
                    "required": false,
                    "dataType": "string",
                    "name": "anonymousName",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": false,
                    "description": "anonymousName"
                  },
                  {
                    "required": false,
                    "dataType": "string",
                    "name": "anonymousEmail",
                    "paramType": "form",
                    "allowMultiple": false,
                    "defaultValue": false,
                    "description": "anonymousEmail"
                  }
                ],
                "summary": "Push comments"
              }
            ]
          }
        ]
      }
    <?php
  }
}
