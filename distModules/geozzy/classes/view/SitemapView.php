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

    $urlsInfo = array();

    $multiLang = ( count( Cogumelo::getSetupValue( 'lang:available' ) ) > 1 );
    // $langsConf = Cogumelo::getSetupValue( 'lang:available' );
    // $langDefault = Cogumelo::getSetupValue( 'lang:default' );
    // $langAvailable = is_array( $langsConf ) ? array_keys( $langsConf ) : array( $langDefault );

    $defConf = Cogumelo::getSetupValue( 'mod:geozzy:sitemap:default' );
    $ignoreRTypes = Cogumelo::getSetupValue( 'mod:geozzy:sitemap:ignoreRTypes' );
    $conf = Cogumelo::getSetupValue( 'mod:geozzy:sitemap' );

    $filters = is_array( $ignoreRTypes ) ? array( 'rTypeIdNameNotIn' => $ignoreRTypes ) : array();
    $urlAliasResModel = new UrlAliasResourceViewModel();
    $urlAliasResList = $urlAliasResModel->listItems( array( 'filters' => $filters ) );
    if( $urlAliasResList ) {
      while( $urlAliasRes = $urlAliasResList->fetch() ) {
        $info = $urlAliasRes->getAllData( 'onlydata' );
        $modDate = isset( $info['timeLastUpdate'] ) ? $info['timeLastUpdate'] : $info['timeCreation'];
        $objDate = new DateTime($modDate);
        $modDate = $objDate->format( DateTime::ATOM );
        $params = array(
          'loc' => htmlspecialchars( $info['urlFrom'] ),
          'mod' => $modDate
        );
        if( $multiLang ) {
          $params['loc'] = '/'.$info['lang'].$params['loc'];
        }
        $tConf = isset( $conf[ $info['rTypeIdName'] ] ) ? $conf[ $info['rTypeIdName'] ] : false;
        if( isset( $defConf['change'] ) || isset( $tConf['change'] )  ) {
          $params['changefreq'] = isset( $tConf['change'] ) ? $tConf['change'] : $defConf['change'];
        }
        if( isset( $defConf['priority'] ) || isset( $tConf['priority'] )  ) {
          $params['priority'] = isset( $tConf['priority'] ) ? $tConf['priority'] : $defConf['priority'];
        }

        $urlsInfo[] = $params;
        /*
          <changefreq>always,hourly,daily,weekly,monthly,yearly,never</changefreq>
          <priority>0.0 to 1.0</priority> (default: 0.5)
          URLs con multiidioma:
          <url>
            <loc>http://www.example.com/</loc>
            <xhtml:link rel="alternate" hreflang="en" href="http://www.example.com/en/" />
            <xhtml:link rel="alternate" hreflang="de-ch" href="http://www.example.com/ch/" />
            <xhtml:link rel="alternate" hreflang="de" href="http://www.example.com/de/" />
          </url>
        */
      }
    }



    $this->template->assign( 'urlPrefix', rtrim( Cogumelo::getSetupValue( 'setup:webBaseUrl:urlCurrent' ), '/' ) );
    $this->template->assign( 'urlsInfo', $urlsInfo );

    $this->template->setTpl( 'sitemap.tpl', 'geozzy' );

    header('Content-type: application/xml; charset=utf-8');
    $this->template->exec();
  } // function showSitemap()

}