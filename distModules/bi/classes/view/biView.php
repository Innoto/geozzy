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
    $template = new Template( $this->baseDir );
    $template->setTpl( 'bi.tpl', 'bi' );
    return $template;
  }

}
