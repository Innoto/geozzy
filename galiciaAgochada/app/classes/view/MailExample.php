<?php

Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/MailController.php');


/**
* Clase Master to extend other application methods
*/
class MailExample extends View {

  public function __construct( $baseDir = false ) {
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return true;
  }

  public function mail() {

    // __construct( $vars = false, $template = false, $module = false )
    // $mailControl = new MailController( ['var1'=>'Contido var 1', 'var2'=>'Contido var 2'], 'mailExample.tpl' );

    $mailControl = new MailController();

    $tpl = new Template();
    $tpl->setTpl( 'mailExample.tpl' );

    $mailControl->setBody( 'Texto sin HTML', $tpl, array( 'var1'=>'Contido var 1', 'var2'=>'2' ) );
    $mailControl->send( 'jmporto@innoto.es', 'Ola meu - Compacto' );


    /*

    Ejemplos variados

    $to = 'jmporto@innoto.es';
    //$to = 'guiseris@hotmail.com';


    // Ej.1: Sin HTML y sin tpls.
    $mailControl->clear();
    $mailControl->setBody( 'Texto sin HTML' );
    $mailControl->send( $to, 'Ola meu - Ej.1: Sin HTML y sin tpl.' );


    // Ej.2: Con texto y HTML, indicando From.
    $mailControl->clear();
    $mailControl->setBody( 'Texto sin HTML', '<p>Correo en formato <strong>HTML</strong></p>' );
    $mailControl->send( $to, 'Ola meu - Ej.2: Con texto y HTML, indicando From', 'Porto Test', 'test@olameu.com' );


    // Ej.3: Con texto y HTML en tpl con multiples destinos
    $tpl1 = new Template();
    $tpl1->setTpl( 'mailExample.tpl' );
    $tpl1->assign( 'var1', 'Contido var 1' );
    $tpl1->assign( 'var2', 'Contido da var 2 en tpl <strong>HTML</strong>' );
    $mailControl->clear();
    $mailControl->setBodyPlain( 'Texto sin HTML' );
    $mailControl->setBodyHtml( $tpl1 );
    $mailControl->send( array( $to, 'meu@olameu.com' ), 'Ola meu - Ej.3: Con texto y HTML con tpl' );


    // Ej.4: Compacto
    $tpl2 = new Template();
    $tpl2->setTpl( 'mailExample.tpl' );
    $mailControl->clear();
    $mailControl->setBody( 'Texto sin HTML', $tpl2, array( 'var1'=>'Contido var 1', 'var2'=>'2' ) );
    $mailControl->send( $to, 'Ola meu - Ej.4: Compacto', 'Porto Test', 'test@olameu.com' );


    // Ej.5: HTML sin verisón texto. No recomendable!!!
    $mailControl->clear();
    $mailControl->setBodyHtml( '<p>Correo en formato <strong>HTML</strong></p>' );
    $mailControl->send( $to, 'Ola meu - Ej.5: HTML sin verisón texto. No recomendable' );


    // Ej.F1: Correo con 1 fichero
    $filePath = ModuleController::getRealFilePath( 'classes/view/templates/img/aloxamentos.png' ); // Fich. en APP
    $mailControl->clear();
    $mailControl->setBody( 'Mira 1 fichero:' );
    $mailControl->setFiles( $filePath );
    $mailControl->send( $to, 'Ola meu - Ej.F1: Correo con 1 fichero' );


    // Ej.F2: Correo con varios ficheros
    $files = array(
      ModuleController::getRealFilePath( 'classes/view/templates/img/aloxamentos.png' ), // Fich. en APP
      ModuleController::getRealFilePath( 'classes/view/templates/img/cabecera.png' ) // Fich. en APP
    );
    $mailControl->clear();
    $mailControl->setBody( 'Mira varios ficheros:',  '<p>Mira varios <strong>ficheros</strong>:</p>' );
    $mailControl->setFiles( $files );
    $mailControl->send( $to, 'Ola meu - Ej.F2: Correo con varios ficheros' );

    */
  }
}
