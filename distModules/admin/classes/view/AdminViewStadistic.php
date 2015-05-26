<?php
admin::load('view/AdminViewMaster.php');


class AdminViewStadistic extends AdminViewMaster
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
    $template->setTpl('stadisticPage.tpl', 'admin');

    $this->template->addToBlock( 'col8', $template );
    $this->template->assign( 'headTitle', __('Stadistic Page') );
    $this->template->setTpl( 'adminContent-8-4.tpl', 'admin' );
    $this->template->exec();

  }

}
