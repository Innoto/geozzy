<?php
geozzy::load('model/UrlAliasModel.php');
cogumelo::load('coreController/Cache.php');



class UrlAliasController {

  public function __construct() {
  }


  public function getAlternative( $urlFrom ) {
    cogumelo::debug( 'UrlAliasController::getAlternative urlFrom='. $urlFrom );

    $alternative = false;
    $urlParams = '';

    global $C_LANG; // Idioma actual, cogido de la url
    $actLang = $C_LANG;
    $defLang = Cogumelo::getSetupValue( 'lang:default' );

    $cache = Cogumelo::GetSetupValue('cache:UrlAliasController');

    if( $cache ) {
      $cacheCtrl = new Cache();
      $cacheKey = Cogumelo::getSetupValue('db:name').':'.__METHOD__.':'.$actLang.':'.$urlFrom;
      $alternative = $cacheCtrl->getCache( $cacheKey );
    }

    if( empty( $alternative ) ) {
      // cogumelo::debug('(Notice) UrlAliasController: SIN cache ('.$cacheKey.')');

      $urlFromParts = explode( '?', $urlFrom, 2 );
      if( isset( $urlFromParts['1'] ) ) {
        $urlFrom = $urlFromParts['0'];
        $urlParams = '?'.$urlFromParts['1'];
        // cogumelo::debug( 'UrlAliasController::getAlternative From tocado: '. $urlFrom );
      }

      $aliasData = false;
      $urlAliasModel = new UrlAliasModel();

      $urlAliasList = $urlAliasModel->listItems( [ 'filters' => [ 'urlFrom' => '/'.$urlFrom, 'lang' => $actLang ] ] );
      if( gettype( $urlAliasList ) === 'object' && $urlAlias = $urlAliasList->fetch() ) {
        $aliasData = $urlAlias->getAllData( 'onlydata' );
      }
      else {
        $urlAliasList = $urlAliasModel->listItems( [ 'filters' => [ 'urlFrom' => '/'.$urlFrom ] ] );
        if( gettype( $urlAliasList ) === 'object' && $urlAlias = $urlAliasList->fetch() ) {
          $aliasData = $urlAlias->getAllData( 'onlydata' );
          cogumelo::debug( '(Notice) UrlAliasController: aliasData sin idioma: ' . print_r( $aliasData, true ) );
        }
      }

      if( !empty( $aliasData ) ) {
        // cogumelo::debug( '(Notice) UrlAliasController: aliasData: ' . print_r( $aliasData, true ) );

        $langUrl = '';
        if( isset( $aliasData['lang'] ) && $aliasData['lang'] !== '' && $aliasData['lang'] !== $defLang ) {
          // El idioma por defecto no se añade
          $langUrl = '/' . $aliasData['lang'];
        }
        cogumelo::debug( '(Notice) UrlAliasController: langUrl: '.$aliasData['lang'].' - '. $langUrl );


        if( !empty( $aliasData['resource'] ) ) {
          $baseUrl = '/' . Cogumelo::getSetupValue('mod:geozzy:resource:directUrl') . '/' . $aliasData['resource'];
        }
        else {
          $baseUrl = $aliasData[ 'urlTo' ];
        }


        if( empty( $aliasData['http'] ) || $aliasData['http'] <= 200 || $aliasData['http'] >= 600 ) {

          cogumelo::debug( 'UrlAliasController::getAlternative - Es un ALIAS' );
          // Es un alias
          $alternative = array(
            'code' => 'alias',
            'url' => $baseUrl.$urlParams
          );
        }
        else {

          cogumelo::debug( 'UrlAliasController::getAlternative - Es un REDIRECT' );
          // Es un Redirect
          if( !empty( $aliasData['resource'] ) ) {
            $canonicalList = $urlAliasModel->listItems([
              'filters' => [ 'canonical' => 1, 'lang' => $aliasData['lang'], 'resource' => $aliasData['resource'] ]
            ]);

            if( gettype( $canonicalList ) === 'object' && $canonicalObj = $canonicalList->fetch() ) {
              $canonicalData = $canonicalObj->getAllData( 'onlydata' );
              $baseUrl = $langUrl.$canonicalData['urlFrom'];

              cogumelo::debug( 'UrlAliasController::getAlternative - Es un REDIRECT baseUrl:'.$baseUrl );
            }
          }
          else {
            if( strpos( $baseUrl, 'http' ) !== 0 ) {
              $baseUrl = $langUrl.$baseUrl;
            }
          }

          $alternative = array(
            'code' => $aliasData[ 'http' ],
            'url' => $baseUrl.$urlParams
          );
        }
      }

      if( $cache ) {
        $cacheCtrl->setCache( $cacheKey, $alternative, $cache );
      }
    }

    cogumelo::debug( '(Notice) UrlAliasController::getAlternative urlFrom='.$urlFrom.' Alternative='.json_encode($alternative) );

    return $alternative;
  }

}

