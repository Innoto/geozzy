<?php


class RExtViewAltLandingHotelCollections {

  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtViewAltZZZ::__construct' );
    $this->defRTypeCtrl = $defRTypeCtrl;
    $this->defResCtrl = $defRTypeCtrl->defResCtrl;
  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( Template $resBlock ) {
    error_log( "RExtViewAltZZZ: getViewBlock()" );
    $newBlockTemplate = false; // Para incluir un bloque en el tpl "normal"

    $resId = $this->defResCtrl->resObj->getter('id');

    $resBlock->setTpl( 'rExtViewAltLandingHotelCollections.tpl', 'rextView' ); // Para reemplazar el tpl "normal"

    // $resBlock->addToBlock( 'rextEatAndDrink', $eatBlock );
    $resBlock->assign( 'altViewInfo', 'Un dato metido desde o View Aternativo ;-) ('.$resId.')' );

    return $newBlockTemplate;
  }

} // class RExtViewAltZZZ

