<?php

abstract class ExplorerController {
  abstract function serveMinimal( );
  abstract function servePartial( );

  public $cacheQuery = true; // false, true or time in seconds

  public function __construct( $resId = false ) {
    // error_log( 'ExplorerController::__construct' );

    $cache = Cogumelo::getSetupValue('cache:ExplorerController:default');
    if( $cache !== null ) {
      $this->cacheQuery = $cache;
    }
  }
}
