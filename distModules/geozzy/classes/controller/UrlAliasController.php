<?php
geozzy::load('model/UrlAliasModel.php');
cogumelo::load('coreController/Cache.php');



class UrlAliasController {

  public function __construct() {
  }


  public function getAlternative( $urlFrom ) {
    // error_log( 'UrlAliasController::getAlternative urlFrom='. $urlFrom );

    $alternative = false;
    $urlParams = '';

    $cacheKey = Cogumelo::getSetupValue('db:name').':geozzy:UrlAliasController:getAlternative:'.$urlFrom;

    $cacheCtrl = new Cache();
    $alternative = $cacheCtrl->getCache( $cacheKey );

    if( empty( $alternative ) ) {
      // error_log('(Notice) UrlAliasController: SIN cache ('.$cacheKey.')');

      $urlFromParts = explode( '?', $urlFrom, 2 );
      if( isset( $urlFromParts['1'] ) ) {
        $urlFrom = $urlFromParts['0'];
        $urlParams = '?'.$urlFromParts['1'];
        // error_log( 'UrlAliasController::getAlternative From tocado: '. $urlFrom );
      }

      $urlAliasModel = new UrlAliasModel();
      $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'urlFrom' => '/'.$urlFrom ) ) );

      if( gettype( $urlAliasList ) === 'object' && $urlAlias = $urlAliasList->fetch() ) {
        $aliasData = $urlAlias->getAllData( 'onlydata' );
        // error_log( "Alias: " . print_r( $aliasData, true ) );

        if( !empty( $aliasData['resource'] ) ) {
          $baseUrl = '/' . Cogumelo::getSetupValue('mod:geozzy:resource:directUrl') . '/' . $aliasData['resource'];
        }
        else {
          $baseUrl = $aliasData[ 'urlTo' ];
        }



        $langUrl = '';
        if( isset( $aliasData['lang'] ) && $aliasData['lang'] !== '' ) {
          $langUrl = '/' . $aliasData['lang'];
        }

        if( empty( $aliasData['http'] ) || $aliasData['http'] <= 200 || $aliasData['http'] >= 600 ) {
          // Es un alias
          $alternative = array(
            'code' => 'alias',
            'url' => $baseUrl.$urlParams
          );
        }
        else {






          // Es un Redirect
          if( !empty( $aliasData['resource'] ) ) {
            $canonicalList = $urlAliasModel->listItems( array( 'filters' => array(
              'canonical' => 1,
              'lang' => $aliasData['lang'],
              'resource' => $aliasData['resource']
            ) ) );

            if( $canonicalList && $canonicalObj = $canonicalList->fetch() ) {
              $canonicalData = $canonicalObj->getAllData( 'onlydata' );
              $baseUrl = $langUrl.$canonicalData['urlFrom'];
            }
          }

          $alternative = array(
            'code' => $aliasData[ 'http' ],
            'url' => $baseUrl.$urlParams
          );
        }
      }

      $cacheCtrl->setCache( $cacheKey, $alternative, 60 ); // 1 min
    }

    // error_log( '(Notice) UrlAliasController::getAlternative urlFrom='.$urlFrom.' Alternative='.json_encode($alternative) );

    return $alternative;
  }

}

