<?php
Cogumelo::load('view/MasterView.php');

class ExplorerView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );

    $this->template->addClientScript('js/TimeDebuger.js', 'common');

  }

  function paisaxesExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $url = $this->commonLayout();
    $this->template->assign('url', $url);

    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientScript('js/model/TaxonomygroupModel.js', 'geozzy');

    $this->template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategoryCollection.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');

    biMetrics::autoIncludes();
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->addClientScript('js/paisaxesExplorer.js');
    $this->template->setTpl('paisaxesExplorer.tpl');
    $this->template->exec();
  }
  function rinconsExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $url = $this->commonLayout();
    $this->template->assign('url', $url);

    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterRinconsExplorer.less');
    $this->template->addClientScript('js/rinconsExplorer.js');
    $this->template->setTpl('rinconsExplorer.tpl');
    $this->template->exec();
  }
  function praiasExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $url = $this->commonLayout();
    $this->template->assign('url', $url);

    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterPraiasExplorer.less');
    $this->template->addClientScript('js/praiasExplorer.js');
    $this->template->setTpl('praiasExplorer.tpl');
    $this->template->exec();
  }
  function xantaresExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $url = $this->commonLayout();
    $this->template->assign('url', $url);

    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientScript('js/model/TaxonomygroupModel.js', 'geozzy');

    $this->template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategoryCollection.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');
    biMetrics::autoIncludes();
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterXantaresExplorer.less');
    $this->template->addClientScript('js/xantaresExplorer.js');
    $this->template->setTpl('xantaresExplorer.tpl');
    $this->template->exec();
  }
  function aloxamentosExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $url = $this->commonLayout();
    $this->template->assign('url', $url);

    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterAloxamentosExplorer.less');
    $this->template->addClientScript('js/aloxamentosExplorer.js');
    $this->template->setTpl('aloxamentosExplorer.tpl');
    $this->template->exec();
  }

  function todosSegredosExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $url = $this->commonLayout();
    $this->template->assign('url', $url);

    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientScript('js/model/TaxonomygroupModel.js', 'geozzy');
    $this->template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategoryCollection.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');
    biMetrics::autoIncludes();
    explorer::autoIncludes();
    $this->template->addClientStyles('styles/masterTodosSegredosExplorer.less');
    $this->template->addClientScript('js/todosSegredosExplorer.js');
    $this->template->setTpl('todosSegredosExplorer.tpl');
    $this->template->exec();
  }

/*Examples*/
  function explorerLayout( $urlParams = false ){

    if( isset($urlParams) && $urlParams[1]){
      $this->template->assign( 'explorerType', 'explorerLayout'.$urlParams[1] );
    }
    else{
      $this->template->assign( 'explorerType', '');
    }
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->setTpl('Example_explorerLayout.tpl');
    $this->template->exec();
  }

  function explorerLayoutSection( $urlParams = false ){

    if( isset($urlParams) && $urlParams[1]){
      $this->template->assign( 'explorerType', 'explorerLayout'.$urlParams[1] );
    }
    else{
      $this->template->assign( 'explorerType', '');
    }
    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->setTpl('Example_explorerLayoutSection.tpl');
    $this->template->exec();
  }

  // función común a todos los exploradores
  function commonLayout(){

    $url_parts = explode('/', $_SERVER["REQUEST_URI"]);
    if(sizeof($url_parts)>2){
      $url = $url_parts[2];
    }
    else {
      $url = $url_parts[1];
    }
    return $url;
  }
}
