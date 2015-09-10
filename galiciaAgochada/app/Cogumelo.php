<?php


class Cogumelo extends CogumeloClass
{
  public $dependences = array();
  public $includesCommon = array();


  public function __construct() {
    parent::__construct();

    /* Probando Bloques */
    $this->addUrlPatterns( '#^probandoBloques1$#', 'view:BloquesTestView::exemplo1' );

    /* Probando Recursos */
    $this->addUrlPatterns( '#^recurso/?$#', 'view:RecursoView::verRecurso' );
    $this->addUrlPatterns( '#^recurso/(\d+)$#', 'view:RecursoView::verRecurso' );
    $this->addUrlPatterns( '#^recurso-crear$#', 'view:RecursoView::crearForm' );
    $this->addUrlPatterns( '#^recurso-editar/(\d+)$#', 'view:RecursoView::editarForm' );
    $this->addUrlPatterns( '#^recurso-form-action$#', 'view:RecursoView::actionResourceForm' );


    /* Probando Collections */
    $this->addUrlPatterns( '#^coleccion/?$#', 'view:ColeccionView::mostrar' );
    $this->addUrlPatterns( '#^coleccion/(\d+)$#', 'view:ColeccionView::mostrar' );
    $this->addUrlPatterns( '#^coleccion-crear$#', 'view:ColeccionView::crearForm' );
    $this->addUrlPatterns( '#^coleccion-editar/(\d+)$#', 'view:ColeccionView::editarForm' );
    $this->addUrlPatterns( '#^coleccion-form-action$#', 'view:ColeccionView::actionForm' );


    /* Probando Alias */
    $this->addUrlPatterns( '#^alias/?$#', 'view:AliasView::mostrar' );
    $this->addUrlPatterns( '#^alias-crear$#', 'view:AliasView::crearForm' );
    $this->addUrlPatterns( '#^alias-form-action$#', 'view:AliasView::actionForm' );

    /* Explorador */
    $this->addUrlPatterns( '#^explorer$#', 'view:MasterView::explorerExample' );
    $this->addUrlPatterns( '#^explorerLayout$#', 'view:ExplorerView::explorerLayout' );    

    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    //$this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
