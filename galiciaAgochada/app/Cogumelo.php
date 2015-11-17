<?php


class Cogumelo extends CogumeloClass
{
  public $dependences = array(
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap"),
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
      "params" => array("select2"),
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
    )
  );
  public $includesCommon = array( );


  public function __construct() {
    parent::__construct();

    /* Probando Recursos */
//    $this->addUrlPatterns( '#^recurso/?$#', 'view:RecursoView::verRecurso' );
//    $this->addUrlPatterns( '#^recurso/(\d+)$#', 'view:RecursoView::verRecurso' );

    /*Explorer (Param = Sagan||Dora||Indiana)*/
    $this->addUrlPatterns( '#^explorerLayout/(.*)$#', 'view:ExplorerView::explorerLayout' );
    $this->addUrlPatterns( '#^explorerLayout$#', 'view:ExplorerView::explorerLayout' );
    /*Explorer Section (Param = Sagan||Dora||Indiana) */
    $this->addUrlPatterns( '#^explorerLayoutSection/(.*)$#', 'view:ExplorerView::explorerLayoutSection' );
    $this->addUrlPatterns( '#^explorerLayoutSection$#', 'view:ExplorerView::explorerLayoutSection' );

    /*Real urls*/
    /*Explorers*/
    $this->addUrlPatterns( '#^paisaxes-espectaculares#', 'view:ExplorerView::paisaxesExplorer' );
    $this->addUrlPatterns( '#^rincons-con-encanto#', 'view:ExplorerView::rinconsExplorer' );
    $this->addUrlPatterns( '#^praias-ensono#', 'view:ExplorerView::praiasExplorer' );
    $this->addUrlPatterns( '#^sabrosos-xantares#', 'view:ExplorerView::xantaresExplorer' );
    $this->addUrlPatterns( '#^aloxamentos-con-encanto#', 'view:ExplorerView::aloxamentosExplorer' );

    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    //$this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
