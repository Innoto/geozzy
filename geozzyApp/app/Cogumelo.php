<?php


class Cogumelo extends CogumeloClass
{

  public $dependences = array(

  );
  public $includesCommon = array();


  function __construct() {
    parent::__construct();


    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    $this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
