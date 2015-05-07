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
    $this->addUrlPatterns( '#^recurso$#', 'view:RecursoView::verRecurso' );
    $this->addUrlPatterns( '#^recurso-ver$#', 'view:RecursoView::verRecurso' );
    $this->addUrlPatterns( '#^recurso-crear$#', 'view:RecursoView::crearForm' );
    $this->addUrlPatterns( '#^recurso-editar$#', 'view:RecursoView::editarForm' );
    //$this->addUrlPatterns( '#^recurso-form-action$#', 'view:RecursoView::actionForm' );


    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    $this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
