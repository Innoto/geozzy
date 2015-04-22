<?php

require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
Cogumelo::autoIncludes();

/**
* Clase Master to extend other application methods
*/
class MainAPIView extends View
{

  function __construct($baseDir){
    parent::__construct($baseDir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    if( GEOZZY_API_ACTIVE ){
     return true;
    }
  }


  // resources
  function resource() {
    geozzy::load('model/ResourceModel.php');
    $resourceModel = new ResourceModel();
    $resources = $resourceModel->listItems( array('filters'=> array( 'id'=> $_GET['id'] ) ) );

    if( $resource = $resources->fetch() ) {
      $this->syncModel( $recource );
    }
  }

  function resourceList() {
    geozzy::load('model/ResourceModel.php');
    $resourceModel = new ResourceModel();
    $resourceList = $resourceModel->listItems(  array( 'filters' => array( ) ) );
    $this->syncModelList( $resourceList );
  }

  function resourceTypes() {
    geozzy::load('model/ResourcetypeModel.php');    
    $resourcetypeModel = new ResourcetypeModel( );
    $resourcetypeList = $resourcetypeModel->listItems( ) ;
    $this->syncModelList( $resourcetypeList );
  }

  // Categories

  function categoryList() {
    geozzy::load('model/TaxonomygroupModel.php');    
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));
    $this->syncModelList( $taxGroupList );

  }
  
  function categoryTerms() {
    geozzy::load('model/TaxonomytermModel.php');    
    $taxtermModel = new TaxonomytermModel();
    $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$_GET['group']) ) );
    $this->syncModelList( $taxtermList );
  }

  // Topics

  function topicList() {
    geozzy::load('model/TopicModel.php');    
    $topicModel = new TopicModel();
    $topicList = $topicModel->listItems( );
    $this->syncModelList( $topicList );
  }

  // explorers

  function explorers()  {

  }



  function syncModelList( $result ) {

    header('Content-type: application/json');
    echo '[';
    $c = '';
    while ($valueobject = $result->fetch() )
    {
      $allData = $valueobject->getAllData();
      echo $c.json_encode( $allData['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';
  }


  function syncModel( $model ) {
    header('Content-type: application/json');
    $data = $model->getAllData();
    echo $c.json_encode( $data['data'] );
  }


}