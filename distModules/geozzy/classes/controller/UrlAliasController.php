<?php
geozzy::load( 'model/UrlAliasModel.php' );


class UrlAliasController {

  public function __construct() {
  }


  public function getAlternative( $urlFrom ) {
    //error_log( 'UrlAliasController::getAlternative urlFrom='. $urlFrom );

    $alternative = false;
    $urlParams = '';

    $urlFromParts = explode( '?', $urlFrom, 2 );
    if( isset( $urlFromParts['1'] ) ) {
      $urlFrom = $urlFromParts['0'];
      $urlParams = '?'.$urlFromParts['1'];
      //error_log( 'UrlAliasController::getAlternative From tocado: '. $urlFrom );
    }

    $urlAliasModel = new UrlAliasModel();
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'urlFrom' => '/'.$urlFrom ) ) );

    if( $urlAliasList && $urlAlias = $urlAliasList->fetch() ) {
      $aliasData = $urlAlias->getAllData( 'onlydata' );
      // error_log( "Alias: " . print_r( $aliasData, true ) );

      $baseUrl = '/' . Cogumelo::getSetupValue('geozzy:resource:directUrl') . '/' . $aliasData[ 'resource' ];
      $langUrl = '';

      if( isset( $aliasData[ 'lang' ] ) && $aliasData[ 'lang' ] !== '' ) {
        $langUrl = '/' . $aliasData[ 'lang' ];
      }

      if( !isset( $aliasData[ 'http' ] ) || $aliasData[ 'http' ] <= 200 ) {
        // Es un alias
        $alternative = array(
          'code' => 'alias',
          'url' => $baseUrl.$urlParams
        );
      }
      else {
        // Es un Redirect
        $alternative = array(
          'code' => $aliasData[ 'http' ],
          'url' => $baseUrl.$urlParams
        );
      }
    }

    return $alternative;
  }

}

