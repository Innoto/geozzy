<?php

Cogumelo::load('coreView/View.php');
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class MainAPIView extends View
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

  function main(){
    geozzy::load('model/ResourceModel.php');

    $recObj = new ResourceModel();
    $recursosList = $recObj->listItems( array( 'affectsDependences' => array('FiledataModel') , 'order' => array( 'id' => -1 ) ) );
    //var_dump( $recursosList->fetch()->getAllData() );
  }


  function resource() {

  }

}


