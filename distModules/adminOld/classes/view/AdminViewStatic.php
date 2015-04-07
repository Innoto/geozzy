<?php
adminOld::load('view/AdminViewMaster.php');


class AdminViewStatic extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function allTables() {
    $this->template->setTpl('alltable.tpl', 'adminOld');
    $this->commonAdminInterface();
  }

  function addContent() {
    $this->template->setTpl('addcontent.tpl', 'adminOld');
    $this->commonAdminInterface();
  }
}

