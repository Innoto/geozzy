<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeFavourites extends Module {

  public $name = 'rtypeFavourites';
  public $version = 1.0;
  public $rext = array();


  public $dependences = array(
    array(
      "id" =>"underscore",
      "params" => array("underscore@1.8.3"),
      "installer" => "yarn",
      "includes" => array("underscore-min.js")
    ),
    array(
      "id" =>"backbonejs",
      "params" => array("backbone@1.1.2"),
      "installer" => "yarn",
      "includes" => ['backbone.js']
    ),
    array(
      "id" =>"select2",
      "params" => array("select2@4"),
      "installer" => "yarn",
      "includes" => array("dist/js/select2.full.js", "dist/css/select2.css")
    )
  );


  public $includesCommon = array(
    'controller/RTypeFavouritesController.php',
    'view/RTypeFavouritesView.php',
    'js/favouritesView.js'
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


  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleDeploy();
  }

}
