<?php

Cogumelo::load('view/MasterView.php');
geozzy::load( 'controller/ResourceController.php' );



class AppResourceBridgeView extends MasterView {

  public function __construct( $baseDir = false ) {

    /*
    TODO: Esto farase no modulo user
    */
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    Cogumelo::setSetupValue( 'user:session', $user['data'] );



    parent::__construct( $baseDir );
  }


  public function showResourcePage( $urlParams = false ) {
    // error_log( "AppResourceBridgeView: showResourcePage()" . print_r( $urlParams, true ) );

    if( isset( $urlParams['1'] ) ) {
      $resId = isset( $urlParams['1'] ) ? $urlParams['1'] : false;
    }

    $resourceCtrl = new ResourceController();
    $resViewBlockInfo = $resourceCtrl->getViewBlockInfo( $resId );

    if( $resViewBlockInfo['data'] ) {

      if( $resViewBlockInfo['template'] ) {
        foreach( $resViewBlockInfo['template'] as $nameBlock => $templateBlock ) {
          $this->template->addToFragment( 'resTemplateBlock', $templateBlock );
        }
      }

      $resData = array( 'data' => $resViewBlockInfo['data'], 'ext' => $resViewBlockInfo['ext'] );
      // Si vienen datos de header o footer, se asignan
      if( isset($resViewBlockInfo['header']) ) {
        $resData['header'] = $resViewBlockInfo['header'];
      }
      if( isset($resViewBlockInfo['footer']) ) {
        $resData['footer'] = $resViewBlockInfo['footer'];
      }
      $this->template->assign( 'res', $resData );

      $this->template->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

      $this->template->addClientStyles( 'styles/masterResource.less' );

      $rTypeIdName = str_replace( 'rtype', 'RType', $resourceCtrl->getRTypeIdName() );

      // Buscamos si existe un master{RTypeName}.less para el RType de este recurso
      $rTypeLess = 'master'.ucfirst( $rTypeIdName ).'.less';
      if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/styles/'.$rTypeLess ) ) {
        $this->template->addClientStyles( 'styles/'.$rTypeLess );
      }

      // Buscamos si existe un auto{RTypeName}.js para el RType de este recurso
      $rTypeJs = 'auto'.ucfirst( $rTypeIdName ).'.js';
      if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/js/'.$rTypeJs ) ) {
        $this->template->addClientScript( 'js/'.$rTypeJs );
      }
      if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/script/'.$rTypeJs ) ) {
        $this->template->addClientScript( 'script/'.$rTypeJs );
      }


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



  public function page404() {
    header( 'HTTP/1.0 404 Not Found' );

    $this->template->assign( 'title', __( 'Página no encontrada' ) );

    $this->template->assign( 'res', false );
    $this->template->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

    $this->template->addClientStyles( 'styles/masterResource.less' );

    $this->template->setTpl( 'appResourceBridgePage404.tpl', 'appResourceBridge' );

    $this->template->exec();
  }


  public function page403() {
    header( 'HTTP/1.0 403 Access Forbidden' );

    $this->template->assign( 'title', __( 'Acceso no permitido' ) );

    $this->template->assign( 'res', false );
    $this->template->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

    $this->template->addClientStyles( 'styles/masterResource.less' );

    $this->template->setTpl( 'appResourceBridgePage403.tpl', 'appResourceBridge' );

    $this->template->exec();
  }
} // class RTypeAppEspazoNaturalView
