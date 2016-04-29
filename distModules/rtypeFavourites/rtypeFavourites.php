<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeFavourites extends Module {

  public $name = 'rtypeFavourites';
  public $version = 1.0;
  public $rext = array();

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeFavouritesController.php',
    'view/RTypeFavouritesView.php'
  );

  public $nameLocations = array(
    'es' => 'Favoritos',
    'en' => 'Favourites',
    'gl' => 'Favoritos'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
