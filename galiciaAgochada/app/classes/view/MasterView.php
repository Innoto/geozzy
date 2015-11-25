<?php

Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();
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

    $resourceTaxAllModel = new ResourceTaxonomyAllModel( );
    $resourcesList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'RecantosConEstilo',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel')
      )
    );

    while ($resource = $resourcesList->fetch() )
    {
      Cogumelo::console( $resource->getAllData() );
    }

    $this->template->addClientScript('js/portada.js');
    $this->template->addClientStyles('styles/masterPortada.less');
    $this->template->setTpl('portada.tpl');
    $this->template->exec();
  }

}
