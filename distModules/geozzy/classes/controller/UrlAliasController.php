<?php
geozzy::load( 'model/UrlAliasModel.php' );


class UrlAliasController {

  public function __construct() {
  }


  public function getAlternative( $urlFrom ) {
    // error_log( 'UrlAliasController::getAlternative' );

    $alternative = false;

    $urlAliasModel = new UrlAliasModel();
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'urlFrom' => '/'.$urlFrom ) ) );

    if( $urlAliasList && $urlAlias = $urlAliasList->fetch() ) {
      $aliasData = $urlAlias->getAllData( 'onlydata' );
      error_log( "Alias: " . print_r( $aliasData, true ) );

      $baseUrl = '/recurso/' . $aliasData[ 'resource' ];
      $langUrl = '';

      if( isset( $aliasData[ 'lang' ] ) && $aliasData[ 'lang' ] !== '' ) {
        $langUrl = '/' . $aliasData[ 'lang' ];
      }

      if( !isset( $aliasData[ 'http' ] ) || $aliasData[ 'http' ] <= 200 ) {
        // Es un alias
        $alternative = array(
          'code' => 'alias',
          'url' => $baseUrl
        );
      }
      else {
        // Es un Redirect
        $alternative = array(
          'code' => $aliasData[ 'http' ],
          'url' => $baseUrl
        );
      }
    }

    return $alternative;
  }

}

