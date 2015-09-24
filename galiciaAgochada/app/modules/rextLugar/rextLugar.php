<?php
Cogumelo::load( 'coreController/Module.php' );


class rextLugar extends Module {

  public $name = 'rextLugar';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextLugarType' => array(
      'idName' => 'rextLugarType',
      'name' => array(
        'en' => 'Lugar type',
        'es' => 'Tipo de Lugar',
        'gl' => 'Tipo de Lugar'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
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
    'controller/RExtLugarController.php'
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rExtModuleRc( __CLASS__ );
  }
}
