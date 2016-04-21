<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeBlog extends Module {

  public $name = 'rtypeBlog';
  public $version = 1.0;
  public $rext = array( 'rextSocialNetwork' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeBlogController.php',
    'view/RTypeBlogView.php'
  );

  public $nameLocations = array(
    'es' => 'Blog',
    'en' => 'Blog',
    'gl' => 'Blogue'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
