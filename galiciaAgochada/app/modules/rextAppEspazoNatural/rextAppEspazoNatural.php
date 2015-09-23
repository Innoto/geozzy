<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppEspazoNatural extends Module {

  public $name = 'rextAppEspazoNatural';
  public $version = '1.0';


  public $models = array(
    'RExtAppEspazoNaturalModel'
  );

  public $taxonomies = array(
    'appEspazoNaturalType' => array(
      'idName' => 'appEspazoNaturalType',
      'name' => array(
        'en' => 'Espazo natural Type',
        'es' => 'Tipo de Espacio natural',
        'gl' => 'Tipo de Espazo natural'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => 'praia',
          'name' => array(
            'en' => 'Page',
            'es' => 'Página',
            'gl' => 'Páxina'
          )
        ),
        array(
          'idName' => 'monte',
          'name' => array(
            'en' => 'File',
            'es' => 'Fichero',
            'gl' => 'Ficheiro'
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
