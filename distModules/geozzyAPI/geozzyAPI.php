<?php


Cogumelo::load("coreController/Module.php");


class geozzyAPI extends Module {
  public $name = "geozzyAPI";
  public $version = 1.0;


  public $dependences = array(
    array(
     "id" =>"swagger-ui",
     "params" => array("swagger-ui#v2.0.24"),
     "installer" => "bower",
     "includes" => array("")
    )

  );

  function __construct() {
    // API DOC GENERATOR
    $this->addUrlPatterns( '#^api/?$#', 'view:DocAPIView::main' );
    $this->addUrlPatterns( '#^api/doc/index.json$#', 'view:DocAPIView::apidocJson' ); // Main swagger JSON
  }
}
