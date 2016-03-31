<?php
Cogumelo::load( 'coreController/Module.php' );


class rextRoutes extends Module {

  public $name = 'rextRoutes';
  public $version = 1.0;


  public $models = array();

  public $taxonomies = array();

  public $dependences = array(
    array(
      "id" => "phpgeo",
      "params" => array("mjaschen/phpgeo ", "1.3.2"),
      "installer" => "composer",
      "includes" => array("")
    ),
  );

  public $includesCommon = array(
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load( 'controller/RTUtilsController.php' );

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
