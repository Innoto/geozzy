<?php

Cogumelo::load("coreController/Module.php");


class adminBI extends Module {

  public $name = "bi";
  public $version = 1.0;
  public $dependences = array(
    array(
      "id" =>"requirejs",
      "params" => array("requirejs@2.1.15"),
      "installer" => "yarn",
      "includes" => array()
    ),
    array(
      "id" =>"requirejs-text",
      "params" => array("requirejs-text@2.0.12"),
      "installer" => "bower",
      "includes" => array()
    ),
    array(
      "id" =>"underscore",
      "params" => array("underscore@1.8.3"),
      "installer" => "yarn",
      "includes" => array("underscore-min.js")
    ),
    array(
      "id" =>"backbonejs",
      "params" => array("backbone@1.1.2"),
      "installer" => "yarn",
      "includes" => ['backbone.js']
    ),
    array(
      "id" =>"mustache",
      "params" => array("mustache"),
      "installer" => "yarn",
      "includes" => array()
    ),
    array(
      "id" =>"highstock",
      "params" => array("highstock-release@2.1.9"),
      "installer" => "yarn",
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
      "params" => array("leaflet@1.0.1"),
      "installer" => "yarn",
      "includes" => array('dist/leaflet.js','dist/leaflet.css')
    ),
    array(
      "id" =>"heatmap.js-amd",
      "params" => array("heatmap.js-amd#v2.0"),
      "installer" => "bower",
      "includes" => array(
        'build/heatmap.js',
        'plugins/leaflet-heatmap/leaflet-heatmap.js'
      )
    ),
    array(
      "id" =>"leaflet-areaselect",
      "params" => array("leaflet-area-select"),
      "installer" => "yarn",
      "includes" => array("src/leaflet-areaselect.css","src/leaflet-areaselect.js")
    ),
    array(
      "id" =>"leaflet.heat",
      "params" => array("leaflet.heat"),
      "installer" => "yarn",
      "includes" => array("dist/leaflet-heat.js")
    ),
    array(
      'id' =>'moment',
      'params' => array( 'moment' ),
      'installer' => 'yarn',
      'includes' => array( 'min/moment-with-locales.min.js' )
    ),
    array(
      "id" =>"eonasdan-bootstrap-datetimepicker",
      "params" => array("eonasdan-bootstrap-datetimepicker@4.17.44"),
      "installer" => "yarn",
      "includes" => array("build/css/bootstrap-datetimepicker.min.css", "build/js/bootstrap-datetimepicker.min.js")
    ),
    array(
      "id" =>"q",
      "params" => array("q@1.0.1"),
      "installer" => "yarn",
      "includes" => array()
    ),
    array(
      "id" =>"seiyria-bootstrap-slider",
      "params" => array("bootstrap-slider-basic"),
      "installer" => "yarn",
      "includes" => array("dist/css/bootstrap-slider.css", "dist/bootstrap-slider.min.js")
    )
  );

  public $includesCommon = array(
    'view/biView.php',
    'styles/app.css'
  );

  public function __construct() {
  }
}
