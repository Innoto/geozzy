<?php
Cogumelo::load('view/MasterView.php');


class ExplorerView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );

    $this->template->addClientScript('js/TimeDebuger.js', 'common');

  }

  function paisaxesExplorer(){
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientScript('js/model/TaxonomygroupModel.js', 'geozzy');

    $this->template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategoryCollection.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');

    biMetrics::autoIncludes();
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->addClientScript('js/paisaxesExplorer.js');
    $this->template->setTpl('paisaxesExplorer.tpl');
    $this->template->exec();
  }
  function rinconsExplorer(){
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterRinconsExplorer.less');
    $this->template->addClientScript('js/rinconsExplorer.js');
    $this->template->setTpl('rinconsExplorer.tpl');
    $this->template->exec();
  }
  function praiasExplorer(){
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterPraiasExplorer.less');
    $this->template->addClientScript('js/praiasExplorer.js');
    $this->template->setTpl('praiasExplorer.tpl');
    $this->template->exec();
  }
  function xantaresExplorer(){
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientScript('js/model/TaxonomygroupModel.js', 'geozzy');

    $this->template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategoryCollection.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');
    biMetrics::autoIncludes();
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterXantaresExplorer.less');
    $this->template->addClientScript('js/xantaresExplorer.js');
    $this->template->setTpl('xantaresExplorer.tpl');
    $this->template->exec();
  }
  function aloxamentosExplorer(){
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterAloxamentosExplorer.less');
    $this->template->addClientScript('js/aloxamentosExplorer.js');
    $this->template->setTpl('aloxamentosExplorer.tpl');
    $this->template->exec();
  }

/*Examples*/
  function explorerLayout( $urlParams = false ){

    if( isset($urlParams) && $urlParams[1]){
      $this->template->assign( 'explorerType', 'explorerLayout'.$urlParams[1] );
    }
    else{
      $this->template->assign( 'explorerType', '');
    }
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->setTpl('Example_explorerLayout.tpl');
    $this->template->exec();
  }

  function explorerLayoutSection( $urlParams = false ){

    if( isset($urlParams) && $urlParams[1]){
      $this->template->assign( 'explorerType', 'explorerLayout'.$urlParams[1] );
    }
    else{
      $this->template->assign( 'explorerType', '');
    }
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->setTpl('Example_explorerLayoutSection.tpl');
    $this->template->exec();
  }
}
