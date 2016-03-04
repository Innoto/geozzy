<?php
Cogumelo::load('view/ExplorerPageView.php');

class RExtViewAltAppXantaresExplorer {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppXantaresExplorer::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;
  }

  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppXantaresExplorer: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    $explorerView = new ExplorerPageView(false);
    $explorerView->xantaresExplorer();
    $viewBlockInfo['footer'] = false;
    $viewBlockInfo['template']['full'] = $explorerView->template;

    return $viewBlockInfo;
  }

} // class RExtViewAltAppXantaresExplorer
