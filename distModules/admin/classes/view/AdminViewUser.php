<?php
admin::load('view/MasterView.php');


class AdminViewUser extends MasterView
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function editUser() {
    $this->template->setTpl('editUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }

  function showUser() {
    $this->template->setTpl('showUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }

  function listUsers() {
    $this->template->setTpl('listUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }

  function createUser() {
    $this->template->setTpl('createUser.tpl', 'admin');
    $returnedHtml = $this->template->execToString();
    $this->assignHtmlAdmin($returnedHtml);

  }
}

