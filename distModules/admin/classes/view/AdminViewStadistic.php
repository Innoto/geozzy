<?php
admin::load('view/AdminViewMaster.php');


class AdminViewStadistic extends AdminViewMaster
{

  public function __construct( $base_dir ) {
    parent::__construct( $base_dir );
  }


  public function main() {

    $this->template->setTpl('stadisticPage.tpl', 'admin');
    // $this->commonAdminInterface();
    $this->template->exec();
  }

}

