<?php

Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/MailController.php');


/**
* Clase Master to extend other application methods
*/
class MailExample extends View
{

  public function __construct( $baseDir ) {
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


    $mailControl = new MailController( ['var1'=>'Contido var 1', 'var2'=>'Contido var 2'],  'mailExample.tpl');

    //var_dump($mailControl->mailBody);
    $mailControl->send('pblanco@innoto.es', 'Hello!!', 'Innoto', 'pblanco@innoto.es');

  }
}
