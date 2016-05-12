<?php

Cogumelo::load( 'coreView/View.php' );
rextFavourite::load( 'controller/RExtFavouriteController.php' );

class RExtFavouriteView extends View {

  var $userId = false;

  public function __construct( $base_dir ) {

    user::load( 'controller/UserAccessController.php' );
    $userCtrl = new UserAccessController();
    $userInfo = $userCtrl->getSessiondata();
    // error_log( 'USER: '.print_r( $userInfo, true ) );
    $this->userId = isset( $userInfo['data']['id'] ) ? $userInfo['data']['id'] : false;

    parent::__construct( $base_dir ); // Esto lanza el accessCheck
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return( $this->userId !== false );
  }


  public function execCommand() {
    // error_log( 'RExtFavouriteView->execCommand: '.$_POST['execute'] );
    $result = array('result' => 'error');

    if( isset( $_POST['execute'] ) ) {
      switch( $_POST['execute'] ) {
        case 'setStatus':
          if( isset( $_POST['resource'], $_POST['status'] ) ) {
            $result = $this->setStatus( $_POST['resource'], $_POST['status'] );
          }
          break;
        case 'getStatus':
          if( isset( $_POST['resource'] ) ) {
            $result = $this->getStatus( $_POST['resource'] );
          }
          break;
        default:
          error_log( 'ERROR - RExtFavouriteView::execCommand - Comando no soportado: '.$_POST['execute'] );
          break;
      }
    }
    else {
      error_log( 'ERROR - RExtFavouriteView::execCommand - Datos erroneos' );
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode( $result );
  }

  public function setStatus( $resource, $status ) {
    $result = array('result' => 'error');
    $status = ( $status ) ? 1 : 0; // Manejamos status como 0-1 y no false-true

    // error_log( 'RExtFavouriteView->setStatus: '.$resource.', '.$status );

    $favCtrl = new RExtFavouriteController();
    if( $favCtrl->setStatus( $resource, $status, $this->userId ) ) {
      $result = array(
        'result' => 'ok',
        'status' => $status
      );
    }

    return $result;
  }

  public function getStatus( $resource ) {
    // error_log( 'RExtFavouriteView->getStatus: '.$resource );
    $result = array('result' => 'error');

    $favCtrl = new RExtFavouriteController();
    $favInfo = $favCtrl->getStatus( $resource, $this->userId );

    if( $favInfo ) {
      $result = array(
        'result' => 'ok',
        'status' => '1',
        'data' => $favInfo
      );
    }
    else {
      $result = array(
        'result' => 'ok',
        'status' => '0'
      );
    }

    return $result;
  }

}

