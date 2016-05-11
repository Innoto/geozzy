<?php
admin::load('view/AdminViewMaster.php');


class AdminViewMultiList extends AdminViewMaster
{

  public function __construct( $base_dir ) {
    parent::__construct( $base_dir );
  }


  public function main() {


    geozzy::load("model/TaxonomygroupModel.php");
    geozzy::load("model/TaxonomytermModel.php");

    $template = new Template( $this->baseDir );

    $taxtermModel = new TaxonomytermModel();

    $template->assign('termArray', $taxtermModel->listItems()->fetchAll() );
    $template->setTpl('multiListExamples.tpl', 'admin');

    $this->template->addToFragment( 'col8', $template );
    $this->template->assign( 'headTitle', __('Stats Page') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $this->template->exec();

  }

}
