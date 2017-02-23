<?php
geozzy::load('view/RTypeViewCore.php');


class RTypeAppLugarView extends RTypeViewCore implements RTypeViewInterface {

  public function __construct( $defResCtrl = null ) {
    // error_log( 'RTypeAppLugarView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct( $defResCtrl, new rtypeAppLugar() );
  }

} // class RTypeAppLugarView
