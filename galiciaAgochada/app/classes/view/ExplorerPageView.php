<?php
Cogumelo::load('view/MasterView.php');


class ExplorerPageView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }
  // función común a todos los exploradores
  public function commonLayout(){

    $url_parts = explode('/', $_SERVER["REQUEST_URI"]);
    if( count($url_parts)>2 ){
      $url = $url_parts[2];
    }
    else {
      $url = $url_parts[1];
    }

    $this->template->assign('url', $url);
    $this->template->addClientScript('js/TimeDebuger.js', 'common');
    $this->template->addClientScript('js/galiciaAgochadaExplorersUtils.js');
    $this->template->addClientScript('js/model/TaxonomygroupModel.js', 'geozzy');
    $this->template->addClientScript('js/model/TaxonomytermModel.js', 'geozzy');
    $this->template->addClientScript( 'js/model/ResourceModel.js' , 'geozzy');
    $this->template->addClientScript( 'js/collection/ResourceCollection.js' , 'geozzy');
    $this->template->addClientScript('js/collection/CategoryCollection.js', 'geozzy');
    $this->template->addClientScript('js/collection/CategorytermCollection.js', 'geozzy');
    biMetrics::autoIncludes();
    explorer::autoIncludes();

  }

  public function paisaxesExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $this->commonLayout();

    rextRoutes::autoIncludes();

    $this->template->addClientStyles('styles/masterPaisaxesExplorer.less');
    $this->template->addClientScript('js/paisaxesExplorer.js');

    $this->template->addClientStyles('styles/rExtFavourite.less', 'rextFavourite' );
    $this->template->addClientScript('js/rExtFavouriteController.js', 'rextFavourite' );

    $this->template->setTpl('paisaxesExplorerPage.tpl');
  }

  public function rinconsExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $this->commonLayout();

    $this->template->addClientStyles('styles/masterRinconsExplorer.less');
    $this->template->addClientScript('js/rinconsExplorer.js');


    $this->template->addClientScript('js/model/RouteModel.js', 'rextRoutes' );
    $this->template->addClientScript('js/collection/RouteCollection.js', 'rextRoutes' );
    $this->template->addClientScript('js/view/routeView.js', 'rextRoutes' );
    $this->template->addClientScript('js/view/ExplorerRoutesView.js', 'rextRoutes' );


    $this->template->addClientStyles('styles/rExtFavourite.less', 'rextFavourite' );
    $this->template->addClientScript('js/rExtFavouriteController.js', 'rextFavourite' );



    $this->template->setTpl('rinconsExplorerPage.tpl');
  }

  public function praiasExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $this->commonLayout();

    $this->template->addClientStyles('styles/masterPraiasExplorer.less');
    $this->template->addClientScript('js/praiasExplorer.js');

    $this->template->addClientStyles('styles/rExtFavourite.less', 'rextFavourite' );
    $this->template->addClientScript('js/rExtFavouriteController.js', 'rextFavourite' );

    $this->template->setTpl('praiasExplorerPage.tpl');
  }
  public function xantaresExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $this->commonLayout();

    $this->template->addClientStyles('styles/masterXantaresExplorer.less');
    $this->template->addClientScript('js/xantaresExplorer.js');

    $this->template->addClientStyles('styles/rExtFavourite.less', 'rextFavourite' );
    $this->template->addClientScript('js/rExtFavouriteController.js', 'rextFavourite' );

    if( isset($_GET['participation']) ){
      $this->template->assign( 'initParticipation', $_GET['participation'] );
    }

    $this->template->setTpl('xantaresExplorerPage.tpl');
  }
  public function aloxamentosExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $this->commonLayout();

    $this->template->addClientStyles('styles/masterAloxamentosExplorer.less');
    $this->template->addClientScript('js/aloxamentosExplorer.js');

    $this->template->addClientStyles('styles/rExtFavourite.less', 'rextFavourite' );
    $this->template->addClientScript('js/rExtFavouriteController.js', 'rextFavourite' );

    $this->template->setTpl('aloxamentosExplorerPage.tpl');
  }

  public function todosSegredosExplorer(){
    // cogemos la url actual para el idioma (hasta que sea recurso)
    $this->commonLayout();

    $this->template->addClientStyles('styles/masterTodosSegredosExplorer.less');
    $this->template->addClientScript('js/todosSegredosExplorer.js');

    $this->template->addClientStyles('styles/rExtFavourite.less', 'rextFavourite' );
    $this->template->addClientScript('js/rExtFavouriteController.js', 'rextFavourite' );

    $this->template->setTpl('todosSegredosExplorerPage.tpl');
  }

/*Examples*/
  public function explorerLayout( $urlParams = false ){

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

  public function explorerLayoutSection( $urlParams = false ){

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
}
