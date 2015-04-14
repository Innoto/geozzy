<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class MasterView extends View
{

  /**
   * Inicializacion por defecto
   * @param string $baseDir
   **/
  public function __construct( $baseDir ) {

    parent::__construct( $baseDir );
  }

  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }

  public function getBlock() {
    $formBasura = new FormController( 'probaBasura', '/form-group-action' );
    $formBasura->setField( 'input2', array( 'id' => 'meu2', 'label' => 'Meu Bloque 2', 'value' => 'valor678' ) );
    $formBasura->setField( 'submit', array( 'type' => 'submit', 'label' => 'Pulsa para enviar', 'value' => 'Manda' ) );
    $formBasura->saveToSession();
    $this->template->assign( 'formBasura', $formBasura->getHtmlForm() );
    $this->template->setTpl( 'bloquePorto2.tpl', 'blocks' );

    return $this->template;
  }

  public function getBlockTitulo( $titulo ) {
    $this->template->assign( 'titulo', $titulo );
    $this->template->setTpl( 'bloquePorto1.tpl', 'blocks' );

    return $this->template;
  }

}

