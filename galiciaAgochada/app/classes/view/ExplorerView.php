<?php
Cogumelo::load('view/MasterView.php');

common::autoIncludes();
geozzy::autoIncludes();

class ExplorerView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  function explorerLayout( $urlParams = false ){

    if( isset($urlParams) && $urlParams[1]){
      $this->template->assign( 'explorerType', 'explorerLayout'.$urlParams[1] );
    }
    else{
      $this->template->assign( 'explorerType', '');
    }
    $this->template->addClientStyles('styles/explorerLayout.less');
    $this->template->setTpl('explorerLayout.tpl');
    $this->template->exec();
  }
}
