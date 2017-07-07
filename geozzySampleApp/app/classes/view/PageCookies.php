<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
Cogumelo::autoIncludes();

class PageCookies {

  public function __construct() {
  }

  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign('isFront', false);
    //$template->addClientStyles('styles/masterPagePrivacidad.less');
    $template->setTpl('pageCookies.tpl');

    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
