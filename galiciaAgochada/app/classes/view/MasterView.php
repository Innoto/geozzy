<?php

Cogumelo::load('coreView/View.php');

common::autoIncludes();
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class MasterView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    return true;
  }


  function page404() {
    echo 'PAGE404: Recurso non atopado';
  }
  function main(){
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientStyles('styles/master.less');
    $this->template->setTpl('portada.tpl');
    $this->template->exec();
  }

}
