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

    /*i18n*/
    $this->addUrlPatterns( '#^test#', 'view:Testi18n::translate' );

    /*Adminview*/
    $this->addUrlPatterns( '#^getobj$#', 'view:Adminview::getobj' );
    $this->addUrlPatterns( '#^setobj$#', 'view:Adminview::setobj' );



    /*MasterView*/
    $this->addUrlPatterns( '#^404$#', 'view:MasterView::page404' );
    $this->addUrlPatterns( '#^$#', 'view:MasterView::master' );
  }

}
