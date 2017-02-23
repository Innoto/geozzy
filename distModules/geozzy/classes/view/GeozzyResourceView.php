<?php


/**
 * A desaparecer. Cambiamos a ResourceView por coherencia de nombre
 */


geozzy::load('view/ResourceView.php');


class GeozzyResourceView extends ResourceView {

  public function __construct(){
    error_log( 'GeozzyResourceView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct();
  }
} // class GeozzyResourceView extends ResourceView
