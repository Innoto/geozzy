<?php
Cogumelo::load( 'coreController/Module.php' );


class initResources extends Module {
  public $version = 1.0;
  public $includesCommon = array();


  public function __construct() {
    //$this->addUrlPatterns( '#^initResources$#', 'view:InitResourcesView::generateResources' );
  }


  public function moduleRc() {
    initResources::load('controller/InitResourcesController.php');
    $initResources = new InitResourcesController();
    $initResources->generateResources(  );
  }

  public function moduleDeploy( ) {
    /*echo "\nResources will update as detailed 'conf/initResources/resources.php'  yes/no. [no]: ";
    $handle = fopen ("php://stdin","r");
    $line = fgets($handle);
    if(trim($line) === 'yes'){
      echo "\n\nUpdating...\n";*/
      initResources::load('controller/InitResourcesController.php');
      $initResources = new InitResourcesController();
      $initResources->generateResources( );
    /*}
    fclose($handle);*/

  }
}
