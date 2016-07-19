<?php
Cogumelo::load('coreView/View.php');
geozzy::load( 'model/UrlAliasResourceViewModel.php' );


class SitemapView extends View {

  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

  }

  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    $accessValid = false;

    $validIp = array(
      '213.60.18.106', // Innoto
      '176.83.204.135', '91.117.124.2', // ITG
      '91.116.191.224', // Zadia
      '127.0.0.1'
    );

    $conectionIP = isset( $_SERVER['HTTP_X_REAL_IP'] ) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
    if( in_array( $conectionIP, $validIp ) || strpos( $conectionIP, '10.77.' ) === 0 ) {
      $accessValid = true;
    }
    else {
      if(
        ( !isset( $_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!= GA_ACCESS_USER ) &&
        ( !isset( $_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW']!= GA_ACCESS_PASSWORD ) )
      {
        error_log( 'BLOQUEO --- Acceso Denegado!!!' );
        header('WWW-Authenticate: Basic realm="Galicia Agochada"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acceso Denegado.';
        // exit;
      }
      else {
        $accessValid = true;
      }
    }

    return $accessValid;
  }


  /**
   *  Defino un formulario con su TPL como Bloque
   */
  public function showSitemap() {
    error_log( "SitemapView: showSitemap()" );

    $urlsInfo = array();

    $langDefault = Cogumelo::getSetupValue( 'lang:default' );
    $langsConf = Cogumelo::getSetupValue( 'lang:available' );
    $langAvailable = is_array( $langsConf ) ? array_keys( $langsConf ) : array( $langDefault );


    $urlAliasResModel = new UrlAliasResourceViewModel();
    $urlAliasResList = $urlAliasResModel->listItems( array( 'filters' => array() ) );
    if( $urlAliasResList ) {
      while( $urlAliasRes = $urlAliasResList->fetch() ) {
        $info = $urlAliasRes->getAllData( 'onlydata' );
        $modDate = (isset($info['timeLastUpdate'])) ? $info['timeLastUpdate'] : $info['timeCreation'];
        $objDate = new DateTime($modDate);
        $modDate = $objDate->format( DateTime::ATOM );
        $urlsInfo[] = array(
          'loc' => htmlspecialchars('/'.$info['lang'].$info['urlFrom']),
          'mod' => $modDate
        );
      }
    }



    $this->template->assign( 'urlPrefix', rtrim( Cogumelo::getSetupValue( 'setup:webBaseUrl:urlCurrent' ), '/' ) );
    $this->template->assign( 'urlsInfo', $urlsInfo );

    $this->template->setTpl( 'sitemap.tpl', 'geozzy' );

    header('Content-type: application/xml; charset=utf-8');
    $this->template->exec();
  } // function showSitemap()

}