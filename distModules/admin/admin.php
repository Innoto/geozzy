<?php

Cogumelo::load("coreController/Module.php");

define('MOD_ADMIN_URL_DIR', 'admin');

class admin extends Module
{
  public $name = "admin";
  public $version = "";
  public $dependences = array(
    array(
     "id" => "bootstrap",
     "params" => array("bootstrap"),
     "installer" => "bower",
     "includes" => array("dist/css/bootstrap.min.css", "dist/js/bootstrap.min.js")
    ),
    array(
     "id" => "font-awesome",
     "params" => array("font-awesome-4.2.0"),
     "installer" => "manual",
     "includes" => array("css/font-awesome.min.css")
    ),
    array(
     "id" =>"metismenu",
     "params" => array("metisMenu"),
     "installer" => "bower",
     "includes" => array("dist/metisMenu.min.css", "dist/metisMenu.min.js")
    ),
    array(
     "id" =>"html5shiv",
     "params" => array("html5shiv"),
     "installer" => "bower",
     "includes" => array("dist/html5shiv.js")
    ),
    array(
     "id" =>"respond",
     "params" => array("respond"),
     "installer" => "bower",
     "includes" => array("src/respond.js")
    ),
    array(
     "id" =>"raphael",
     "params" => array("raphael"),
     "installer" => "bower",
     "includes" => array("raphael-min.js")
    ),
    array(
     "id" =>"morrisjs",
     "params" => array("morris.js-0.5.1"),
     "installer" => "manual",
     "includes" => array("morris.js", "morris.css")
    )

  );

  public $includesCommon = array(
    'styles/adminBase.less',
    'styles/admin.less',
    'js/adminBase.js'
  );

  function __construct() {
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'$#', 'view:AdminViewStadistic::main' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/charts$#', 'view:AdminViewStadistic::main' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/alltables$#', 'view:AdminViewStatic::allTables' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/addcontent$#', 'view:AdminViewStatic::addContent' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/logout$#', 'view:AdminViewMaster::sendLogout' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/login$#', 'view:AdminViewLogin::main' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/senduserlogin$#', 'view:AdminViewLogin::sendLoginForm' );

    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/edit/(.*)$#', 'view:AdminViewUser::editUser' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/show$#', 'view:AdminViewUser::showUser' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/list$#', 'view:AdminViewUser::listUsers' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/table$#', 'view:AdminViewUser::listUsersTable' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/create$#', 'view:AdminViewUser::createUser' );
    $this->addUrlPatterns( '#^'.MOD_ADMIN_URL_DIR.'/user/senduser$#', 'view:AdminViewUser::sendUserForm' );
  }

  static function moduleRc() {
;
    user::load("model/UserModel.php");


    fwrite(STDOUT, "Enter the superAdmin password:\n");
    $passwd = self::getPassword(true);

    $userData = array(
      'login' => 'superAdmin',
      'name' => 'superAdmin',
      'password' => SHA1($passwd),
      'email' => 'arodriguez@map-experience.com',
      'role' => 10,
      'status' => USER_STATUS_ACTIVE
    );
    $user = new UserModel($userData);
    $user->save();
  }

  /**
   * Get a password from the shell.
   * This function works on *nix systems only and requires shell_exec and stty.
   *
   * @param  boolean $stars Wether or not to output stars for given characters
   * @return string
   */
  static function getPassword($stars = false)
  {
      // Get current style
      $oldStyle = shell_exec('stty -g');

      if ($stars === false) {
          shell_exec('stty -echo');
          $password = rtrim(fgets(STDIN), "\n");
      } else {
          shell_exec('stty -icanon -echo min 1 time 0');

          $password = '';
          while (true) {
              $char = fgetc(STDIN);

              if ($char === "\n") {
                  break;
              } else if (ord($char) === 127) {
                  if (strlen($password) > 0) {
                      fwrite(STDOUT, "\x08 \x08");
                      $password = substr($password, 0, -1);
                  }
              } else {
                  fwrite(STDOUT, "*");
                  $password .= $char;
              }
          }
      }

      // Reset old style
      shell_exec('stty ' . $oldStyle);

      // Return the password
      return $password;
  }
}
