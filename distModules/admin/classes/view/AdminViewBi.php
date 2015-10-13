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
//    $dashboardBlock->exec();
  }



}
