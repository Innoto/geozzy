<?php


Cogumelo::load("coreController/Module.php");


class geozzyUser extends Module
{
  public $name = "geozzyUser";
  public $version = 1.0;


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
    'js/model/UserSessionModel.js',
    'js/view/UserLoginBoxView.js',
    'js/view/UserRegisterBoxView.js',
    'js/UserSession.js'
  );


  function __construct() {

  }

}
