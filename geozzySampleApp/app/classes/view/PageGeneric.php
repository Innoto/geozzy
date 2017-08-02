<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();
geozzy::autoIncludes();

class PageGeneric {

  public function __construct() {
  }

  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign('isFront', false);
    //$template->addClientStyles('styles/master.less');
    $template->setTpl('pageGeneric.tpl');

    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
