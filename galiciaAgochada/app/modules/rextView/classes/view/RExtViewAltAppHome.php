<?php


class RExtViewAltAppHome {

  public $defRExtViewCtrl = false;
  public $defRTypeCtrl = false;
  public $defResCtrl = false;


  public function __construct( $defRExtViewCtrl ){
    error_log( 'RExtViewAltAppHome::__construct' );
    $this->defRExtViewCtrl = $defRExtViewCtrl;
    $this->defRTypeCtrl = $this->defRExtViewCtrl->defRTypeCtrl;
    $this->defResCtrl = $this->defRTypeCtrl->defResCtrl;

    global $C_LANG;
    $this->actLang = $C_LANG;
  }



  /**
    Alteramos la visualizacion el Recurso
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    error_log( "RExtViewAltAppHome: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    /*
      $viewBlockInfo = array(
        'template' => array objTemplate,
        'data' => resourceData,
        'ext' => array rExt->viewBlockInfo
      );
    */

    // Podemos obtener datos de las estructura
    $resId = $viewBlockInfo['data']['id'];

    // Cambiar datos
    //$viewBlockInfo['data']['title'] = 'Un dato metido desde o View Aternativo ;-) ('.$resId.')';

    // Recargamos los datos en las variables que usamos del objTemplate
    $viewBlockInfo['template']['full']->assign( 'res', array( 'data' => $viewBlockInfo['data'], 'ext' => $viewBlockInfo['ext'] ) );

    $resourceTaxAllModel = new ResourceTaxonomyAllModel( );
    $resourceModel = new ResourceModel( );

    /**
    Destacados de RecantosConEstilo
    **/
    $resList = $resourceModel->listItems(
      array(
        'filters'=> array(
          'ResourceTaxonomyAllModel.idName' => 'RecantosConEstilo',
          'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
        ),
        'affectsDependences' => array('ResourceTaxonomyAllModel'),
        'joinType' => 'RIGHT'
      )
    );
    $resourceArrayRecantos = array();
    while ( $res = $resList->fetch() ) {
      $resAll = $res->getAllData();
      $resourceArrayRecantos[$res->getter('id')]['data'] = $resAll['data'];
      $urlAlias = $this->getUrlAlias($res->getter('id'));
      $resourceArrayRecantos[$res->getter('id')]['urlAlias'] = $urlAlias;
    }

    $viewBlockInfo['template']['full']->assign('rdRecantosConEstilo', $resourceArrayRecantos);


      /**
      Destacados de Festa Rachada
      **/

      $resList = $resourceModel->listItems(
        array(
          'filters'=> array(
            'ResourceTaxonomyAllModel.idName' => 'FestaRachada',
            'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
          ),
          'affectsDependences' => array('ResourceTaxonomyAllModel'),
          'joinType' => 'RIGHT'
        )
      );
      $resourceArrayFesta = array();
      while ( $res = $resList->fetch() ) {
        $resAll = $res->getAllData();
        $resourceArrayFesta[$res->getter('id')]['data'] = $resAll['data'];
        $urlAlias = $this->getUrlAlias($res->getter('id'));
        $resourceArrayFesta[$res->getter('id')]['urlAlias'] = $urlAlias;
      }

      $viewBlockInfo['template']['full']->assign('rdFestaRachada', $resourceArrayFesta);

      /**
      Destacados de PraiasDeEnsono
      **/

      $resList = $resourceModel->listItems(
        array(
          'filters'=> array(
            'ResourceTaxonomyAllModel.idName' => 'PraiasDeEnsono',
            'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
          ),
          'affectsDependences' => array('ResourceTaxonomyAllModel'),
          'joinType' => 'RIGHT'
        )
      );
      $resourceArrayPraias = array();
      while ( $res = $resList->fetch() ) {
        $resAll = $res->getAllData();
        $resourceArrayPraias[$res->getter('id')]['data'] = $resAll['data'];
        $urlAlias = $this->getUrlAlias($res->getter('id'));
        $resourceArrayPraias[$res->getter('id')]['urlAlias'] = $urlAlias;
      }

      $viewBlockInfo['template']['full']->assign('rdPraiasDeEnsono', $resourceArrayPraias);

      /**
      Destacados de PaisaxesEspectaculares
      **/

      $resList = $resourceModel->listItems(
        array(
          'filters'=> array(
            'ResourceTaxonomyAllModel.idName' => 'PaisaxesEspectaculares',
            'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
          ),
          'affectsDependences' => array('ResourceTaxonomyAllModel'),
          'joinType' => 'RIGHT'
        )
      );
      $resourceArrayPaisaxes = array();
      while ( $res = $resList->fetch() ) {
        $resAll = $res->getAllData();
        $resourceArrayPaisaxes[$res->getter('id')]['data'] = $resAll['data'];
        $urlAlias = $this->getUrlAlias($res->getter('id'));
        $resourceArrayPaisaxes[$res->getter('id')]['urlAlias'] = $urlAlias;
      }

      $viewBlockInfo['template']['full']->assign('rdPaisaxesEspectaculares', $resourceArrayPaisaxes);

      /**
      Destacados de AloxamentoConEncantos
      **/

      $resList = $resourceModel->listItems(
        array(
          'filters'=> array(
            'ResourceTaxonomyAllModel.idName' => 'AloxamentoConEncanto',
            'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
          ),
          'affectsDependences' => array('ResourceTaxonomyAllModel'),
          'joinType' => 'RIGHT'
        )
      );
      $resourceArrayAloxamentos = array();
      while ( $res = $resList->fetch() ) {
        $resAll = $res->getAllData();
        $resourceArrayAloxamentos[$res->getter('id')]['data'] = $resAll['data'];
        $urlAlias = $this->getUrlAlias($res->getter('id'));
        $resourceArrayAloxamentos[$res->getter('id')]['urlAlias'] = $urlAlias;
      }

      $viewBlockInfo['template']['full']->assign('rdAloxamentoConEncanto', $resourceArrayAloxamentos);

      /**
      Destacados de AutenticaGastronomia
      **/

      $resList = $resourceModel->listItems(
        array(
          'filters'=> array(
            'ResourceTaxonomyAllModel.idName' => 'AutenticaGastronomia',
            'ResourceTaxonomyAllModel.idNameTaxgroup' => 'starred'
          ),
          'affectsDependences' => array('ResourceTaxonomyAllModel'),
          'joinType' => 'RIGHT'
        )
      );
      $resourceArrayGastronomia = array();
      while ( $res = $resList->fetch() ) {
        $resAll = $res->getAllData();
        $resourceArrayGastronomia[$res->getter('id')]['data'] = $resAll['data'];
        $urlAlias = $this->getUrlAlias($res->getter('id'));
        $resourceArrayGastronomia[$res->getter('id')]['urlAlias'] = $urlAlias;
      }

      $viewBlockInfo['template']['full']->assign('rdAutenticaGastronomia', $resourceArrayGastronomia);

      $viewBlockInfo['template']['full']->addClientScript('js/portada.js' );
      $viewBlockInfo['template']['full']->addClientStyles('styles/masterPortada.less' );
      $viewBlockInfo['template']['full']->setTpl( 'rExtViewAltAppHome.tpl', 'rextView' );


    return $viewBlockInfo;
  }

  // Obtiene la url del recurso en el idioma especificado y sino, en el idioma actual
  public function getUrlAlias($resId, $lang = false){
    $urlAliasModel = new UrlAliasModel();

    if ($lang){
      $langId = $lang;
    }
    else{
      $langId = $this->actLang;
    }
    $urlAlias = false;
    $urlAliasList = $urlAliasModel->listItems( array( 'filters' => array( 'resource' => $resId, 'lang' => $langId ) ) )->fetch();

    if ($urlAliasList){
      $urlAlias = $langId.$urlAliasList->getter('urlFrom');
    }

    return $urlAlias;
  }

} // class RExtViewAltAppMylandpage
