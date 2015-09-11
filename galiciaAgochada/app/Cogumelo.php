<?php


class Cogumelo extends CogumeloClass
{
  public $dependences = array();
  public $includesCommon = array();


  public function __construct() {
    parent::__construct();

    /* Probando Recursos */
    $this->addUrlPatterns( '#^recurso/?$#', 'view:RecursoView::verRecurso' );
    $this->addUrlPatterns( '#^recurso/(\d+)$#', 'view:RecursoView::verRecurso' );

    /*Explorer*/
    $this->addUrlPatterns( '#^explorerLayout$#', 'view:ExplorerView::explorerLayout' );

    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    //$this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
