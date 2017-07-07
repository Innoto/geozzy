<?php

Cogumelo::load('coreView/View.php');
common::autoIncludes();
Cogumelo::autoIncludes();
geozzy::autoIncludes();

class PageAbout {

  public function __construct() {
  }

  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    // error_log( 'alterViewBlockInfo en PageAbout' );
    $resourceCtrl = new ResourceController();

    $templateName = ($templateName) ? $templateName : 'full';
    $template = $viewBlockInfo['template'][ $templateName ];

    $template->assign( 'isFront', false );
    $template->addClientScript( 'js/pageAbout.js' );
    $template->addClientStyles( 'styles/masterPageAbout.less' );
    $template->setTpl( 'pageAbout.tpl' );

    // --------------------------------------------------- Cargamos las colecciones
    $collectionArrayInfo = $resourceCtrl->getCollectionBlockInfo( $viewBlockInfo['data']['id'] );

    $collection = '';
    if ($collectionArrayInfo){
      foreach( $collectionArrayInfo as $key => $collectionInfo ) {
        if ($collectionInfo['col']['collectionType'] === 'base'){ // colecciones multimedia
          $resArray = array();
          if( count( $collectionInfo['res'] ) > 0 ) {
            foreach( $collectionInfo['res'] as $key => $resInfo ) {
              $resInfo['camp'] = $this->getDataCamp( $resInfo );
              $resArray[] = $resInfo;
            }
          }
          $collectionInfo['res'] = $resArray;

          $collection = $collectionInfo;
        }
      }
    }
    $template->assign( 'collection', $collection );

    $viewBlockInfo['template'][ $templateName ] = $template;

    return $viewBlockInfo;
  }

  public function getDataCamp( $resInfo ){
    $resourceCtrl = new ResourceController();
    /*Obtener tipo de visualizacion en el caso de ser campaña*/
    $resVisualizacion = 'mode1';
    $campLinkTarget = false;
    $campLinkURL = false;
    $campViewURL = false;
    $campLinkText = false;
    $rTypeItem = false;
    $rtypeControl = new ResourcetypeModel();
    $rTypeItem = $rtypeControl->ListItems( array( 'filters' => array( 'id' => $resInfo['rType'] ) ) )->fetch();

    if( $rTypeItem->getter('idName') === 'rtypeAppCampaign'){
      $resTaxAll = new ResourceTaxonomyAllModel();
      $resViewTerm = $resTaxAll->listItems(
        array(
          'filters'=> array(
            'resource' => $resInfo['id'],
            'idNameTaxgroup' => 'appCampaignType'
          )
        )
      )->fetch();

      $resVisualizacion = $resViewTerm->getter('idName');

      $rextCampModel = new RExtAppCampaignModel();
      $resRextCamp = $rextCampModel->listItems(
        array(
          'filters'=> array(
            'resource' => $resInfo['id'],
            'idNameTaxgroup' => 'appCampaignType'
          )
        )
      )->fetch();
      $campLinkTarget = $resRextCamp->getter('linkTarget');
      $campLinkResource = $resRextCamp->getter('linkResource');
      $campLinkText = $resRextCamp->getter('linkText');
      $campLinkURL = $resRextCamp->getter('linkUrl');

      if( isset($campLinkResource) && $campLinkResource){
        $campViewURL = $resourceCtrl->getUrlAlias( $campLinkResource );
      }
      else if( $campLinkURL ){
        $campViewURL = $campLinkURL;
      }
      else{
        $campViewURL = $resourceCtrl->getUrlAlias( $resInfo['id'] );
      }
    }

    $response = array(
      'mode' => $resVisualizacion,
      'linkTarget' => $campLinkTarget,
      'linkText' => $campLinkText,
      'viewUrl' => $campViewURL
    );

    return $response;
  }
}
