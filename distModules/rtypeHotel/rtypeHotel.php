<?php

Cogumelo::load("coreController/Module.php");

class rtypeHotel extends Module
{
  public $name = "rtypeHotel";
  public $version = "1.0";
  public $dependences = array();
  public $includesCommon = array();
  public $rext = array('rextAccommodation');
  public function __construct() {}
}