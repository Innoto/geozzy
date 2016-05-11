<?php

Cogumelo::load( 'coreView/View.php' );
rextFavourite::load( 'controller/RExtFavouriteController.php' );

class RExtFavouriteView extends View {

  public function __construct( $base_dir ) {
    parent::__construct( $base_dir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return true;
  }


  public function execCommand() {
    if( isset( $_POST['execute'] ) ) {
      switch( $_POST['execute'] ) {
        case 'keepAlive':
          $this->keepAlive();
          break;
        case 'removeGroupElement':
          $this->removeGroupElement();
          break;
        case 'getGroupElement':
          $this->getGroupElement();
          break;
        default:
          error_log( 'ERROR - RExtFavouriteView::execCommand - Comando no soportado: '.$_POST['execute'] );
          break;
      }
    }
    else {
      error_log( 'ERROR - RExtFavouriteView::execCommand - Datos erroneos' );
    }
  }


}

