<?php


Cogumelo::load("coreController/Module.php");


class geozzyUser extends Module
{
  public $name = "geozzyUser";
  public $version = 1.0;


  public $dependences = array(

  );

  function __construct() {



    // API DOC GENERATOR
    //$this->addUrlPatterns( '#^api$#', 'view:DocAPIView::main' );
    //$this->addUrlPatterns( '#^api/apidoc.json#', 'view:DocAPIView::apidocJson' ); // Main swagger JSON
  }

}
