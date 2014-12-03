<?php
admin::load('view/MasterView.php');


class AdminViewStadistic extends MasterView
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }

  function main(){
    $this->template->addClientScript('js/exampleMorrisData.js' , 'admin');
    $this->template->setTpl('stadisticPage.tpl', 'admin');
    $stadisticHTML = $this->template->execToString();
    $this->assignHtmlAdmin($stadisticHTML);
  }
}

