<?php
admin::load('view/MasterView.php');


class StadisticView extends MasterView
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $res = true;
    if(!$useraccesscontrol->isLogged()){
      Cogumelo::redirect('/admin/login');
      $res = false;
    }
    return $res;
  }

  function main(){
    $this->template->addClientScript('js/exampleMorrisData.js' , 'admin');
    $this->template->setTpl('stadisticPage.tpl', 'admin');
    $stadisticHTML = $this->template->execToString();
    Cogumelo::console( $stadisticHTML );
    $this->assignHtmlAdmin($stadisticHTML);
  }
}

