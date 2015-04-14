<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();

class BlocksPorto1 extends View
{
  //var $baseDir = null;

  /**
   * Inicializacion por defecto
   * @param string $baseDir
   **/
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
    $this->baseDir = $baseDir;
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }

  public function getBlock() {
    $template = new Template( $this->baseDir );

    $template->assign( 'titulo', 'unTitulo' );
    $template->setTpl( 'bloquePorto1.tpl', 'Blocks' );

    return $template;
  }

  public function getBlockTitulo( $titulo ) {
    $template = new Template( $this->baseDir );

    $template->assign( 'titulo', $titulo );
    $template->setTpl( 'bloquePorto1.tpl', 'Blocks' );

    return $template;
  }

}

