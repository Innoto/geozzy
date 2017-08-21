<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();
geozzy::autoIncludes();

class PageHome {

  public function __construct() {
  }


  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    // error_log( 'alterViewBlockInfo en PageHome' );
    $resourceCtrl = new ResourceController();

    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign( 'isFront', true );
    $template->addClientScript( 'js/pageHome.js' );
    $template->addClientStyles( 'styles/masterPortada.less' );
    $template->setTpl( 'pageHome.tpl' );

    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }

}
