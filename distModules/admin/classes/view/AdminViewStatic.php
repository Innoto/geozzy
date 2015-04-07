<?php
admin::load('view/AdminViewMaster.php');


class AdminViewStatic extends AdminViewMaster
{

  public function __construct( $base_dir ){
    parent::__construct( $base_dir );
  }


  public function allTables() {
    $this->template->setTpl('alltable.tpl', 'admin');
    $this->template->exec();
  }


  public function addContent() {
    $this->template->setTpl('addcontent.tpl', 'admin');
    $this->template->exec();
  }

}

