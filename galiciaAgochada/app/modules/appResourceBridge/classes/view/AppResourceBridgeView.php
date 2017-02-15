<?php

Cogumelo::load('view/MasterView.php');
geozzy::load( 'controller/ResourceController.php' );
geozzy::load('view/ResourceView.php');



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
    $resViewBlockInfo = false;

    $resId = isset( $urlParams['1'] ) ? $urlParams['1'] : false;

    if( $resId ) {
      $resourceView = new ResourceView();
      $resViewBlockInfo = $resourceView->getViewBlockInfo( $resId );
    }


    // Miro si el recurso NO esta publicado pero podemos verlo
    $unpublishedPermission = false;
    if( isset( $resViewBlockInfo['unpublished'] ) ) {
      $useraccesscontrol = new UserAccessController();
      // $user = $useraccesscontrol->getSessiondata();
      $unpublishedPermission = $useraccesscontrol->checkPermissions( 'admin:access' );
      // error_log( 'unpublishedPermission: '.json_encode( $unpublishedPermission ) );

      if( $unpublishedPermission ) {
        $resViewBlockInfo = $resViewBlockInfo['unpublished'];
        $resViewBlockInfo['unpublished'] = true;
      }
    }



    if( !isset( $resViewBlockInfo['geozzyRedirect'] ) ) {
      $pageTemplate = $this->getResourcePageTemplate( $resViewBlockInfo );

      if( $pageTemplate ) {
        if( isset( $resViewBlockInfo['unpublished'] ) && $unpublishedPermission ) {
          // $pageTemplate->addClientStyles( 'styles/masterResourceUnpublished.less' );
          if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/js/unpublishedResource.js' ) ) {
            $pageTemplate->addClientScript( 'js/unpublishedResource.js' );
            $pageTemplate->assign( 'unpublished', true );
          }
        }

        $pageTemplate->exec();
      }
      else {
        $this->page404();
      }
    }
    else {
      $httpCodeMsg = array (
        '300' => "HTTP/1.1 300 Multiple Choices",
        '301' => "HTTP/1.1 301 Moved Permanently",
        '302' => "HTTP/1.1 302 Found",
        '303' => "HTTP/1.1 303 See Other",
        '304' => "HTTP/1.1 304 Not Modified",
        '305' => "HTTP/1.1 305 Use Proxy",
        '307' => "HTTP/1.1 307 Temporary Redirect",
      );
      $httpCode = '302';
      if( isset($resViewBlockInfo['geozzyRedirect']['httpCode']) ) {
        $httpCode = $resViewBlockInfo['geozzyRedirect']['httpCode'];
      }

      header( $httpCodeMsg[ $httpCode ] );
      header( 'Location: '.$resViewBlockInfo['geozzyRedirect']['url'] );
    }
  }


  public function getResourcePageTemplate( $resViewBlockInfo ) {
    // error_log( 'AppResourceBridgeView::getResourcePageTemplate()' );
    $pageTemplate = null;

    if( isset( $resViewBlockInfo['data'] ) && $resViewBlockInfo['data'] ) {
      $pageTemplate = new Template();

      if( isset( $resViewBlockInfo['template'] ) && is_array( $resViewBlockInfo['template'] ) ) {
        foreach( $resViewBlockInfo['template'] as $templateBlock ) {
          $pageTemplate->addToFragment( 'resTemplateBlock', $templateBlock );
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
      $pageTemplate->assign( 'res', $resData );

      $pageTemplate->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

      $pageTemplate->addClientStyles( 'styles/masterResource.less' );


      if( isset( $resViewBlockInfo['data']['rTypeIdName'] ) ) {
        $rTypeIdName = str_replace( 'rtype', 'RType', $resViewBlockInfo['data']['rTypeIdName'] );

        // Buscamos si existe un master{RTypeName}.less para el RType de este recurso
        $rTypeLess = 'master'.ucfirst( $rTypeIdName ).'.less';
        if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/styles/'.$rTypeLess ) ) {
          $pageTemplate->addClientStyles( 'styles/'.$rTypeLess );
        }

        // Buscamos si existe un auto{RTypeName}.js para el RType de este recurso
        $rTypeJs = 'auto'.ucfirst( $rTypeIdName ).'.js';
        if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/js/'.$rTypeJs ) ) {
          $pageTemplate->addClientScript( 'js/'.$rTypeJs );
        }
        if( file_exists( Cogumelo::GetSetupValue( 'setup:appBasePath' ).'/classes/view/templates/script/'.$rTypeJs ) ) {
          $pageTemplate->addClientScript( 'script/'.$rTypeJs );
        }
      }

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

      $pageTemplate->setTpl( $tplFile, 'appResourceBridge');
    }

    return $pageTemplate;
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
