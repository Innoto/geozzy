<?php
geozzy::load('view/RTypeViewCore.php');


class RTypeTravelPlannerView extends RTypeViewCore implements RTypeViewInterface {

  public function __construct( $defResCtrl = null ) {
    // error_log( 'RTypeTravelPlannerView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct( $defResCtrl, new rtypeTravelPlanner() );
  }

} // class RTypeTravelPlannerView
