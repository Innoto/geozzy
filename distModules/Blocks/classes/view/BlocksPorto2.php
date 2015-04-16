<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();
form::autoIncludes();

class BlocksPorto2 extends View
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

  public function getFormBlock() {
    $template = new Template( $this->baseDir );

    $formBasura = new FormController( 'probaBasura', '/form-group-action' );
    $formBasura->setField( 'input2', array( 'id' => 'meu2', 'label' => 'Meu Bloque 2', 'value' => 'valor678' ) );
    $formBasura->setField( 'submit', array( 'type' => 'submit', 'label' => 'Pulsa para enviar', 'value' => 'Manda' ) );
    $formBasura->saveToSession();
    $template->assign( 'formBasura', $formBasura->getHtmlForm() );
    $template->setTpl( 'bloquePorto2.tpl', 'Blocks' );

    return $template;
  }

}

