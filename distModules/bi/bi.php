<?php

Cogumelo::load("coreController/Module.php");


class bi extends Module
{
  public $name = "bi";
  public $version = "1.0";
  public $dependences = array(
    array(
     "id" =>"requirejs",
     "params" => array("requirejs#^2.1.15"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"requirejs-text",
     "params" => array("requirejs-text#^2.0.12"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"select2",
     "params" => array("select2"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"mustache",
     "params" => array("mustache"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"material-design-lite",
     "params" => array("material-design-lite"),
     "installer" => "bower",
     "includes" => array('material.min.js','material.min.css')
    ),
    array(
     "id" =>"highcharts-release",
     "params" => array("highcharts-release"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"funplus-highcharts-export-csv",
     "params" => array("funplus-highcharts-export-csv"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"leaflet",
     "params" => array("leaflet"),
     "installer" => "bower",
     "includes" => array('dist/leaflet.js','dist/leaflet.css')
    ),
    array(
     "id" =>"heatmap.js-amd",
     "params" => array("heatmap.js-amd"),
     "installer" => "bower",
     "includes" => array('plugins/leaflet-heatmap.js','build/heatmap.js')
    ),
    /*array(
     "id" =>"select2-bootstrap-css",
     "params" => array("select2-bootstrap-css"),
     "installer" => "bower",
     "includes" => array()
    ),*/
    array(
     "id" =>"moment",
     "params" => array("moment"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"eonasdan-bootstrap-datetimepicker",
     "params" => array("eonasdan-bootstrap-datetimepicker"),
     "installer" => "bower",
     "includes" => array()
    )

  );

  public $includesCommon = array(
    'view/biView.php',
    'styles/app.css'
  );
  public function __construct() {}
}
