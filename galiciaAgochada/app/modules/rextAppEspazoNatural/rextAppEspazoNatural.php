<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppEspazoNatural extends Module {

  public $name = 'rextAppEspazoNatural';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextAppEspazoNaturalType' => array(
      'idName' => 'rextAppEspazoNaturalType',
      'name' => array(
        'en' => 'EspazoNatural Type',
        'es' => 'Tipo de Espacio natural',
        'gl' => 'Tipo de Espazo natural'
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
    'controller/RExtAppEspazoNaturalController.php'
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rExtModuleRc( __CLASS__ );
  }
}
