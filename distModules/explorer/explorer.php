<?php


Cogumelo::load("coreController/Module.php");


class explorer extends Module
{
  public $name = "explorer";
  public $version = "1.0";



  public $dependences = array(
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    ),
    array(
     "id" =>"backbone-localstorage",
     "params" => array("backbone-localstorage#0.3.2"),
     "installer" => "bower",
     "includes" => array("backbone-localstorage.min.js")
    )
  );

  function __construct() {
    $this->addUrlPatterns( '#^api/explorer/(.*)#', 'view:ExplorerAPIView::explorer' );
    $this->addUrlPatterns( '#^api/explorer.json#', 'view:ExplorerAPIView::docJson' ); // Main swagger JSON
  }

}
