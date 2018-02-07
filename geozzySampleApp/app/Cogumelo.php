<?php


class Cogumelo extends CogumeloClass {

  public static $version = 1.0;

  public $dependences = array(
    array(
      "id" =>"featurejs",
      "params" => array("feature.js"),
      "installer" => "bower",
      "includes" => array('feature.min.js')
    ),
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap#v3.3"),
     "installer" => "bower"
    ),
    array(
     "id" => "font-awesome",
     "params" => array("Font-Awesome#v4.7.0"),
     "installer" => "bower",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"html5shiv",
     "params" => array("html5shiv"),
     "installer" => "bower",
     "includes" => array("dist/html5shiv.min.js")
    ),
    array(
      "id" =>"placeholders",
      "params" => array("placeholders"),
      "installer" => "bower",
      "includes" => array("dist/placeholders.jquery.min.js")
    ),
    array(
      "id" =>"jquery-easing",
      "params" => array("jquery-easing-original"),
      "installer" => "bower",
      "includes" => array("jquery.easing.min.js")
    ),
    array(
      "id" =>"owlcarousel",
      "params" => array("owl.carousel"),
      "installer" => "bower",
      "includes" => array("dist/owl.carousel.min.js", "dist/assets/owl.carousel.min.css")
    ),
    // array(
    //   "id" =>"parallax.js",
    //   "params" => array("parallax.js"),
    //   "installer" => "bower",
    //   "includes" => array("parallax.js")
    // ),
    // array(
    //   "id" =>"unitegallery",
    //   "params" => array("unitegallery#1.7"),
    //   "installer" => "bower",
    //   "includes" => array("dist/js/unitegallery.min.js", "dist/css/unite-gallery.css", "dist/themes/tiles/ug-theme-tiles.js", "dist/themes/default/ug-theme-default.css")
    // ),
    // array(
    //   'id' => 'jquery-validation',
    //   'params' => array( 'jquery-validate#1.14' ),
    //   'installer' => 'bower',
    //   'includes' => array( 'dist/jquery.validate.min.js', 'dist/additional-methods.min.js' )
    // ),
    // array(
    //   'id' => 'masonry',
    //   'params' => array( 'masonry' ),
    //   'installer' => 'bower',
    //   'includes' => array( 'dist/masonry.pkgd.min.js' )
    // ),
    // array(
    //   'id' => 'lazyload',
    //   'params' => array( 'jquery.lazyload' ),
    //   'installer' => 'bower',
    //   'includes' => array( 'jquery.lazyload.js' )
    // ),
    // array(
    //   "id" =>"raleway",
    //   "params" => array("raleway"),
    //   "installer" => "bower",
    //   "includes" => array("raleway.css")
    // ),
    // array(
    //   "id" =>"jquery.bootstrap-responsive-tabs",
    //   "params" => array("jquery.bootstrap-responsive-tabs"),
    //   "installer" => "bower",
    //   "includes" => array("dist/js/jquery.bootstrap-responsive-tabs.min.js", "dist/css/bootstrap-responsive-tabs.css")
    // ),
  );

  public $includesCommon = array();


  public function __construct() {
    parent::__construct();

    // $this->addUrlPatterns( '#^contact/sendToMail#', 'view:PageHome::sendContactMail' );

    /*MasterView*/
    //$this->addUrlPatterns( '#^$#', 'view:MasterPageView::home' );
    //$this->addUrlPatterns( '#^403$#', 'view:MasterPageView::page403' );
    //$this->addUrlPatterns( '#^404$#', 'view:MasterPageView::page404' );
  }

}
