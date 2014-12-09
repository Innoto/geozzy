<?php
admin::load('view/AdminViewMaster.php');


class AdminViewStatic extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function allTables() {
    $this->template->setTpl('alltable.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }

  function addContent() {
    $this->template->setTpl('addcontent.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }
}

