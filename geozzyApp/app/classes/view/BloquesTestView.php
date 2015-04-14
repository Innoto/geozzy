<?php

Cogumelo::load( 'coreView/View.php' );

// Blocks::autoIncludes();
Blocks::load( 'view/BlocksPorto1.php' );

class BloquesTestView extends View
{

  public function __construct( $base_dir ) {

    parent::__construct( $base_dir );
  }


  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }


  /**
    Defino y muestro un formulario
  */
  public function exemplo1() {

    $bp1 = new BlocksPorto1( null );
    $this->template->setBlock( 'bloque1', $bp1->getBlock() );
    $this->template->setBlock( 'bloque2', $bp1->getBlockTitulo( 'Segundo' ) );

    $this->template->setTpl( 'probandoBloques1.tpl' );
    $this->template->exec();
  } // function loadForm()


} // class BloquesTest extends View
