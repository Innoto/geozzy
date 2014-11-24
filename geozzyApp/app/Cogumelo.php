<?php


class Cogumelo extends CogumeloClass
{

  public $dependences = array(
    array(
     "id" => "formstoneWallpaper",
     "params" => array("Wallpaper"),
     "installer" => "bower",
     "includes" => array("jquery.fs.wallpaper.js", "jquery.fs.wallpaper.css")
    )
  );
  public $includesCommon = array();


  function __construct() {
    parent::__construct();


    /*MasterView*/
    $this->addUrlPatterns( '#^$#', 'view:MasterView::main' );
    $this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
  }

}
