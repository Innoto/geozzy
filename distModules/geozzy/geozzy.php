<?php

Cogumelo::load( 'coreController/Module.php' );

// define('MOD_GEOZZY_URL_DIR', 'geozzy');


class geozzy extends Module {

  public $name = 'geozzy';
  public $version = 8;

  // public $autoIncludeAlways = true;
  // FALLA: PHP Fatal error:  Class 'Smarty' not found in /home/proxectos/cogumelo/coreClasses/coreView/Template.php on line 10

  // public $dependences = array();

  // pre-170126
  // 'id' =>'ckeditor',
  // 'params' => array('ckeditor#standard/stable'),
  // 'installer' => 'bower',
  // 'includes' => array('ckeditor.js'),
  // 'autoinclude' => false


  public $includesCommon = array(
    'controller/ResourceController.php',
    'model/ResourceModel.php',
    'model/UrlAliasModel.php',
    'view/GeozzyTaxonomytermView.php',
    'js/generateModal.js'
  );


  public function __construct() {
    // $this->addUrlPatterns( '#^'.MOD_GEOZZY_URL_DIR.'$#', 'view:AdminViewStadistic::main' );

    // geozzy Resource
    //$this->addUrlPatterns( '#^recursoForm$#', 'view:GeozzyResourceView::loadForm' );
    //$this->addUrlPatterns( '#^recurso-form-action$#', 'view:GeozzyResourceView::actionCreate' );

    // Sitemap
    $this->addUrlPatterns( '#^sitemap.xml$#', 'view:SitemapView::showSitemap' );
  }



  public function setGeozzyUrlPatternsAPI() {
    // geozzy api UI events
    // $this->addUrlPatterns( '#^api/core/uieventlist$#', 'view:GeozzyAPIView::uiEventList' );

    // geozzy core api doc
    $this->addUrlPatterns( '#^api/doc/bi.json$#', 'view:GeozzyAPIView::biJson' );
    $this->addUrlPatterns( '#^api/doc/resources.json$#', 'view:GeozzyAPIView::resourcesJson' );
    $this->addUrlPatterns( '#^api/doc/resourceTypes.json$#', 'view:GeozzyAPIView::resourceTypesJson' );
    $this->addUrlPatterns( '#^api/doc/resourceIndex.json$#', 'view:GeozzyAPIView::resourceIndexJson' );
    $this->addUrlPatterns( '#^api/doc/collections.json$#', 'view:GeozzyAPIView::collectionsJson' );
    $this->addUrlPatterns( '#^api/doc/starred.json$#', 'view:GeozzyAPIView::starredJson' );
    $this->addUrlPatterns( '#^api/doc/categoryList.json$#', 'view:GeozzyAPIView::categoryListJson' );
    $this->addUrlPatterns( '#^api/doc/categoryTerms.json$#', 'view:GeozzyAPIView::categoryTermsJson' );
    $this->addUrlPatterns( '#^api/doc/topicList.json$#', 'view:GeozzyAPIView::topicListJson' );

    $this->addUrlPatterns( '#^api/doc/userLogin.json$#', 'view:GeozzyAPIView::userLoginJson' );
    $this->addUrlPatterns( '#^api/doc/userLogout.json$#', 'view:GeozzyAPIView::userLogoutJson' );
    $this->addUrlPatterns( '#^api/doc/userUnknownPass.json$#', 'view:GeozzyAPIView::userUnknownPassJson' );
    $this->addUrlPatterns( '#^api/doc/cgml-session.json$#', 'view:GeozzyAPIView::cgmlSessionJson' );
    $this->addUrlPatterns( '#^api/doc/userSession.json$#', 'view:GeozzyAPIView::userSessionJson' );
    //$this->addUrlPatterns( '#^api/uiEventList.json$#', 'view:GeozzyAPIView::uiEventListJson' );



    // geozzy api resources
    $this->addUrlPatterns( '#^api/core/starred$#', 'view:GeozzyAPIView::starred' );
    $this->addUrlPatterns( '#^api/core/resourcelist(.*)$#', 'view:GeozzyAPIView::resourceList' );
    $this->addUrlPatterns( '#^api/core/resourceIndex(.*)#', 'view:GeozzyAPIView::resourceIndex' );
    $this->addUrlPatterns( '#^api/core/resourcetypes$#', 'view:GeozzyAPIView::resourceTypes' );

    // geozzy api collections
    $this->addUrlPatterns( '#^api/(collections(/.*)?)$#', 'view:GeozzyAPIView::collections' );

    // geozzy api Categories
    $this->addUrlPatterns( '#^api/core/categorylist$#', 'view:GeozzyAPIView::categoryList' );
    $this->addUrlPatterns( '#^api/core/categoryterms/(.*)$#', 'view:GeozzyAPIView::categoryTerms' );

    // geozzy api Topics
    $this->addUrlPatterns( '#^api/core/topiclist$#', 'view:GeozzyAPIView::topicList' );

    // geozzy api Users
    $this->addUrlPatterns( '#^api/core/userlogin#', 'view:GeozzyAPIView::userLogin' );
    $this->addUrlPatterns( '#^api/core/userlogout#', 'view:GeozzyAPIView::userLogout' );
    $this->addUrlPatterns( '#^api/core/userunknownpass#', 'view:GeozzyAPIView::userUnknownPass' );
    $this->addUrlPatterns( '#^api/core/usersession#', 'view:GeozzyAPIView::userSession' );


    if( Cogumelo::getSetupValue('geozzy:api:usersInfo:access') ) {
      $this->addUrlPatterns( '#^api/doc/usersInfo.json$#', 'view:GeozzyAPIView::usersInfoJson' );
      $this->addUrlPatterns( '#^api/core/usersinfo#', 'view:GeozzyAPIView::usersInfo' );
    }
  }


  public function getGeozzyDocAPI() {

    $docApi = array(
      array(
        'path' => '/doc/bi.json',
        'description' => 'BI dashboard utils'
      ),
      array(
        'path' => '/doc/resourceTypes.json',
        'description' => 'resourceTypes'
      ),
      array(
        'path' => '/doc/resources.json',
        'description' => 'Core Resource'
      ),
      array(
        'path' => '/doc/resourceIndex.json',
        'description' => 'Resource index'
      ),
      array(
        'path' => '/doc/collections.json',
        'description' => 'Get collections'
      ),
      array(
        'path' => '/doc/starred.json',
        'description' => 'Starred terms with resources'
      ),
      array(
        'path' => '/doc/categoryList.json',
        'description' => 'Category List'
      ),
      array(
        'path' => '/doc/categoryTerms.json',
        'description' => 'CategoryTerms by category'
      ),
      array(
        'path' => '/doc/topicList.json',
        'description' => 'Topics'
      ),
      array(
        'path' => '/doc/userLogin.json',
        'description' => 'User Login'
      ),
      array(
        'path' => '/doc/userLogout.json',
        'description' => 'User Logout'
      ),
      array(
        'path' => '/doc/userUnknownPass.json',
        'description' => 'User new password'
      ),
      array(
        'path' => '/doc/cgml-session.json',
        'description' => 'Get Cogumelo session info'
      ),
      array(
        'path' => '/doc/userSession.json',
        'description' => 'User Session'
      ),
    );

    if( Cogumelo::getSetupValue('geozzy:api:usersInfo:access') ) {
      $docApi[] = [ 'path' => '/doc/usersInfo.json', 'description' => 'Users Info' ];
    }

    return $docApi;
  }



  public function moduleRc() {
    user::load('model/UserModel.php');
    user::load('model/RoleModel.php');
    geozzy::load('model/TaxonomygroupModel.php');
    geozzy::load('model/TaxonomytermModel.php');
    geozzy::load('model/TopicModel.php');
    geozzy::load('model/ResourcetypeModel.php');
    geozzy::load('model/ResourcetypeTopicModel.php');


    //
    // Creamos un usuario superAdmin para Geozzy
    //
    $passwd = false;

    $fileConnectionsInfo = APP_BASE_PATH.'/conf/inc/default-connections-info.php';
    if( file_exists( $fileConnectionsInfo ) ) {
      include $fileConnectionsInfo;
      if( defined( 'SUPERADMIN_PASS' ) ) {
        $passwd = SUPERADMIN_PASS;
      }
    }

    if( !$passwd ) {
      fwrite( STDOUT, "Enter password for superadmin@geozzy.com password:\n" );
      $passwd = self::getPassword(true);
    }

    $userData = array(
      'login' => 'superadmin@geozzy.com',
      'name' => 'SuperAdmin',
      'surname' => 'Geozzy',
      'email' => 'superadmin@geozzy.com',
      'timeCreateUser' => gmdate( 'Y-m-d H:i:s', time() ),
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

    // first initialize terms
    $this->initializeOrUpdateTerms();
  }



  public function moduleDeploy() {
    geozzy::load('model/TaxonomygroupModel.php');
    geozzy::load('model/TaxonomytermModel.php');
    geozzy::load('model/TopicModel.php');
    geozzy::load('model/ResourcetypeModel.php');
    geozzy::load('model/ResourcetypeTopicModel.php');

    // update terms
    $this->initializeOrUpdateTerms( true );
  }

  /*
  *  Add taxonomías base de app, STARRED and MENU
  */
  public function initializeOrUpdateTerms( $isDeploy =false ) {


    global $GEOZZY_TAXONOMYGROUPS;

    if( file_exists(APP_BASE_PATH.'/conf/inc/geozzyTaxonomyGroups.php') ) {
      require_once APP_BASE_PATH.'/conf/inc/geozzyTaxonomyGroups.php';
    }
    else {
      $GEOZZY_TAXONOMYGROUPS = [];
    }


    $GEOZZY_TAXONOMYGROUPS['starred'] = array(
      'idName' => 'starred',
      'name' => array(
        'es' => 'Starred',
        'en' => 'Destacado',
        'gl' => 'Destacado'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 1
    );

    if( file_exists(APP_BASE_PATH.'/conf/inc/geozzyStarred.php') ) {
      require_once APP_BASE_PATH.'/conf/inc/geozzyStarred.php';
      global $GEOZZY_STARRED;
      $GEOZZY_TAXONOMYGROUPS['starred']['initialTerms'] = $GEOZZY_STARRED;
    }


    // only add menu items when not deploy
    if( $isDeploy == false ) {
      $GEOZZY_TAXONOMYGROUPS['menu'] = array(
        'idName' => 'menu',
        'name' => array(
          'es' => 'Menu',
          'en' => 'Menu',
          'gl' => 'Menu'
        ),
        'editable' => 0,
        'nestable' => 4,
        'sortable' => 1
      );

      if( file_exists(APP_BASE_PATH.'/conf/inc/geozzyMenu.php') ) {
        require_once APP_BASE_PATH.'/conf/inc/geozzyMenu.php';
        $GEOZZY_TAXONOMYGROUPS['menu']['initialTerms'] = $GEOZZY_MENU;
      }
    }

    /*
    Creamos las taxonomías definidas
    */
    if( count( $GEOZZY_TAXONOMYGROUPS ) > 0 ) {
      foreach( $GEOZZY_TAXONOMYGROUPS as $tax ) {
        foreach( $tax['name'] as $langKey => $name ) {
          $tax['name_'.$langKey] = $name;
        }
        unset($tax['name']);
        $existTaxonomygroupModel = ( new TaxonomygroupModel() )->listItems(['filters'=>['idName'=> $tax['idName'] ]])->fetch();
        if( $existTaxonomygroupModel ) {
          $tax['id'] = $existTaxonomygroupModel->getter('id');
        }
        $taxgroup = new TaxonomygroupModel( $tax );
        $taxgroup->save();



        if( isset($tax['initialTerms']) && count( $tax['initialTerms']) > 0 ) {
          $this->createTermsArray( $taxgroup->getter('id'), false, $tax['initialTerms']);
        }
      }
    }
  }




  /**
   * This function is to create terms
   */
  public function createTermsArray( $taxId, $parentId, $terms ){
    foreach( $terms as $term ) {
      $term['taxgroup'] = $taxId;
      if(!empty($parentId)){
        $term['parent'] = $parentId;
      }
      foreach( $term['name'] as $langKey => $name ) {
        $term['name_'.$langKey] = $name;
      }

      /*
      unset($term['name']);
      $taxterm = new TaxonomytermModel( $term );
      $taxterm->save();
      */



      unset($term['name']);
      $existTaxonomytermModel = ( new TaxonomytermModel() )->listItems(['filters'=>['idName'=> $term['idName'], 'taxgroup'=> $term['taxgroup']  ]])->fetch();
      if( $existTaxonomytermModel ) {
        $term['id'] = $existTaxonomytermModel->getter('id');
      }

      $taxterm = new TaxonomytermModel( $term );
      $taxterm->save();


      if(!empty ($term['children']) ){
        $this->createTermsArray( $taxId, $taxterm->getter('id'), $term['children']);
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
  static public function getPassword( $stars = false ) {
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
            $password = mb_substr($password, 0, -1);
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
