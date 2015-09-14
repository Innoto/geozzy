<?php
Cogumelo::load('view/MasterView.php');

common::autoIncludes();
geozzy::autoIncludes();

class ExplorerView extends MasterView
{
  public function __construct( $baseDir ) {
    parent::__construct( $baseDir );
  }
}
