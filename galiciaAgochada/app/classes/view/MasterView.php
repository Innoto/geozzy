<?php

Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class MasterView extends View
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
    global $C_LANG;
    $this->actLang = $C_LANG;
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {

    $accessValid = false;

    $validIp = array(
      '213.60.18.106', // Innoto
      '176.83.204.135', '91.117.124.2', // ITG
      '91.116.191.224', // Zadia
      '127.0.0.1'
    );

    if( in_array( $_SERVER['REMOTE_ADDR'], $validIp ) || strpos( $_SERVER['REMOTE_ADDR'], '10.77.' ) === 0 ) {
      $accessValid = true;
    }
    else {
      if(
        ( !isset( $_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!= GA_ACCESS_USER ) &&
        ( !isset( $_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW']!= GA_ACCESS_PASSWORD ) )
      {
        error_log( 'BLOQUEO --- Acceso Denegado!!!' );
        header('WWW-Authenticate: Basic realm="Galicia Agochada"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acceso Denegado.';
        // exit;
      }
      else {
        $accessValid = true;
      }
    }

    return $accessValid;
  }


  public function page404() {
    echo 'PAGE404: Recurso non atopado';
  }


  public function home() {
    $resourceTaxAllModel = new ResourceTaxonomyAllModel( );

    /**
    Destacados de RecantosConEstilo
    **/
    $dList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'RecantosConEstilo',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel','UrlAliasModel')
      )
    );
    $resDest = array();
    while ( $dRes = $dList->fetch() ) {

      $resource = $dRes->getterDependence('resource');

      if($resource){
        $resDest = array_merge( $resDest, $resource );
        if($resDest){
          foreach ($resDest as $res){
            $urlAlias = $this->getUrlAlias($res->getter('id'));
            $resourceArrayRecantos[$res->getter('id')]['urlAlias'] = $urlAlias;
            $resData = $res->getAllData();
            $resourceArrayRecantos[$res->getter('id')]['data'] = $resData['data'];
          }
        }
      }
    }
    $this->template->assign('rdRecantosConEstilo', $resourceArrayRecantos);
    // end

    /**
    Destacados de Festa Rachada
    **/
    $dList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'FestaRachada',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel')
      )
    );
    $resDest = array();
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
        if($resDest){
          foreach ($resDest as $res){
            $urlAlias = $this->getUrlAlias($res->getter('id'));
            $resourceArrayFesta[$res->getter('id')]['urlAlias'] = $urlAlias;
            $resData = $res->getAllData();
            $resourceArrayFesta[$res->getter('id')]['data'] = $resData['data'];
          }
        }
      }
    }
    $this->template->assign('rdFestaRachada', $resourceArrayFesta);
    // end

    /**
    Destacados de PraiasDeEnsono
    **/
    $dList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'PraiasDeEnsono',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel')
      )
    );
    $resDest = array();
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
        if($resDest){
          foreach ($resDest as $res){
            $urlAlias = $this->getUrlAlias($res->getter('id'));
            $resourceArrayPraias[$res->getter('id')]['urlAlias'] = $urlAlias;
            $resData = $res->getAllData();
            $resourceArrayPraias[$res->getter('id')]['data'] = $resData['data'];
          }
        }
      }
    }
    $this->template->assign('rdPraiasDeEnsono', $resourceArrayPraias);
    // end

    /**
    Destacados de PaisaxesEspectaculares
    **/
    $dList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'PaisaxesEspectaculares',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel')
      )
    );
    $resDest = array();
    $resourceArrayPaisaxes = array();
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
        if($resDest){
          foreach ($resDest as $res){
            $urlAlias = $this->getUrlAlias($res->getter('id'));
            $resourceArrayPaisaxes[$res->getter('id')]['urlAlias'] = $urlAlias;
            $resData = $res->getAllData();
            $resourceArrayPaisaxes[$res->getter('id')]['data'] = $resData['data'];
          }
        }
      }
    }
    $this->template->assign('rdPaisaxesEspectaculares', $resourceArrayPaisaxes);
    // end

    /**
    Destacados de AloxamentoConEncantos
    **/
    $dList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'AloxamentoConEncanto',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel')
      )
    );
    $resDest = array();
    $resourceArrayAloxamentos = array();
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
        if($resDest){
          foreach ($resDest as $res){
            $urlAlias = $this->getUrlAlias($res->getter('id'));
            $resourceArrayAloxamentos[$res->getter('id')]['urlAlias'] = $urlAlias;
            $resData = $res->getAllData();
            $resourceArrayAloxamentos[$res->getter('id')]['data'] = $resData['data'];
          }
        }
      }
    }
    $this->template->assign('rdAloxamentoConEncanto', $resourceArrayAloxamentos);
    // end

    /**
    Destacados de AutenticaGastronomia
    **/
    $dList = $resourceTaxAllModel->listItems(
      array(
        'filters' => array(
          'idName' => 'AutenticaGastronomia',
          'idNameTaxgroup' => 'starred'
        ),
        'order' => array(
          'weightResTAxTerm' => '-1'
        ),
        'affectsDependences' => array('ResourceModel')
      )
    );
    $resDest = array();
    $resourceArrayGastronomia = array();
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
        if($resDest){
          foreach ($resDest as $res){
            $urlAlias = $this->getUrlAlias($res->getter('id'));
            $resourceArrayGastronomia[$res->getter('id')]['urlAlias'] = $urlAlias;
            $resData = $res->getAllData();
            $resourceArrayGastronomia[$res->getter('id')]['data'] = $resData['data'];
          }
        }
      }
    }
    $this->template->assign('rdAutenticaGastronomia', $resourceArrayGastronomia);
    // end

    $this->template->assign('isFront', true);
    $this->template->addClientScript('js/portada.js');
    $this->template->addClientStyles('styles/masterPortada.less');
    $this->template->setTpl('portada.tpl');
    $this->template->exec();
  }

  public function exampleComarca() {
    $this->template->setTpl('zonaMap.tpl','rextAppZona');
    $this->template->exec();
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

}
