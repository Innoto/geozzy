<?php
admin::load('view/AdminViewMaster.php');


class AdminViewBi extends AdminViewMaster
{

  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }

  public function dashboard() {

    
    $biView =  new biView();
    $dashboardBlock = $biView->dashboard();
    
/*$dashboardBlock->setTpl('dashboard.tpl', 'admin');*/

    $this->template->addToBlock( 'col12', $dashboardBlock);
    $this->template->assign( 'headTitle', __('Stats') );
    $this->template->setTpl( 'adminContent-12.tpl', 'admin' );
    $this->template->exec();
  }



}
