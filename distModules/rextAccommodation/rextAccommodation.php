<?php

Cogumelo::load("coreController/Module.php");

class rextAccommodation extends Module
{
  public $name = "rextAccomodation";
  public $version = "1.0";
  public $dependences = array();
  public $includesCommon = array();
  public $models = array('AccommodationModel');
  public function __construct() {}
}