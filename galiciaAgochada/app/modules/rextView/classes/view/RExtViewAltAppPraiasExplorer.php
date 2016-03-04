<?php
Cogumelo::load('view/ExplorerPageView.php');

class RExtViewAltAppPraiasExplorer {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppPraiasExplorer::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;
  }

  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppPraiasExplorer: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    $explorerView = new ExplorerPageView(false);
    $explorerView->praiasExplorer();
    $viewBlockInfo['footer'] = false;
    $viewBlockInfo['template']['full'] = $explorerView->template;

    return $viewBlockInfo;
  }

} // class RExtViewAltAppPraiasExplorer
