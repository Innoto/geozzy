<?php
require_once APP_BASE_PATH."/conf/geozzyAPI.php";
Cogumelo::load('coreView/View.php');
geozzy::autoIncludes();
geozzy::load( 'view/GeozzyResourceView.php' );
//admin::autoIncludes();
user::autoIncludes();
common::autoIncludes();


/**
* Clase Master to extend other application methods
*/
class AdminFormsAPIView extends AdminViewMaster
{

  public function __construct( $baseDir ){
    parent::__construct( $baseDir );
    $this->baseDir = $baseDir;
  }

  



}
