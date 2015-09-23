<?php
Cogumelo::load('view/MasterView.php');

common::autoIncludes();
geozzy::autoIncludes();

class ExplorerView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  function paisaxesExplorer(){
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->setTpl('paisaxesExplorer.tpl');
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
