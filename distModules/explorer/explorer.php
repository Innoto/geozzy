<?php


Cogumelo::load("coreController/Module.php");


class explorer extends Module
{
  public $name = "explorer";
  public $version = "1.0";



  public $dependences = array(

  );

  function __construct() {
    $this->addUrlPatterns( '#^api/explorer/(.*)#', 'view:ExplorerAPIView::explorer' );
    $this->addUrlPatterns( '#^api/explorer.json#', 'view:ExplorerAPIView::docJson' ); // Main swagger JSON
  }

}
