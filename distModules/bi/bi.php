<?php

Cogumelo::load("coreController/Module.php");


class bi extends Module
{
  public $name = "bi";
  public $version = "1.0";
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
     "id" => "bootstrap",
     "params" => array("bootstrap"),
     "installer" => "bower",
     "includes" => array("dist/js/bootstrap.min.js")
    ),
    array(
     "id" =>"select2",
     "params" => array("select2"),
     "installer" => "bower",
     "includes" => array("dist/js/select2.full.js", "dist/css/select2.css")
    ),
    array(
     "id" =>"mustache",
     "params" => array("mustache"),
     "installer" => "bower",
     "includes" => array('mustache.js')
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
     "includes" => array('highcharts.js')
    ),
    array(
     "id" =>"funplus-highcharts-export-csv",
     "params" => array("funplus-highcharts-export-csv"),
     "installer" => "bower",
     "includes" => array('export-csv.js')
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
     "includes" => array('min/moment-with-locales.min.js')
    ),
    array(
     "id" =>"eonasdan-bootstrap-datetimepicker",
     "params" => array("eonasdan-bootstrap-datetimepicker"),
     "installer" => "bower",
     "includes" => array('build/js/bootstrap-datetimepicker.min.js','build/css/bootstrap-datetimepicker.min.css')
    )

  );

  public $includesCommon = array(
    'view/biView.php',
    'styles/app.css',
    'js/'
  );
  public function __construct() {}
}
