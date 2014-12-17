<?php
admin::load('view/AdminViewMaster.php');


class AdminViewStadistic extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }

  function main(){

    $this->template->addClientScript('js/exampleMorrisData.js' , 'admin');
    $this->template->setTpl('stadisticPage.tpl', 'admin');
    $this->commonAdminInterface();

  }
}

