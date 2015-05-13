<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
geozzy::autoIncludes();
admin::autoIncludes();
form::autoIncludes();
user::autoIncludes();
table::autoIncludes();

class AdminViewMaster extends View
{

  public function __construct( $base_dir ) {
    parent::__construct($base_dir);
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    $useraccesscontrol = new UserAccessController();
    $res = true;
    if(!$useraccesscontrol->isLogged()){
      Cogumelo::redirect('/admin/login');
      $res = false;
    }
    return $res;
  }


  public function commonAdminInterface(){
    $this->template->setTpl('adminMaster.tpl', 'admin');
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    $this->template->assign( 'user' , $user);

    $this->template->exec();
  }


  public function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/admin');
  }

}
