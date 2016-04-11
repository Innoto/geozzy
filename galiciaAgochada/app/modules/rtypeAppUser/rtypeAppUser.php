<?php

Cogumelo::load( 'coreController/Module.php' );

class rtypeAppUser extends Module {

  public $name = 'rtypeAppUser';
  public $version = 1.0;
  public $rext = array( 'rextUserProfile', 'rextContact');

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeAppUserController.php',
    'view/RTypeAppUserView.php'
  );

  public $nameLocations = array(
    'es' => 'Perfil de Usuario',
    'en' => 'User Profile',
    'gl' => 'Perfil de Usuario'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();

  }
}
