<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppLugar extends Module {

  public $name = 'rextAppLugar';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextAppLugarType' => array(
      'idName' => 'rextAppLugarType',
      'name' => array(
        'en' => 'Lugar type',
        'es' => 'Tipo de Lugar',
        'gl' => 'Tipo de Lugar'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'praia',
          'name' => array(
            'en' => 'Beach',
            'es' => 'Playa',
            'gl' => 'Praia'
          )
        ),
        array(
          'idName' => 'monte',
          'name' => array(
            'en' => 'Forest',
            'es' => 'Bosque',
            'gl' => 'Monte'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAppLugarController.php'
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rExtModuleRc( __CLASS__ );
  }
}
