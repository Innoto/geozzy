<?php
Cogumelo::load('view/MasterView.php');




class viewAppStoryCastro extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign('isFront', false);

    //$template->addClientScript('js/pageAbout.js');
    //$template->addClientStyles('styles/masterPageAbout.less');

    $template->setTpl('storyCastro.tpl');


    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
