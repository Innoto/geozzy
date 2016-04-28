<?php

Cogumelo::load('view/MasterView.php');
geozzy::load( 'controller/ResourceController.php' );



class AppResourceBridgeView extends MasterView {

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  public function showResourcePage( $urlParams = false ) {
    error_log( "AppResourceBridgeView: showResourcePage()" . print_r( $urlParams, true ) );

    if( isset( $urlParams['1'] ) ) {
      $resId = isset( $urlParams['1'] ) ? $urlParams['1'] : false;
    }

    $resourceCtrl = new ResourceController();
    $resViewBlockInfo = $resourceCtrl->getViewBlockInfo( $resId );

    if( $resViewBlockInfo['data'] ) {

      if( $resViewBlockInfo['template'] ) {
        foreach( $resViewBlockInfo['template'] as $nameBlock => $templateBlock ) {
          $this->template->addToBlock( 'resTemplateBlock', $templateBlock );
        }
      }

      $resData = array('data' => $resViewBlockInfo['data'], 'ext' => $resViewBlockInfo['ext']);
      // Si vienen datos de header o footer, se asignan
      if (isset($resViewBlockInfo['header'])){
        $resData['header'] = $resViewBlockInfo['header'];
      }
      if (isset($resViewBlockInfo['footer'])){
        $resData['footer'] = $resViewBlockInfo['footer'];
      }
      $this->template->assign( 'res', $resData );

      $this->template->assign( 'i18nlocale', I18N_LOCALE );

      $this->template->addClientStyles('styles/masterResource.less');
      //$this->template->addClientScript('js/resource.js');
/*
      if( class_exists( 'geozzyUser' ) ) {
        geozzyUser::autoIncludes();
      }
*/
      $tplFile = 'appResourceBridgePageFull.tpl';
      if( isset( $_REQUEST['pf'] ) && $_REQUEST['pf'] !== '' ) {
        $mark = preg_replace( '/[^0-9a-z_-]/i', '_', $_REQUEST['pf'] );
        $tplTest = 'appResourceBridgePage-'.$mark.'.tpl';
        if( ModuleController::getRealFilePath( 'classes/view/templates/'.$tplTest, 'appResourceBridge' ) ) {
          $tplFile = $tplTest;
        }
      }

      $this->template->setTpl( $tplFile, 'appResourceBridge');

      $this->template->exec();
    }
    else {
      header("HTTP/1.0 404 Not Found");
      $this->template->addClientStyles('styles/masterResource.less');
      $this->template->setTpl( 'appResourceBridgePage404.tpl', 'appResourceBridge');
      $this->template->assign( 'title404', __( 'Página no encontrada' ) );

      $this->template->exec();
    }
  }

  /*
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
  */

} // class RTypeAppEspazoNaturalView
