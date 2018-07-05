<?php

Cogumelo::load("coreController/Module.php");

class explorerSearch extends Module {
  public $name = "explorerSearch";
  public $version = 1;

  public $dependences = array(
  );

  public $includesCommon = array(


    //'js/Explorer.js',
    //'styles/explorerMapArrows.less'
  );


  public function __construct() {

  }


  function setGeozzyUrlPatternsAPI() {
    $this->addUrlPatterns( '#^api/explorerSearch#', 'view:ExplorerSearchAPIView::explorerSearch' );
    $this->addUrlPatterns( '#^api/doc/explorerSearch.json#', 'view:ExplorerSearchAPIView::explorerSearchJson' ); // Main swagger JSON
  }


  function getGeozzyDocAPI() {

    $ret = array(
      array(
        'path'=> '/doc/explorerSearch.json',
        'description' => 'Explorer search API'
      )
    );

    return $ret;
  }

}
