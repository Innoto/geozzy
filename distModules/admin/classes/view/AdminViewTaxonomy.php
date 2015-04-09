<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTaxonomy extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function categoryTermsSync() {
    $taxtermModel = new TaxonomygroupModel();
    $taxtermList = $taxgroupModel->listItems();

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      echo $c.json_encode( $taxGroup->getAllData()['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';
  
  }

  function categoriesSync() {
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems();

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      echo $c.json_encode( $taxGroup->getAllData()['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';
  
  }


  /**
  * Section list user
  **/
/*
  function listTaxTerm( $request ) {


    $taxgroupModel = new TaxonomygroupModel();
    $taxGroup = $taxgroupModel->listItems(
      array(
        'filters' => array( 'id' => $request[1] )
      )
    )->fetch();


    $this->template->assign( 'taxEditable', $taxGroup->getter('editable') ) ;

    $taxtermModel =  new TaxonomytermModel();
    $taxTerms = $taxtermModel->listItems( array( 'filters' => array( 'taxgroup'=>$request[1]) ) )->fetchAll();

    $this->template->assign( 'taxId', $request[1] );
    $this->template->assign( 'taxTerms', $taxTerms );


    $this->template->setTpl('listTaxTerm.tpl', 'admin');
    $this->template->exec();

  }


  function sendTaxTerm() {

    $res = true;
    $dataTerm = $_POST;

    if( isset($dataTerm['id']) && !is_numeric($dataTerm['id']) ){
      $res = false;
    }
    if( !isset($dataTerm['idName']) ){
      $res = false;
    }
    if( !isset($dataTerm['taxgroup']) || !is_numeric($dataTerm['taxgroup']) ){
      $res = false;
    }
    if($res){
      $taxGroupModel =  new TaxonomygroupModel();
      $taxGroup = $taxGroupModel->listItems( array('filters' => array('id' => $dataTerm['taxgroup']) ) )->fetch();
      if(!$taxGroup->getter('editable')){
        $res = false;
      }
    }

    if($res){
      $taxTerm =  new TaxonomytermModel($dataTerm);
      $taxTerm->save();
      $res = $taxTerm->data;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($res);
  }

  function deleteTaxTerm() {
    $res = true;

    if( !isset($_POST['id']) || !is_numeric($_POST['id']) ){
      $res = false;
    }
    if($res){
      $taxGroupModel =  new TaxonomygroupModel();
      $taxGroup = $taxGroupModel->listItems( array('filters' => array('id' => $_POST['taxgroup']) ) )->fetch();
      if(!$taxGroup->getter('editable')){
        $res = false;
      }
    }
    if($res){
      $taxTermModel =  new TaxonomytermModel();
      $taxTerm = $taxTermModel->listItems( array('filters' => array('id' => $_POST['id']) ) )->fetch();
      $res = $taxTerm->delete();
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($res);
  }
*/
}

