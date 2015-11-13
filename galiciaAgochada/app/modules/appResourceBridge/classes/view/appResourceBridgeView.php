<?php

Cogumelo::load('view/MasterView.php');
geozzy::load( 'view/GeozzyResourceView.php' );

common::autoIncludes();
geozzy::autoIncludes();



class AppResourceBridgeView extends MasterView {

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir );

  }


  public function showResourcePage( $urlParams = false ) {
    error_log( "AppResourceBridgeView: showResourcePage()" . print_r( $urlParams, true ) );

    if( isset( $urlParams['1'] ) ) {
      $resId = isset( $urlParams['1'] ) ? $urlParams['1'] : false;
    }

    $resourceView = new GeozzyResourceView();
    $resViewBlockInfo = $resourceView->getViewBlockInfo( $resId );

    if( $resViewBlockInfo['templateBlock'] ) {
      foreach( $resViewBlockInfo['templateBlock'] as $nameBlock => $templateBlock ) {
        $this->template->addToBlock( 'resTemplateBlock', $templateBlock );
      }
    }
    $this->template->assign( 'resData', $resViewBlockInfo['data'] );
    $this->template->assign( 'resExt', $resViewBlockInfo['rExt'] );

    $this->template->addClientStyles('styles/masterResource.less');
    $this->template->addClientScript('js/resource.js');
    $this->template->setTpl( 'resourceViewPageBlock.tpl', 'geozzy' );
    $this->template->exec();
  }



  public function showResourcePageTmp( $urlParams = false ) {
    error_log( "AppResourceBridgeView: showResourcePage()" . print_r( $urlParams, true ) );

    $resourceView = new GeozzyResourceView();
    $resViewBlockInfo = $resourceView->getResourceBlockTmp( $urlParams );

    $this->template->setBlock( 'resourceBlock', $templateBlock );

    $this->template->addClientStyles('styles/masterResource.less');
    $this->template->addClientScript('js/resource.js');
    $this->template->setTpl( 'resourceViewPage.tpl', '?????' );
    $this->template->exec();
  }



} // class RTypeAppEspazoNaturalView
