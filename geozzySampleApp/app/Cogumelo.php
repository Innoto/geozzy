<?php


class Cogumelo extends CogumeloClass {

  public static $version = 1.0;

  public $dependences = array(
    array(
      "id" =>"featurejs",
      "params" => array("feature.js"),
      "installer" => "yarn",
      "includes" => array('feature.min.js')
    ),
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap@3.3"),
     "installer" => "yarn"
    ),
    array(
     "id" => "font-awesome",
     "params" => array("font-awesome"),
     "installer" => "yarn",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"html5shiv",
     "params" => array("html5shiv"),
     "installer" => "yarn",
     "includes" => array("dist/html5shiv.js")
    ),
    array(
      "id" =>"placeholders",
      "params" => array("jquery-placeholder"),
      "installer" => "yarn",
      "includes" => array("jquery.placeholder.js")
    ),
    array(
      "id" =>"jquery-easing",
      "params" => array("jquery.easing"),
      "installer" => "yarn",
      "includes" => array("jquery.easing.min.js")
    ),
    array(
      "id" =>"parallax.js",
      "params" => array("jquery-parallax.js"),
      "installer" => "yarn",
      "includes" => array("parallax.js")
    ),
    array(
      "id" =>"owlcarousel",
      "params" => array("owl.carousel"),
      "installer" => "yarn",
      "includes" => array("dist/owl.carousel.js", "dist/assets/owl.carousel.css")
    ),
    array(
      'id' => 'masonry',
      'params' => array( 'masonry-layout' ),
      'installer' => 'yarn',
      'includes' => array( 'dist/masonry.pkgd.min.js' )
    ),
    array(
      'id' => 'imagesloaded',
      'params' => array( 'imagesloaded' ),
      'installer' => 'yarn',
      'includes' => array( 'imagesloaded.pkgd.min.js' )
    )



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
