<?php


class Cogumelo extends CogumeloClass
{

  public $dependences = array(

  );
  public $includesCommon = array();


  function __construct() {
    parent::__construct();

    /* Probando Bloques */
    $this->addUrlPatterns( '#^probandoBloques1#', 'view:BloquesTestView::exemplo1' );


    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    $this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
