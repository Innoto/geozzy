<?php


Cogumelo::load("coreController/Module.php");


class geozzyAPI extends Module {
  public $name = "geozzyAPI";
  public $version = 1.0;


  public $dependences = array(
    array(
     "id" =>"swagger-ui",
     "params" => array("swagger-ui@3"),
     "installer" => "yarn",
     "includes" => array("")
    )

  );

  function __construct() {
    // API DOC GENERATOR
    $this->addUrlPatterns( '#^api/?$#', 'view:DocAPIView::main' );
    $this->addUrlPatterns( '#^api/doc/index.json$#', 'view:DocAPIView::apidocJson' ); // Main swagger JSON

    global $COGUMELO_IS_EXECUTING_FROM_SCRIPT;

    if( $COGUMELO_IS_EXECUTING_FROM_SCRIPT !== true) {
      require_once APP_BASE_PATH."/conf/inc/geozzyAPI.php";


      if( GEOZZY_API_ACTIVE === true ) {
        $this->addUrlPatternsAPI();
      }
    }

  }

  function addUrlPatternsAPI() {
    global $C_INDEX_MODULES;
    global $COGUMELO_INSTANCED_MODULES;
    global $GEOZZY_API_ALLOWED_MODULES;

    if( count($C_INDEX_MODULES)>0 ) {

      foreach($C_INDEX_MODULES as $modName) {
        if(
          isset($COGUMELO_INSTANCED_MODULES[$modName]) &&
          method_exists($COGUMELO_INSTANCED_MODULES[$modName], 'setGeozzyUrlPatternsAPI') &&
          in_array($modName, $GEOZZY_API_ALLOWED_MODULES)
        ) {
          $COGUMELO_INSTANCED_MODULES[$modName]->setGeozzyUrlPatternsAPI();
        }
      }

    }


  }

  static function addModuleAPI( $moduleToAddApi ) {
    global $COGUMELO_INSTANCED_MODULES;
    global $GEOZZY_API_ALLOWED_MODULES;
    global $GEOZZY_API_DOC_URLS;

    if( !is_array($GEOZZY_API_DOC_URLS) ){
      $GEOZZY_API_DOC_URLS = [];
    }
    if( !is_array($GEOZZY_API_ALLOWED_MODULES) ){
      $GEOZZY_API_ALLOWED_MODULES = [];
    }

    $GEOZZY_API_ALLOWED_MODULES[] = $moduleToAddApi;

    $m = $COGUMELO_INSTANCED_MODULES[$moduleToAddApi];
    $GEOZZY_API_DOC_URLS = array_merge( $GEOZZY_API_DOC_URLS, $m->getGeozzyDocAPI() );

  }
}
