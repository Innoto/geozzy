<?php
Cogumelo::load('view/ExplorerPageView.php');

class RExtViewAltAppTodosSegredosExplorer {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppTodosSegredosExplorer::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;
  }

  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppTodosSegredosExplorer: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    $explorerView = new ExplorerPageView(false);
    $explorerView->todosSegredosExplorer();
    $viewBlockInfo['footer'] = false;
    $viewBlockInfo['template']['full'] = $explorerView->template;

    return $viewBlockInfo;
  }

} // class RExtViewAltAppTodosSegredosExplorer
