<?php

Cogumelo::load("coreController/Module.php");
require_once APP_BASE_PATH."/conf/geozzyTopics.php";
require_once APP_BASE_PATH."/conf/geozzyTaxonomyGroups.php";
require_once APP_BASE_PATH."/conf/geozzyResourcetype.php";

define('MOD_GEOZZY_URL_DIR', 'geozzy');

class geozzy extends Module
{
  public $name = "geozzy";
  public $version = "";
  public $dependences = array(

    array(
     "id" =>"underscore",
     "params" => array("underscore#1.8.3"),
     "installer" => "bower",
     "includes" => array("underscore-min.js")
    ),
    array(
     "id" =>"backbonejs",
     "params" => array("backbone#1.1.2"),
     "installer" => "bower",
     "includes" => array("backbone.js")
    )
  );
  public $includesCommon = array(
    'model/ResourceModel.php'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^'.MOD_GEOZZY_URL_DIR.'$#', 'view:AdminViewStadistic::main' );
  }

  static public function moduleRc() {

    user::load("model/UserModel.php");
    user::load("model/RoleModel.php");
    geozzy::load("model/TaxonomygroupModel.php");
    geozzy::load("model/TaxonomytermModel.php");
    geozzy::load("model/TopicModel.php");
    geozzy::load("model/ResourcetypeModel.php");
    geozzy::load("model/ResourcetypeTopicModel.php");


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


    /**
    Crea Taxonomias necesarias para iniciar Geozzy
    */

    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'prominent', 'name' => 'Destacado', 'editable' => 0 ) );
    $taxgroup->save();

    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'hoteltype', 'name' => 'Tipo de Hotel', 'editable' => 1 ) );
    $taxgroup->save();
    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'events', 'name' => 'Eventos', 'editable' => 1 ) );
    $taxgroup->save();
    $taxgroup = new TaxonomygroupModel( array( 'idName' => 'categories', 'name' => 'Categorias', 'editable' => 1 ) );
    $taxgroup->save();


    /**
    Crea Taxonomias definidas en el un archivo de Conf en GeozzyApp por el usuario
    */

    global $GEOZZY_TAXONOMYGROUPS;

    if( count( $GEOZZY_TAXONOMYGROUPS ) > 0 ) {
      foreach( $GEOZZY_TAXONOMYGROUPS as $key => $tax ) {
        $taxgroup = new TaxonomygroupModel( $tax );
        $taxgroup->save();
        if( count( $tax['initialTerms']) > 0 ) {
          foreach( $tax['initialTerms'] as $key => $term ) {
          # code...
            $term['taxgroup'] = $taxgroup->getter('id');
            $taxterm = new TaxonomytermModel( $term );
            $taxterm->save();
          }
        }
      }
    }

    /**
    Crea los resourcetype definidas en el un archivo de Conf en GeozzyApp por el usuario y los establecidos por defecto
    */

    $GEOZZY_DEFAULT_RESOURCETYPE['base'] = array(
      'idName' => 'base',
      'name' => array(
        'es' => 'base',
        'en' => 'base',
        'gl' => 'base'
      )
    );

    $GEOZZY_DEFAULT_RESOURCETYPE['page'] = array(
      'idName' => 'page',
      'name' => array(
        'es' => 'Page',
        'en' => 'Page',
        'gl' => 'Page'
      )
    );

    $GEOZZY_DEFAULT_RESOURCETYPE['menu'] = array(
      'idName' => 'menu',
      'name' => array(
        'es' => 'menu',
        'en' => 'menu',
        'gl' => 'menu'
      )
    );

    $GEOZZY_DEFAULT_RESOURCETYPE['micro'] = array(
      'idName' => 'micro',
      'name' => array(
        'es' => 'micro',
        'en' => 'micro',
        'gl' => 'micro'
      )
    );

    $GEOZZY_DEFAULT_RESOURCETYPE['image'] = array(
      'idName' => 'image',
      'name' => array(
        'es' => 'image',
        'en' => 'image',
        'gl' => 'image'
      )
    );

    $GEOZZY_DEFAULT_RESOURCETYPE['url'] = array(
      'idName' => 'url',
      'name' => array(
        'es' => 'url',
        'en' => 'url',
        'gl' => 'url'
      )
    );

    $GEOZZY_DEFAULT_RESOURCETYPE['file'] = array(
      'idName' => 'file',
      'name' => array(
        'es' => 'file',
        'en' => 'file',
        'gl' => 'file'
      )
    );
    $GEOZZY_DEFAULT_RESOURCETYPE;

    global $GEOZZY_RESOURCETYPE;

    $GEOZZY_RESOURCETYPE_ALL = array_merge( $GEOZZY_DEFAULT_RESOURCETYPE, $GEOZZY_RESOURCETYPE );

    if( count( $GEOZZY_RESOURCETYPE_ALL ) > 0 ) {
      foreach( $GEOZZY_RESOURCETYPE_ALL as $key => $rt ) {
        $rt['name'] = $rt['name']['es'];
        $rtO = new ResourcetypeModel( $rt );
        $rtO->save();
      }
    }

    /**
    Crea los Topics definidas en el un archivo de Conf en GeozzyApp por el usuario
    */
    global $GEOZZY_TOPICS;

    if( count( $GEOZZY_TOPICS ) > 0 ) {
      foreach( $GEOZZY_TOPICS as $key => $topic ) {
        $topic['name'] = $topic['name']['es'];
        $topic['idName'] = $key;
        $topicD = new TopicModel( $topic );
        $topicD->save();

        if( count( $topic['resourceTypes'] ) > 0 ) {
          foreach( $topic['resourceTypes'] as $key => $rt ) {
            $reTypeModel = new ResourcetypeModel( );
            $reType = $reTypeModel->listItems( array('filters' => array('idName' => $rt['resourceTypeIdName']) ) )->fetch();
            if($reType){
              $rtypeTopicParams = array(
                'topic' => $topicD->getter('id'),
                'resourceType' => $reType->getter('id'),
                'weight' => $rt['weight']
              );

              $rtt = new ResourcetypeTopicModel( $rtypeTopicParams );
              $rtt->save();
            }
          }
        }
      }
    }




  }

  /**
   * Get a password from the shell.
   * This function works on *nix systems only and requires shell_exec and stty.
   *
   * @param boolean $stars Wether or not to output stars for given characters
   *
   * @return string
   */
  static public function getPassword( $stars = false )
  {
    // Get current style
    $oldStyle = shell_exec('stty -g');

    if( $stars === false ) {
      shell_exec('stty -echo');
      $password = rtrim(fgets(STDIN), "\n");
    }
    else {
      shell_exec('stty -icanon -echo min 1 time 0');

      $password = '';
      while (true) {
        $char = fgetc(STDIN);

        if ($char === "\n") {
          break;
        }
        else if (ord($char) === 127) {
          if( strlen($password) > 0 ) {
            fwrite(STDOUT, "\x08 \x08");
            $password = substr($password, 0, -1);
          }
        }
        else {
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
