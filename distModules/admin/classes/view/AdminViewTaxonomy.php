<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTaxonomy extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  /**
  * Section list user
  **/

  function listTaxTerm( $request ) {

    $taxtermModel =  new TaxonomytermModel();
    $taxTerms = $taxtermModel->listItems( array( 'filters' => array( 'taxgroup' => $request[1] ) ) )->fetchAll();
    $this->template->assign( 'taxId', $request[1] );
    $this->template->assign( 'taxTerms', $taxTerms );

    $this->template->setTpl('listTaxTerm.tpl', 'admin');
    $this->commonAdminInterface();

  }


  function sendTaxTerm() {
    $res = true;

    if( isset($_POST['id']) && !is_int($_POST['id']) ){
      $res = false;
    }
    var_dump($res." 1");
    if( !isset($_POST['idName']) ){
      $res = false;
    }
    var_dump($res." 2");
    var_dump($_POST['group']);
    if( !isset($_POST['group']) || !is_numeric($_POST['group']) ){
      $res = false;
    }
    var_dump($res." 3");
    if($res){
      $taxGroupModel =  new TaxonomygroupModel();
      $taxGroup = $taxGroupModel->listItems( array('filters' => array('id' => $_POST['group']) ) )->fetch();
      if(!$taxGroup->getter('editable')){
        $res = false;
      }
    }
    var_dump($res." 4");


    if($res){
      var_dump($res." 5");
      $taxTerm =  new TaxonomytermModel($_POST);
      $taxTerm->save();
      $res = $taxTerm;
    }
    var_dump($res." final");
    return $res;
  }



}

