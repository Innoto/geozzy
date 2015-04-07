<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
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
      Cogumelo::redirect('/adminOld/login');
      $res = false;
    }
    return $res;
  }


  public function commonAdminInterface(){
    $this->template->setTpl('masterAdmin.tpl', 'admin');
    $useraccesscontrol = new UserAccessController();
    $user = $useraccesscontrol->getSessiondata();
    $this->template->assign( 'user' , $user);

    $taxgroupModel = new TaxonomygroupModel( );
    $taxs = $taxgroupModel->listItems()->fetchAll();
    $taxDestacado = "";

    foreach ($taxs as $key => $tax) {
      if($tax->getter('idName') == "Destacado"){
        $taxDestacado = $tax;
      }
    }
    $this->template->assign( 'taxs' , $taxs);
    $this->template->assign( 'taxDestacado' , $taxDestacado);


    $this->template->exec();
  }


  public function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/admin');
  }

}
