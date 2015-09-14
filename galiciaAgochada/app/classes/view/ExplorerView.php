<?php
Cogumelo::load('view/MasterView.php');

common::autoIncludes();
geozzy::autoIncludes();

class ExplorerView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  function explorerLayout(){
    $this->template->addClientStyles('styles/explorerLayout.less');
    $this->template->setTpl('explorerLayout.tpl');
    $this->template->exec();
  }
}
