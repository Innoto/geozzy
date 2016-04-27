<?php


class RExtRoutesController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtSocialNetworkController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rExtRoutesController(), 'rExtRoutesController' );
  }

} // class RExtSocialNetworkController
