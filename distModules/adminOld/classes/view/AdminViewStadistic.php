<?php
adminOld::load('view/AdminViewMaster.php');


class AdminViewStadistic extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }

  function main(){

    $this->template->addClientScript('js/exampleMorrisData.js' , 'adminOld');
    $this->template->setTpl('stadisticPage.tpl', 'adminOld');
    $this->commonAdminInterface();

  }
}

