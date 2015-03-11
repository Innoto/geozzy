<?php

Cogumelo::load("coreController/Module.php");

define('MOD_GEOZZY_URL_DIR', 'geozzy');

class geozzy extends Module
{
  public $name = "geozzy";
  public $version = "";
  public $dependences = array(


  );
  public $includesCommon = array(

  );

  function __construct() {
    $this->addUrlPatterns( '#^'.MOD_GEOZZY_URL_DIR.'$#', 'view:AdminViewStadistic::main' );
  }

  static function moduleRc() {

    user::load("model/UserModel.php");
    user::load("model/RoleModel.php");

    /**
    Creacion de Roles de Geozzy
    */
    $roleData = array(
      'name' => 'administrador',
      'description' => 'Role Usuario'
    );
    $role = new RoleModel($roleData);
    $role->save();

    $roleData = array(
      'name' => 'Gestor',
      'description' => 'Role Gestor'
    );
    $role = new RoleModel($roleData);
    $role->save();

    /**
    Crea un usuario superAdmin para Geozzy
    */
    fwrite(STDOUT, "Enter the superAdmin password:\n");
    $passwd = self::getPassword(true);
    $userData = array(
      'login' => 'superAdmin',
      'name' => 'superAdmin',
      'active' => 1
    );
    $user = new UserModel( $userData );
    $user->setPassword( $passwd );
    $user->save();

    /**
    Crea la relacion Usuario/Role de superAdmin asignadole un role superAdmin
    */
    $roleModel = new RoleModel();
    $role = $roleModel->listItems( array('filters' => array('name' => 'superAdmin') ))->fetch();
    $userRole = new UserRoleModel();
    if( $role ){
      $userRole->setterDependence( 'role', $role );
    }
    $userRole->setterDependence( 'user', $user );
    $userRole->save(array( 'affectsDependences' => true ));

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
