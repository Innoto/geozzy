<?php

Cogumelo::load("coreController/Module.php");

class explorerSliderFilter extends Module {
  public $name = "explorerSliderFilter";
  public $version = 1;

  public $dependences = array(
    array(
     "id" =>"ionrangeslider",
     "params" => array("ion-rangeslider@2"),
     "installer" => "yarn",
     "includes" => array("js/ion.rangeSlider.min.js", "css/ion.rangeSlider.css")
    )
  );

  public $includesCommon = array(
    'js/ExplorerFilterSliderView.js',
  );


  public function __construct() {
  }

}
