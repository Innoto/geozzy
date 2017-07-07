<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
geozzy::autoIncludes();
Cogumelo::autoIncludes();

class PageLegal {

  public function __construct() {
  }

  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign('isFront', false);
    $template->addClientStyles('styles/masterPageLegal.less');
    $template->setTpl('pageLegal.tpl');

    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
