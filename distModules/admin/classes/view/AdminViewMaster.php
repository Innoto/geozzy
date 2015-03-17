<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
admin::autoIncludes();
form::autoIncludes();
user::autoIncludes();


class AdminViewMaster extends View
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
  function commonAdminInterface(){
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    $this->template->assign( 'user' , $user);

    $taxgroupModel = new TaxonomygroupModel(  );


    $this->template->exec();
  }


  function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/admin');
  }
}

