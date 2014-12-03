<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
admin::autoIncludes();
form::autoIncludes();
user::autoIncludes();


class MasterView extends View
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
  function common(){
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    $this->template->assign( 'user' , $user);
  }

  function assignHtmlAdmin($html) {
    $this->common();
    $this->template->assign( 'sectionHtml' , $html);
    $this->template->setTpl('masterAdmin.tpl', 'admin');
    $this->template->exec();
  }


  function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/admin');
  }
}

