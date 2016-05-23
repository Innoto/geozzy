<?php


Cogumelo::load("coreController/Module.php");


class biMetrics extends Module
{
  public $name = "biMetrics";
  public $version = 1.0;



  public $dependences = array(
    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    ),

    array(
    "id" =>"js-cookie",
    "params" => array("js-cookie"),
    "installer" => "bower",
    "includes" => array("src/js.cookie.js")
    ),
  );

  public $includesCommon = array(
    'js/controller/biConfigurationController.js',
    'js/controller/biMetricsController.js',
    'js/controller/biMetricsExplorerController.js',
    'js/controller/biMetricsResourceController.js',
    'js/controller/biRecommenderController.js',
    'js/biMetricsInstances.js'
  );


  function __construct() {
  }

}
