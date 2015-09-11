<?php

Cogumelo::load('view/MasterView.php');

// Blocks::autoIncludes();
Blocks::load( 'view/BlocksPorto1.php' );
Blocks::load( 'view/BlocksPorto2.php' );
user::autoIncludes();
//admin::autoIncludes();
admin::load( 'view/AdminViewUser.php' );


class BloquesTestView extends MasterView
{

  public function __construct( $baseDir ) {

    parent::__construct( $baseDir );
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
    $this->template->addToBlock( 'bloque1', $bp1->getBlockTitulo( 'Segunda parte do bloque' ) );


    $bp2 = new BlocksPorto2( null );
    $this->template->setBlock( 'bloque2', $bp2->getFormBlock() );


    $bp3 = new UserView( $this->baseDir );
    $this->template->setBlock( 'bloque3', $bp3->loginFormBlock() );


    $bp4 = new AdminViewUser( $this->baseDir );
    $this->template->setBlock( 'bloque4', $bp4->listUsersBlock() );


    $this->template->setTpl( 'probandoBloques1.tpl' );
    $this->template->exec();
  } // function loadForm()


} // class BloquesTest extends View
