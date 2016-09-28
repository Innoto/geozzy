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
    $template->addClientScript('mathjs/dist/math.min.js', 'vendor/bower');
    $template->addClientScript('js/model/StoryStepModel.js', 'rtypeStory');
    $template->addClientScript('js/collection/StoryStepCollection.js', 'rtypeStory');
    $template->addClientScript('js/view/StoryTemplates.js', 'rtypeStory');
    $template->addClientScript('js/view/StoryList.js', 'rtypeStory');
    $template->addClientScript('js/view/StoryBackground.js', 'rtypeStory');    
    $template->addClientScript('js/Story.js', 'rtypeStory');



    $template->addClientScript('js/castroStory.js');
    $template->addClientStyles('styles/masterCastroStory.less');

    $template->setTpl('castroStory.tpl');


    $viewBlockInfo['template'][ $templateName ] = $template;
    return $viewBlockInfo;
  }
}
