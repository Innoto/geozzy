<?php
Cogumelo::load( 'coreController/Module.php' );


class rextEatAndDrink extends Module {

  public $name = 'rextEatAndDrink';
  public $version = 1.0;


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
          'idName' => 'xamoneria',
          'icon' => 'view/categoryIcons/xamoneria.svg',
          'name' => array(
            'en' => 'Ham bars',
            'es' => 'Jamonerías',
            'gl' => 'Xamonerías'
          )
        ),
        array(
          'idName' => 'pulpeira',
          'icon' => 'view/categoryIcons/pulpeira.svg',
          'name' => array(
            'en' => 'Octopus bars',
            'es' => 'Pulperías',
            'gl' => 'Pulpeiras'
          )
        ),
        array(
          'idName' => 'meson',
          'icon' => 'view/categoryIcons/meson.svg',
          'name' => array(
            'en' => 'Restaurants',
            'es' => 'Mesones-restaurantes',
            'gl' => 'Mesóns-restaurantes'
          )
        ),
        array(
          'idName' => 'furancho',
          'icon' => 'view/categoryIcons/furancho.svg',
          'name' => array(
            'en' => 'Furanchos',
            'es' => 'Furanchos',
            'gl' => 'Furanchos'
          )
        ),
        array(
          'idName' => 'parrillada',
          'icon' => 'view/categoryIcons/parrillada.svg',
          'name' => array(
            'en' => 'Grill restaurants',
            'es' => 'Parrilladas',
            'gl' => 'Churrasquerías'
          )
        ),
        array(
          'idName' => 'marisquería',
          'icon' => 'view/categoryIcons/marisqueria.svg',
          'name' => array(
            'en' => 'Seefood restaurant',
            'es' => 'Marisquería',
            'gl' => 'Marisquería'
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
            'en' => 'Meat and Fish Pie',
            'es' => 'Empanada',
            'gl' => 'Empanada'
          )
        ),
        array(
          'idName' => 'churrasco',
          'name' => array(
            'en' => 'BBQ meat',
            'es' => 'Churrasco',
            'gl' => 'Churrasco'
          )
        ),
        array(
          'idName' => 'bacalao',
          'name' => array(
            'en' => 'Cod Fish',
            'es' => 'Bacalao',
            'gl' => 'Bacalao'
          )
        ),
        array(
          'idName' => 'carneabrasa',
          'name' => array(
            'en' => 'Grilled meats',
            'es' => 'Carnes a la brasa',
            'gl' => 'Carnes á brasa'
          )
        ),
        array(
          'idName' => 'lamprea',
          'name' => array(
            'en' => 'Lamprea',
            'es' => 'Lamprea',
            'gl' => 'Lamprea'
          )
        ),
        array(
          'idName' => 'cocido',
          'name' => array(
            'en' => 'Boiled meat and vegetables',
            'es' => 'Cocido',
            'gl' => 'Cocido'
          )
        ),
        array(
          'idName' => 'mexillons',
          'name' => array(
            'en' => 'Mussels',
            'es' => 'Mejillones',
            'gl' => 'Mexillóns'
          )
        ),
        array(
          'idName' => 'marraxo',
          'name' => array(
            'en' => 'Marraxo',
            'es' => 'Marraxo',
            'gl' => 'Marraxo'
          )
        ),
        array(
          'idName' => 'costrada',
          'name' => array(
            'en' => 'Costrada',
            'es' => 'Costrada',
            'gl' => 'Costrada'
          )
        ),
        array(
          'idName' => 'marisco',
          'name' => array(
            'en' => 'Sea food',
            'es' => 'Marisco',
            'gl' => 'Marisco'
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
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
