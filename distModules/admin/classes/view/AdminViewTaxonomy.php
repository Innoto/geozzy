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


  /**
  * Section create role
  **/

  function createTaxTerm() {

  }

  /**
  * Section edit role
  **/

  function editTaxTerm($request) {

  }



}

