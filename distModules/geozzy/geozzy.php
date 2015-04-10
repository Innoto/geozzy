<?php

Cogumelo::load("coreController/Module.php");
require_once APP_BASE_PATH."/conf/geozzyTopics.php";
require_once APP_BASE_PATH."/conf/geozzyTaxonomiesGroups.php";

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
    geozzy::load("model/TaxonomygroupModel.php");
    geozzy::load("model/TaxonomytermModel.php");

    /**
    Creacion de Roles de Geozzy
    */
/*
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
*/
    /**
    Crea un usuario superAdmin para Geozzy
    */
/*
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
*/
    /**
    Crea la relacion Usuario/Role de superAdmin asignadole un role superAdmin
    */
/*
    $roleModel = new RoleModel();
    $role = $roleModel->listItems( array('filters' => array('name' => 'superAdmin') ))->fetch();
    $userRole = new UserRoleModel();
    if( $role ){
      $userRole->setterDependence( 'role', $role );
    }
    $userRole->setterDependence( 'user', $user );
    $userRole->save(array( 'affectsDependences' => true ));
*/

    /**
    Crea Taxonomias necesarias para iniciar Geozzy
    */
/*
    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'prominent', 'name' => 'Destacado', 'editable' => 0 ) );
    $taxgroup->save();

    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'hoteltype', 'name' => 'Tipo de Hotel', 'editable' => 1 ) );
    $taxgroup->save();
    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'events', 'name' => 'Eventos', 'editable' => 1 ) );
    $taxgroup->save();
    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'categories', 'name' => 'Categorias', 'editable' => 1 ) );
    $taxgroup->save();
*/

    /**
    Crea Taxonomias definidas en el un archivo de Conf en GeozzyApp por el usuario
    */
    /*global $GEOZZY_TAXONOMIESGROUPS;

    if(sizeof($GEOZZY_TAXONOMIESGROUPS) > 0){
      foreach ($GEOZZY_TAXONOMIESGROUPS as $key => $tax) {
        $taxgroup = new TaxonomygroupModel( $tax );
        $taxgroup->save();
        if(sizeof($tax['initialTerms']) > 0){
          foreach ($tax['initialTerms'] as $key => $term) {
          # code...
            $term['taxgroup'] = $taxgroup->getter('id');
            $taxterm = new TaxonomytermModel( $term );
            $taxterm->save();
          }
        }
      }
    }*/

    /**
    Crea los Topics definidas en el un archivo de Conf en GeozzyApp por el usuario
    */
    global $GEOZZY_TOPICS;

    if(sizeof($GEOZZY_TOPICS) > 0){
      foreach ($GEOZZY_TOPICS as $key => $topic) {





      }
    }


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
