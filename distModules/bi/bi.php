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
     "id" =>"highstock",
     "params" => array("highstock-release#~2.1.9"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"funplus-highcharts-export-csv",
     "params" => array("funplus-highcharts-export-csv#~1.0.4"),
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
    array(
     "id" =>"leaflet-areaselect",
     "params" => array("leaflet-areaselect"),
     "installer" => "bower",
     "includes" => array("src/leaflet-areaselect.css","src/leaflet-areaselect.js")
    ),
    array(
     "id" =>"leaflet.heat",
     "params" => array("Leaflet.heat#*"),
     "installer" => "bower",
     "includes" => array("dist/leaflet-heat.js")
    ),
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
    ),
    array(
     "id" =>"q",
     "params" => array("q#1.0.1"),
     "installer" => "bower",
     "includes" => array()
    ),
    array(
     "id" =>"seiyria-bootstrap-slider",
     "params" => array("seiyria-bootstrap-slider"),
     "installer" => "bower",
     "includes" => array("dist/css/bootstrap-slider.css")
    )
  );

  public $includesCommon = array(
    'view/biView.php',
    'styles/app.css'
  );
  public function __construct() {}
}
