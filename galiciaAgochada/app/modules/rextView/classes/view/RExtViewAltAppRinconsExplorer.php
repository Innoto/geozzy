<?php
Cogumelo::load('view/ExplorerPageView.php');

class RExtViewAltAppRinconsExplorer {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppRinconsExplorer::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;
  }

  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppRinconsExplorer: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    $explorerView = new ExplorerPageView(false);
    $explorerView->rinconsExplorer();
    $viewBlockInfo['footer'] = false;
    $viewBlockInfo['template']['full'] = $explorerView->template;

    return $viewBlockInfo;
  }

} // class RExtViewAltAppRinconsExplorer
