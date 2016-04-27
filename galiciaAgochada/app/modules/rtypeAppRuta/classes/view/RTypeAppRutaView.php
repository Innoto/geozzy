<?php
Cogumelo::load('coreView/View.php');
rtypeAppRuta::load('controller/RTypeAppRutaController.php');



class RTypeAppRutaView extends View
{

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir );

    $this->defResCtrl = $defResCtrl;
    $this->rTypeCtrl = new RTypeAppRutaController( $defResCtrl );
  }





} // class RTypeAppRutaView
