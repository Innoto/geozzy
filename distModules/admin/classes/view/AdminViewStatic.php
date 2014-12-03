<?php
admin::load('view/MasterView.php');


class AdminViewStatic extends MasterView
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

