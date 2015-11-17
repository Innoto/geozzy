<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppFestaPopular extends Module {

  public $name = 'rtypeAppFestaPopular';
  public $version = '1.0';
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppFestaPopularController.php',
    'view/RTypeAppFestaPopularView.php'
  );

  public $nameLocations = array(
    'es' => 'Festa Popular',
    'en' => 'Festa Popular',
    'gl' => 'Festa Popular'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');


    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
