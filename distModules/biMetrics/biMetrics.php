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
    )
  );

  public $includesCommon = array(

    'js/controller/biMetricsController.js',
    'js/controller/biMetricsExplorerController.js',
    'js/controller/biMetricsResourceController.js',
    'js/controller/biMetricsInstancesController.js'
  );


  function __construct() {
  }

}
