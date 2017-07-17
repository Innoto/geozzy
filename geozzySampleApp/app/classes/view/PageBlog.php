<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();
geozzy::autoIncludes();

class PageBlog {

  public function __construct() {
  }

  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {

    $resCtrl = new ResourceController();

    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign( 'isFront', false );
    $template->addClientScript( 'js/pageBlog.js' );
    $template->addClientStyles( 'styles/masterPageBlog.less' );
    $template->setTpl( 'pageBlog.tpl' );

    $limit = 7;
    $page = 1;

    $resModel = new ResourceModel();
    $blogRTypeId = $resCtrl->getRTypeIdByIdName('rtypeAppBlog');

    $numElem = $resModel->listCount( array( 'filters' => array( 'rTypeId' => $blogRTypeId, 'published' => 1 ) ) );

    $limitPage = ceil($numElem/$limit);
    if( isset( $_GET['p'] )){
      if( $_GET['p'] <= $limitPage && $_GET['p'] > 0){
        $page = $_GET['p'];
      }
      else{
        $page = $limitPage;
      }
    }

    $resList = $resModel->listItems( array(
      'affectsDependences' =>
        array( 'FiledataModel', 'UrlAliasModel', 'RExtSocialNetworkModel', 'RExtAppBlogModel' ),
      'filters' =>
        array( 'rTypeId' => $blogRTypeId, 'published' => 1, 'UrlAliasModel.http' => 0, 'UrlAliasModel.canonical' => 1 ),
      'order' =>
        array( 'timeCreation' => -1 ),
      'range' => array(($limit*($page-1)), $limit)
    ));

    $blogEntryList = [];
    if( gettype($resList) === 'object' ) {
      while( $resObj = $resList->fetch() ) {
        $blogEntryList[ $resObj->getter('id') ] = $this->loadBlogEntry( $resObj );
      }
    }

    // Cargo todos los TAX terms del recurso
    $resourceTaxAllModel = new ResourceTaxonomyAllModel();
    $taxAllList = $resourceTaxAllModel->listItems( array( 'filters' => array( 'idNameTaxgroup' => 'blogLabel' ),
      'order' => array( 'weightResTaxTerm' => 1, 'weight' => 1) ) );
    if( gettype($taxAllList) === 'object' ) {
      while( $taxTerm = $taxAllList->fetch() ) {
        $taxTermData = $this->getAllTrData( $taxTerm );

        $tId = $taxTermData['id'];
        // error_log( 'Term ID: '.$tId );
        if( isset( $blogEntryList[ $taxTermData['resource'] ] ) ) {
          $blogEntryList[ $taxTermData['resource'] ]['terms'][] = $taxTermData['id'];
        }
        $taxTerms[ $tId ] = $taxTermData;
        $taxTermsCount[ $tId ] = isset( $taxTermsCount[ $tId ] ) ? $taxTermsCount[ $tId ]+1 : 1;
      }
    }
    $template->assign( 'blogEntryList', $blogEntryList );
    if( isset($taxTerms) ){
      $template->assign( 'taxTerms', $taxTerms );
    }
    if( isset($taxTermsCount) ){
      $template->assign( 'taxTermsCount', $taxTermsCount );
    }
    $template->assign( 'limitPage', $limitPage );

    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }



  public function loadBlogEntry( $resObj ) {
    // error_log( 'Cargando datos de '.$resObj->getter('id') );

    $actLang = $GLOBALS['C_LANG'];
    $defLang = Cogumelo::getSetupValue( 'lang:default' );
    $allLang = Cogumelo::getSetupValue( 'lang:available' );

    //$resourceData = $resObj->getAllData( 'onlydata' );
    $resourceData = [];

    // Añadimos los campos en el idioma actual o el idioma principal
    $resourceFields = $resObj->getCols();
    foreach( $resourceFields as $key => $value ) {
      if( !isset( $resourceData[ $key ] ) ) {
        $resourceData[ $key ] = $resObj->getter( $key );
        // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
        if( $resourceData[ $key ] === '' && isset( $resourceData[ $key.'_'.$defLang ] ) ) {
          $resourceData[ $key ] = $resourceData[ $key.'_'.$defLang ];
        }
      }
    }

    // Cargo los datos de urlAlias dentro de los del recurso
    $urlAliasDep = $resObj->getterDependence( 'id', 'UrlAliasModel' );
    if( $urlAliasDep !== false ) {
      foreach( $urlAliasDep as $urlAlias ) {
        $urlLang = $urlAlias->getter('lang');
        if( $urlLang && $urlLang === $actLang ) {
          $resourceData['urlAlias'] = $urlAlias->getter('urlFrom');
          if( count( $allLang ) > 1 ) {
            $resourceData['urlAlias'] = '/'.$urlLang.$resourceData[ 'urlAlias' ];
          }
          break;
        }
      }
    }
    if( !isset($resourceData['urlAlias']) ) {
      $resourceData['urlAlias'] = '/'.$actLang.'/'.
        Cogumelo::getSetupValue('mod:geozzy:resource:directUrl').'/'.
        $resourceData[ 'id' ];
    }

    // Cargo los datos de image dentro de los del recurso
    $fileDep = $resObj->getterDependence( 'image' );
    if( $fileDep !== false ) {
      foreach( $fileDep as $fileModel ) {
        $resourceData[ 'image' ] = $this->getAllTrData( $fileModel );
      }
    }

    // Cargo los datos del campo batiburrillo
    $blogDep = $resObj->getterDependence( 'id', 'RExtAppBlogModel');
    if( $blogDep !== false ) {
      foreach( $blogDep as $blogData ) {
        $resourceData[ 'longDescription' ] = $blogData->getter('longDescription');
      }
    }

    // Cargo los datos de RExtSocialNetworkModel
    $socialDep = $resObj->getterDependence( 'id', 'RExtSocialNetworkModel');
    if( $socialDep !== false ) {
      foreach( $socialDep as $socialModel ) {
        $socialData = $this->getAllTrData( $socialModel );

        $title = $resourceData['title'];
        $urlAlias = $resourceData['urlAlias'];
        $url = Cogumelo::getSetupValue('setup:webBaseUrl:host').$urlAlias;

        $from = array( '#TITLE#', '#URL#' );
        $to   = array( $title, $url );
        $resourceData['RExtSocialNetwork'] = str_replace( $from, $to, $socialData );

        $resourceData['RExtSocialNetwork']['title'] = $title;
        $resourceData['RExtSocialNetwork']['urlAlias'] = $urlAlias;
        $resourceData['RExtSocialNetwork']['url'] = $url;
      }
    }

    return $resourceData;
  }


  public function getAllTrData( $objModel ) {
    $allData = [];

    $defLang = Cogumelo::getSetupValue( 'lang:default' );
    $rawData = $objModel->getAllData( 'onlydata' ); // Cargamos todos los campos "en bruto"

    foreach( $objModel->getCols() as $fieldName => $fieldInfo ) {
      $allData[ $fieldName ] = $objModel->getter( $fieldName );
      // Si en el idioma actual es una cadena vacia, buscamos el contenido en el idioma principal
      if( $allData[ $fieldName ] === '' && isset( $rawData[ $fieldName.'_'.$defLang ] ) ) {
        $allData[ $fieldName ] = $rawData[ $fieldName.'_'.$defLang ];
      }
    }

    return $allData;
  }
}
