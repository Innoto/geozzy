<?php
Cogumelo::load('view/MasterPageView.php');

class RExtViewAltAppHome {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    //error_log( 'RExtViewAltAppHome::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;

    global $C_LANG;
    $this->actLang = $C_LANG;
  }


  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    //error_log( "RExtViewAltAppHome: alterViewBlockInfo( viewBlockInfo, $templateName )" );

    $masterPageView = new MasterPageView(false);
    $masterPageView->home();
    $viewBlockInfo['template']['full'] = $masterPageView->template;

    return $viewBlockInfo;
  }

  // Obtiene la url del recurso en el idioma especificado y sino, en el idioma actual
  public function getUrlAlias($resId, $lang = false){
    $urlAliasModel = new UrlAliasModel();

    if ($lang){
      $langId = $lang;
    }
    else{
      $langId = $this->actLang;
    }
    $urlAlias = false;
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'resource' => $resId, 'lang' => $langId ) ) )->fetch();

    if ($urlAliasList){
      $urlAlias = $langId.$urlAliasList->getter('urlFrom');
    }

    return $urlAlias;
  }

} // class RExtViewAltAppMylandpage