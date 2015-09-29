<?php
Cogumelo::load('coreView/View.php');

bi::autoIncludes();



class biView extends View
{

  public function __construct( ) {
    parent::__construct('');
  }

  public function accessCheck( ) {
    return true;
  }

  public function dashboard() {

    $this->template->setTpl( 'bi.tpl', 'bi' );
    return $this->template;
  }

}
