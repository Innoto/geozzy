<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
adminOld::autoIncludes();
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
      print('hh');
      Cogumelo::redirect('/adminOld/login');
      $res = false;
    }
    return $res;
  }
  function commonAdminInterface(){
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


  function sendLogout() {
    $useraccesscontrol = new UserAccessController();
    $useraccesscontrol->userLogout();
    Cogumelo::redirect('/adminOld');
  }
}

