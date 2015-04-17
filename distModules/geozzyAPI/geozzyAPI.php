<?php

Cogumelo::load("coreController/Module.php");

define('GEOZZY_API_URL_DIR', 'geozzyapi');

class geozzyAPI extends Module
{
  public $name = "geozzyAPI";
  public $version = "";



  public $dependences = array(
    array(
      "id" => "swaggervel",
      "params" => array("jlapp/swaggervel", "2.0.*@dev"),
      "installer" => "composer",
      "includes" => array("")
    )
  );

  function __construct() {
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/resource$#', 'view::main' );
    $this->addUrlPatterns( '#^'.GEOZZY_API_URL_DIR.'/explorer$#', 'view::main' );
  }

}
