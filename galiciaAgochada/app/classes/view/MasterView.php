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

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {

    if( !MOD_DEVEL_ALLOW_ACCESS ) {
      Cogumelo::error("Must be developer to enter on this site");
      RequestController::redirect(SITE_URL_CURRENT.'');
    }
    else {
      if ((!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!= GA_ACCESS_USER) && (!isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW']!= GA_ACCESS_PASSWORD )) {
        header('WWW-Authenticate: Basic realm="Galicia Agochada"');
        header('HTTP/1.0 401 Unauthorized');
        echo 'Acceso Denegado.';
        exit;
      }
      else {
        return true;
      }
    }
  }


  function page404() {
    echo 'PAGE404: Recurso non atopado';
  }
  function home(){

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
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
      }
    }
    $this->template->assign('rdRecantosConEstilo', $resDest);
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
      }
    }
    $this->template->assign('rdFestaRachada', $resDest);
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
      }
    }
    $this->template->assign('rdPraiasDeEnsono', $resDest);
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
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
      }
    }
    $this->template->assign('rdPaisaxesEspectaculares', $resDest);
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
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
      }
    }
    $this->template->assign('rdAloxamentoConEncanto', $resDest);
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
    while ( $dRes = $dList->fetch() )
    {
      $resource = $dRes->getterDependence('resource');
      if($resource){
        $resDest = array_merge( $resDest, $resource );
      }
    }
    $this->template->assign('rdAutenticaGastronomia', $resDest);
    // end


    $this->template->addClientScript('js/portada.js');
    $this->template->addClientStyles('styles/masterPortada.less');
    $this->template->setTpl('portada.tpl');
    $this->template->exec();
  }

  function exampleComarca(){
    $this->template->setTpl('zonaMap.tpl','rextAppZona');
    $this->template->exec();
  }

}
