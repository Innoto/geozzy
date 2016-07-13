<?php


class Cogumelo extends CogumeloClass {

  public static $version = 1.7;

  public $dependences = array(
    array(
      "id" =>"featurejs",
      "params" => array("feature.js"),
      "installer" => "bower",
      "includes" => array('feature.min.js')
    ),
    array(
      "id" =>"devicejs",
      "params" => array("devicejs"),
      "installer" => "bower",
      "includes" => array('lib/device.min.js')
    ),
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap#v3.3"),
     "installer" => "bower",
     "includes" => array("dist/js/bootstrap.min.js")
    ),
    array(
     "id" => "font-awesome",
     "params" => array("Font-Awesome"),
     "installer" => "bower",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"html5shiv",
     "params" => array("html5shiv"),
     "installer" => "bower",
     "includes" => array("dist/html5shiv.js")
    ),
    array(
      "id" =>"respond",
      "params" => array("respond"),
      "installer" => "bower",
      "includes" => array("src/respond.js")
    ),
    array(
      "id" =>"select2",
      "params" => array("select2#4"),
      "installer" => "bower",
      "includes" => array("dist/js/select2.full.js", "dist/css/select2.css")
    ),
    array(
      "id" =>"placeholders",
      "params" => array("placeholders"),
      "installer" => "bower",
      "includes" => array("dist/placeholders.jquery.min.js")
    ),
    array(
      "id" =>"raleway",
      "params" => array("raleway"),
      "installer" => "bower",
      "includes" => array("raleway.css")
    ),
    array(
      "id" =>"unitegallery",
      "params" => array("unitegallery#1.7"),
      "installer" => "bower",
      "includes" => array("dist/js/unitegallery.min.js", "dist/css/unite-gallery.css","dist/themes/tiles/ug-theme-tiles.js", "dist/themes/default/ug-theme-default.css")
    ),
    array(
      "id" =>"owlcarousel",
      "params" => array("owl.carousel"),
      "installer" => "bower",
      "includes" => array("dist/owl.carousel.js", "dist/assets/owl.carousel.css")
    ),
    array(
      "id" =>"zonaMap",
      "params" => array("zonaMap"),
      "installer" => "bower",
      "includes" => array("zonaMap.js","zonaMap.css")
    )
  );

  public $includesCommon = array(
  );


  public function __construct() {
    parent::__construct();

    /*Explorer (Param = Sagan||Dora||Indiana)*/
    $this->addUrlPatterns( '#^explorerLayout/(.*)$#', 'view:ExplorerView::explorerLayout' );
    $this->addUrlPatterns( '#^explorerLayout$#', 'view:ExplorerView::explorerLayout' );
    /*Explorer Section (Param = Sagan||Dora||Indiana) */
    $this->addUrlPatterns( '#^explorerLayoutSection/(.*)$#', 'view:ExplorerView::explorerLayoutSection' );
    $this->addUrlPatterns( '#^explorerLayoutSection$#', 'view:ExplorerView::explorerLayoutSection' );

    $this->addUrlPatterns( '#^exampleComarca#', 'view:MasterView::exampleComarca' );
    $this->addUrlPatterns( '#^mailexample#', 'view:MailExample::mail' );

    // Prueba Form
    $this->addUrlPatterns( '#^webview/?$#', 'view:ExamplesView::registerForm' );
    $this->addUrlPatterns( '#^webview/senduserregister$#', 'view:ExamplesView::sendRegisterForm' );

    /*Real urls*/
    /*Explorers*/
    /*
    $this->addUrlPatterns( '#^paisaxes-espectaculares#', 'view:ExplorerView::paisaxesExplorer' );
    $this->addUrlPatterns( '#^rincons-con-encanto#', 'view:ExplorerView::rinconsExplorer' );
    $this->addUrlPatterns( '#^praias-ensono#', 'view:ExplorerView::praiasExplorer' );
    $this->addUrlPatterns( '#^sabrosos-xantares#', 'view:ExplorerView::xantaresExplorer' );
    $this->addUrlPatterns( '#^aloxamentos-con-encanto#', 'view:ExplorerView::aloxamentosExplorer' );
    $this->addUrlPatterns( '#^segredos#', 'view:ExplorerView::todosSegredosExplorer' );*/

    /*MasterView*/
    //$this->addUrlPatterns( '#^$#', 'view:MasterView::home' );
    $this->addUrlPatterns( '#^403$#', 'view:MasterView::page403' );
    //$this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
