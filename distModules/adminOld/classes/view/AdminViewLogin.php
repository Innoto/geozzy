<?php
adminOld::load('view/AdminViewMaster.php');


class AdminViewLogin extends AdminViewMaster
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
    if($useraccesscontrol->isLogged()){
      Cogumelo::redirect('/adminOld');
      $res = false;
    }
    return $res;
  }

  function main(){

    $userView = new UserView();

    $form = $userView->loginFormDefine();
    $form->setAction('/adminOld/senduserlogin');
    $form->setSuccess( 'redirect', '/adminOld' );

    $loginHtml = $userView->loginFormGet( $form );
    $this->template->assign('loginHtml', $loginHtml);

    $this->template->setTpl('loginForm.tpl', 'adminOld');
    $this->template->exec();
  }

  function sendLoginForm() {

    $userView = new UserView();

    $form = $userView->actionLoginForm();
    $form->sendJsonResponse();
  }
}

