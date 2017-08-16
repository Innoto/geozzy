<?php
Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/I18nController.php');
geozzy::load( 'controller/ResourceController.php' );
geozzy::load('view/ResourceView.php');

Cogumelo::autoIncludes();
common::autoIncludes();
user::autoIncludes();
geozzy::autoIncludes();


class AppResourceBridgeView extends View {

  public function __construct() {
    // error_log( 'AppResourceBridgeView: __construct(): '. debug_backtrace( DEBUG_BACKTRACE_PROVIDE_OBJECT, 1 )[0]['file'] );
    parent::__construct();
  }


  /**
   * Evaluate the access conditions and report if can continue
   * @return bool : true -> Access allowed
   */
  public function accessCheck() {

    $accessValid = false;

    $accessUser = Cogumelo::getSetupValue( 'globalAccessControl:user' );

    if( $accessUser && $accessUser !== '' ) {
      $accessPass = Cogumelo::getSetupValue( 'globalAccessControl:password' );

      $validIp = array(
        '213.60.18.106', // Innoto
        '127.0.0.1'
      );

      $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
      if( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 ) {
        $accessValid = true;
      }
      else {
        if(
          ( !isset( $_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER'] !== $accessUser ) &&
          ( !isset( $_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW'] !== $accessPass ) )
        {
          error_log( 'BLOQUEO --- Acceso Denegado!!!' );
          header('WWW-Authenticate: Basic realm="Zona con control de acceso"');
          header('HTTP/1.0 401 Unauthorized');
          echo 'Acceso Denegado.';
          // exit;
        }
        else {
          $accessValid = true;
        }
      }
    }
    else {
      $accessValid = true;
    }

    return $accessValid;
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



    if( isset( $resViewBlockInfo['geozzyRedirect'] ) ) {
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
    elseif( !empty( $resViewBlockInfo['geozzyError']['httpCode'] ) ) {
      if( $resViewBlockInfo['geozzyError']['httpCode'] !== 403 ) {
        RequestController::httpError404();
      }
      else {
        RequestController::httpError403();
      }
    }
    else {
      $pageTemplate = $this->getResourcePageTemplate( $resViewBlockInfo );

      if( $pageTemplate ) {
        if( isset( $resViewBlockInfo['unpublished'] ) && $unpublishedPermission ) {
          if( file_exists( Cogumelo::getSetupValue( 'setup:appBasePath' ).'/classes/view/templates/js/unpublishedResource.js' ) ) {
            $pageTemplate->addClientScript( 'js/unpublishedResource.js' );
            $pageTemplate->assign( 'unpublished', true );
          }
        }

        if( !Cogumelo::getSetupValue('mod:mediaserver:minimifyPage') ) {
          $pageTemplate->exec();
        }
        else {
          $pageTemplate->execMinimify();
        }
      }
      else {
        RequestController::httpError404();
      }
    }
  }


  public function getResourcePageTemplate( $resViewBlockInfo ) {
    // error_log( 'AppResourceBridgeView::getResourcePageTemplate()' );
    $pageTemplate = null;

    if( isset( $resViewBlockInfo['data'] ) && $resViewBlockInfo['data'] ) {
      $pageTemplate = new Template();

      if( isset( $resViewBlockInfo['template']['full'] ) ) {
        $pageTemplate->addToFragment( 'resTemplateBlock', $resViewBlockInfo['template']['full'] );
      }

      $resData = array( 'data' => $resViewBlockInfo['data'], 'ext' => $resViewBlockInfo['ext'] );

      if( !empty( $resViewBlockInfo['labels'] ) ) {
        $resData['labels'] = $resViewBlockInfo['labels'];
      }

      // Si vienen datos de header o footer, se asignan
      if( isset($resViewBlockInfo['header']) ) {
        $resData['header'] = $resViewBlockInfo['header'];
      }
      if( isset($resViewBlockInfo['footer']) ) {
        $resData['footer'] = $resViewBlockInfo['footer'];
      }
      $pageTemplate->assign( 'res', $resData );

      $pageTemplate->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

      $pageTemplate->addClientStyles( 'styles/primary.less' );


      if( isset( $resViewBlockInfo['data']['rTypeIdName'] ) ) {
        $rTypeIdName = str_replace( 'rtype', 'RType', $resViewBlockInfo['data']['rTypeIdName'] );

        // Buscamos si existe un master{RTypeName}.less para el RType de este recurso
        $rTypeLess = 'master'.ucfirst( $rTypeIdName ).'.less';
        if( file_exists( Cogumelo::getSetupValue( 'setup:appBasePath' ).'/classes/view/templates/styles/'.$rTypeLess ) ) {
          $pageTemplate->addClientStyles( 'styles/'.$rTypeLess );
        }

        // Buscamos si existe un auto{RTypeName}.js para el RType de este recurso
        $rTypeJs = 'auto'.ucfirst( $rTypeIdName ).'.js';
        if( file_exists( Cogumelo::getSetupValue( 'setup:appBasePath' ).'/classes/view/templates/js/'.$rTypeJs ) ) {
          $pageTemplate->addClientScript( 'js/'.$rTypeJs );
        }
        if( file_exists( Cogumelo::getSetupValue( 'setup:appBasePath' ).'/classes/view/templates/script/'.$rTypeJs ) ) {
          $pageTemplate->addClientScript( 'script/'.$rTypeJs );
        }
      }

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
    error_log( __CLASS__.'->'.__METHOD__.': '. $_SERVER['REQUEST_URI'] );

    header( 'HTTP/1.0 404 Not Found' );

    $this->template->assign( 'title', __( 'Página no encontrada' ) );

    $this->template->assign( 'res', false );
    $this->template->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

    $this->template->addClientStyles( 'styles/primary.less' );

    $this->template->setTpl( 'appResourceBridgePage404.tpl', 'appResourceBridge' );

    $this->template->exec();
  }


  public function page403() {
    error_log( __CLASS__.'->'.__METHOD__.': '. $_SERVER['REQUEST_URI'] );

    header( 'HTTP/1.0 403 Access Forbidden' );

    $this->template->assign( 'title', __( 'Acceso no permitido' ) );

    $this->template->assign( 'res', false );
    $this->template->assign( 'i18nlocale', Cogumelo::getSetupValue( 'i18n:localePath' ) );

    $this->template->addClientStyles( 'styles/primary.less' );

    $this->template->setTpl( 'appResourceBridgePage403.tpl', 'appResourceBridge' );

    $this->template->exec();
  }
}
