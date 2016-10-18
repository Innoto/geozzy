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
    $template->addClientScript('CanvasLayer/src/CanvasLayer.js', 'vendor/manual');
    $template->addClientScript('mathjs/dist/math.min.js', 'vendor/bower');
    $template->addClientScript('js/model/StoryStepModel.js', 'rtypeStory');
    $template->addClientScript('js/router/StoryRouter.js', 'rtypeStory');
    $template->addClientScript('js/collection/StoryStepCollection.js', 'rtypeStory');
    $template->addClientScript('js/view/StoryTemplates.js', 'rtypeStory');
    $template->addClientScript('js/view/StoryList.js', 'rtypeStory');
    $template->addClientScript('js/view/StoryBackground.js', 'rtypeStory');
    $template->addClientScript('js/view/plugins/StoryPluginLegendView.js', 'rtypeStory');
    $template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');
    $template->addClientScript('js/view/plugins/StoryPluginPOISView.js', 'rtypeStory');
    $template->addClientScript('js/view/plugins/StoryPluginKMLView.js', 'rtypeStory');
    $template->addClientScript('js/Story.js', 'rtypeStory');


    $template->addClientScript('js/galiciaAgochadaExplorersUtils.js');
    $template->addClientScript('js/castroStory.js');
    $template->addClientStyles('styles/masterCastroStory.less');

    $template->setTpl('castroStory.tpl');


    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
