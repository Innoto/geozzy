<?php
Cogumelo::load('coreView/View.php');

Cogumelo::autoIncludes();


class printMapDirectionsView extends View {

  public function __construct( $base_dir = false ) {
    parent::__construct($base_dir);
  }

  public function accessCheck() {
    return true;
  }

  public function printMapDirections( $urlParams = false ) {

    $validation = array( 'title' => '#^.+$#', 'origin' => '#^.+$#', 'destination' => '#^.+$#', 'travelMode' => '#^\w+$#' );
    $urlParamsList = RequestController::processUrlParams($urlParams, $validation);

    $mapDirections = [];
    if( isset( $urlParamsList['title'] ) && isset( $urlParamsList['origin'] ) && isset( $urlParamsList['destination'] ) && isset( $urlParamsList['travelMode'] ) ) {
      $mapDirections = [
        'title' => $urlParamsList['title'],
        'origin' => $urlParamsList['origin'],
        'destination' => $urlParamsList['destination'],
        'travelMode' => $urlParamsList['travelMode']
      ];
    }

    $template = new Template();
    $template->setTpl( 'rExtPrintDirectionsBlock.tpl', 'rextMapDirections' );
    $template->addClientScript( 'js/rExtPrintMapDirections.js', 'rextMapDirections' );
    $template->assign( 'mapDirections', $mapDirections );
    $template->exec();
  }

} // class extends printMapDirectionsView
