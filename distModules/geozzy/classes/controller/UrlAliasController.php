<?php
geozzy::load( 'model/UrlAliasModel.php' );


class UrlAliasController {

  private $urlFrom = false;
  public $urlTo = false;
  public $httpCode = false;


  public function __construct( $urlFrom ) {
    error_log( 'UrlAliasController::__construct: ' . $urlFrom  );

    $this->urlFrom = $urlFrom;
  }


  public function evaluateAlternative() {
    error_log( 'UrlAliasController::evaluateAlternative' );

    $alternative = false;

    $urlAliasModel = new UrlAliasModel();
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'urlFrom' => '/'.$this->urlFrom ) ) );
    $urlAlias = $urlAliasList->fetch();

    if( $urlAlias ) {
      $allData = $urlAlias->getAllData();
      error_log( "Alias: " . print_r( $allData, true ) );

      $baseUrl = '/recurso/' . $allData[ 'data' ][ 'resource' ];
      $langUrl = '';

      if( isset( $allData[ 'data' ][ 'lang' ] ) && $allData[ 'data' ][ 'lang' ] !== '' ) {
        $langUrl = '/' . $allData[ 'data' ][ 'lang' ];
      }

      if( !isset( $allData[ 'data' ][ 'http' ] ) || $allData[ 'data' ][ 'http' ] <= 200 ) {
        // Es un alias
        error_log( "Alias-viewUrl: " . $baseUrl );
        global $_C;
        $_C->viewUrl( $baseUrl );
        /**
        TODO: NO USA LANG PORQUE FALLA viewUrl
        $_C->viewUrl( $langUrl . $baseUrl );
        */
      }
      else {
        // Es un Redirect
        error_log( "Redirect: " . $langUrl . $baseUrl );
        Cogumelo::redirect( $langUrl . $baseUrl );
      }
    }
    else {
      echo "<p>Non hai Alias</p><br>\n";
    }

    return $alternative;
  }

}

