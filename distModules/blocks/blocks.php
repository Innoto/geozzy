<?php

Cogumelo::load("coreController/Module.php");

define('MOD_ADMIN_URL_DIR', 'blocks');

class blocks extends Module
{
  public $name = "blocks";
  public $version = "";
  public $dependences = array(

  );

  public $includesCommon = array(

  );

  function __construct() {
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/changepassword$#', 'view:AdminViewUser::changeUserPasswordForm' );
  }
}
