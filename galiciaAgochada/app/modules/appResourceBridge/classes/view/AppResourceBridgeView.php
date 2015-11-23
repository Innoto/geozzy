<?php

Cogumelo::load('view/MasterView.php');
geozzy::load( 'view/GeozzyResourceView.php' );



class AppResourceBridgeView extends MasterView {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  public function showResourcePage( $urlParams = false ) {
    error_log( "AppResourceBridgeView: showResourcePage()" . print_r( $urlParams, true ) );

    if( isset( $urlParams['1'] ) ) {
      $resId = isset( $urlParams['1'] ) ? $urlParams['1'] : false;
    }

    $resourceView = new GeozzyResourceView();
    $resViewBlockInfo = $resourceView->getViewBlockInfo( $resId );

    if( $resViewBlockInfo['template'] ) {
      foreach( $resViewBlockInfo['template'] as $nameBlock => $templateBlock ) {
        $this->template->addToBlock( 'resTemplateBlock', $templateBlock );
      }
    }
    $this->template->assign( 'res', array( 'data' => $resViewBlockInfo['data'], 'ext' => $resViewBlockInfo['ext'] ) );

    $this->template->addClientStyles('styles/masterResource.less');
    $this->template->addClientScript('js/resource.js');

    $this->template->setTpl( 'appResourceBridgePageFull.tpl', 'appResourceBridge');
    //$this->template->setTpl( 'appResourceBridgePageBlock.tpl', 'appResourceBridge');

    $this->template->exec();
  }


  public function showResourcePageTmp( $urlParams = false ) {
    error_log( "AppResourceBridgeView: showResourcePage()" . print_r( $urlParams, true ) );

    $resourceView = new GeozzyResourceView();
    $templateBlock = $resourceView->getResourceBlockTmp( $urlParams );

    $this->template->setBlock( 'resourceBlock', $templateBlock );

    $this->template->addClientStyles('styles/masterResource.less');
    $this->template->addClientScript('js/resource.js');

    $this->template->setTpl( 'appResourceBridge.tpl', 'appResourceBridge');

    $this->template->exec();
  }


} // class RTypeAppEspazoNaturalView