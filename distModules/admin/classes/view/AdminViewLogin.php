<?php
admin::load('view/AdminViewMaster.php');


class AdminViewLogin extends AdminViewMaster
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
    if($useraccesscontrol->isLogged()){
      Cogumelo::redirect('/admin');
      $res = false;
    }
    return $res;
  }


  public function main() {
    header("HTTP/1.0 401");
    $userView = new UserView();

    $form = $userView->loginFormDefine();
    $form->setAction('/admin/senduserlogin');
    $form->setSuccess( 'redirect', '/admin' );

    $loginHtml = $userView->loginFormGet( $form );
    $this->template->assign('loginHtml', $loginHtml);

    $this->template->setTpl('loginForm.tpl', 'admin');
    $this->template->exec();
  }


  public function sendLoginForm() {

    $userView = new UserView();

    $form = $userView->actionLoginForm();
    $form->sendJsonResponse();
  }

}

