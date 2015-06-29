<?php

Cogumelo::load("coreController/Module.php");

class rtypeRestaurant extends Module
{
  public $name = "rtypeRestaurant";
  public $version = "1.0";
  public $dependences = array();
  public $includesCommon = array();
  public $rext = array('rextEatanddrink');
  public function __construct() {}
}
