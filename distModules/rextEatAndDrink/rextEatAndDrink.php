<?php
Cogumelo::load( 'coreController/Module.php' );


class rextEatAndDrink extends Module {

  public $name = 'rextEatAndDrink';
  public $version = '1.0';


  public $models = array(
    'EatAndDrinkModel'
  );

  public $taxonomies = array(
    'eatanddrinkType' => array(
      'idName' => 'eatanddrinkType',
      'name' => array(
        'en' => 'Restaurant type',
        'es' => 'Tipo de local',
        'gl' => 'Tipo de local'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'italiano',
          'name' => array(
            'en' => 'Italiano',
            'es' => 'Italiano',
            'gl' => 'Italiano'
          )
        ),
        array(
          'idName' => 'tapas',
          'name' => array(
            'en' => 'Tapas',
            'es' => 'Tapas',
            'gl' => 'Tapas'
          )
        ),
        array(
          'idName' => 'restaurante',
          'name' => array(
            'en' => 'Restaurante',
            'es' => 'Restaurante',
            'gl' => 'Restaurante'
          )
        )
      )
    ),

    'eatanddrinkSpecialities' => array(
      'idName' => 'eatanddrinkSpecialities',
      'name' => array(
        'en' => 'Specialities',
        'es' => 'Especialidades',
        'gl' => 'Especialidades'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'tortilla',
          'name' => array(
            'en' => 'Omelette',
            'es' => 'Tortilla',
            'gl' => 'Tortilla'
          )
        ),
        array(
          'idName' => 'pulpo',
          'name' => array(
            'en' => 'Octopus',
            'es' => 'Pulpo',
            'gl' => 'Polbo'
          )
        ),
        array(
          'idName' => 'empanada',
          'name' => array(
            'en' => 'Pie',
            'es' => 'Empanada',
            'gl' => 'Empanada'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtEatAndDrinkController.php',
    'model/EatAndDrinkModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rExtModuleRc( __CLASS__ );
  }
}
