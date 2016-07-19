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

    return true;
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