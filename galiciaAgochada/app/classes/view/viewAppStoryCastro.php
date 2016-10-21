<?php
Cogumelo::load('view/MasterView.php');




class viewAppStoryCastro extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }


  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    $templateName = ($templateName) ? $templateName : 'full';
    $viewBlockInfo['footer'] = false; // sin footer
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign('isFront', false);

    // Story includes

    biMetrics::autoIncludes();
    explorer::autoIncludes();
    story::autoIncludes();
/*
    $template->addClientScript('CanvasLayer/src/CanvasLayer.js', 'vendor/manual');
    $template->addClientScript('mathjs/dist/math.min.js', 'vendor/bower');
*/
    // Para plugin Timeline
    /*
    $template->addClientScript('chap-links-library/', 'vendor/bower');
    $template->addClientScript('chap-links-library/', 'vendor/bower');
    $template->addClientStyles('chap-links-library/', 'vendor/bower');
    */
    $template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');

    $template->addClientScript('js/galiciaAgochadaExplorersUtils.js');
    $template->addClientScript('js/castroStory.js');
    $template->addClientStyles('styles/masterCastroStory.less');

    $template->setTpl('castroStory.tpl');


    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
