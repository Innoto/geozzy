<?php

Cogumelo::load('coreView/View.php');
Cogumelo::load('coreController/I18nController.php');
common::autoIncludes();
user::autoIncludes();
geozzy::autoIncludes();
Cogumelo::autoIncludes();

appResourceBridge::load( 'view/AppResourceBridgeView.php' );


class PageErrorView extends View {

  public function __construct( $baseDir = false ) {
    parent::__construct( $baseDir );
    global $C_LANG;
    $this->actLang = $C_LANG;
  }

  public function page404() {
    $bridge = new AppResourceBridgeView();
    $bridge->page404();
  }

  public function page403() {
    $bridge = new AppResourceBridgeView();
    $bridge->page403();
  }


  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return true;
  }
}
