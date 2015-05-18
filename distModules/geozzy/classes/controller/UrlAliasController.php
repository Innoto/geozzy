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
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'urlFrom' => $this->urlFrom ) ) );
    $urlAlias = $urlAliasList->fetch();
    error_log( 'urlAlias: '.$urlAlias );

    if( $urlAlias ) {
      $allData = $urlAlias->getAllData();
      echo "\n<pre>\n" . print_r( $allData, true ) . "\n</pre>\n";
    }





    if( $this->urlFrom === 'rrr' ) {
      $this->urlTo = SITE_URL . 'recurso';
      $this->httpCode = '301';
      $alternative = true;
    }

    return $alternative;
  }

}

