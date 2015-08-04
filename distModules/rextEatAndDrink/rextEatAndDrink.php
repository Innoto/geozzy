<?php

Cogumelo::load("coreController/Module.php");

class rextEatAndDrink extends Module
{

  public $name = "rextEatAndDrink";
  public $version = "1.0";
  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtEatAndDrinkController.php',
    'model/EatAndDrinkModel.php'
  );

  public $models = array(
  	'EatAndDrinkModel'
  );

  public $taxonomies = array(

    'eatanddrinkType' => array(
      'idName' => 'eatanddrinkType',
      'name' => array(
        'en' => 'Type',
        'es' => 'Tipo',
        'gl' => 'Tipo'
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

  	'eatanddrinkCategory' => array(
      'idName' => 'eatanddrinkCategory',
      'name' => array(
        'en' => 'Category',
        'es' => 'Categoría',
        'gl' => 'Categoría'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => '1Fork',
          'name' => array(
            'en' => '1 Fork',
            'es' => '1 Tenedores',
            'gl' => '1 Garfo'
          )
        ),
        array(
          'idName' => '2Forks',
          'name' => array(
            'en' => '2 Forks',
            'es' => '2 Tenedores',
            'gl' => '2 Garfos'
          )
        ),
        array(
          'idName' => '3Forks',
          'name' => array(
            'en' => '3 Forks',
            'es' => '3 Tenedores',
            'gl' => '3 Garfos'
          )
        ),
        array(
          'idName' => '4Forks',
          'name' => array(
            'en' => '4 Forks',
            'es' => '4 Tenedores',
            'gl' => '4 Garfos'
          )
        ),
        array(
          'idName' => '5Forks',
          'name' => array(
            'en' => '5 Forks',
            'es' => '5 Tenedores',
            'gl' => '5 Garfos'
          )
        )
      )
    ),

    'eatanddrinkServices' => array(
      'idName' => 'eatanddrinkServices',
      'name' => array(
        'en' => 'Services',
        'es' => 'Servicios',
        'gl' => 'Servizos'
      ),
      'editable' => 1,
      'nestable' => 1,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'concerts',
          'name' => array(
            'en' => 'Concerts',
            'es' => 'Conciertos',
            'gl' => 'Concertos'
          )
        ),
        array(
          'idName' => 'accessible',
          'name' => array(
            'en' => 'Accessible bathroom',
            'es' => 'Baño adaptado',
            'gl' => 'Baño adaptado'
          )
        )
      )
    ),

    'eatanddrinkFacilities' => array(
      'idName' => 'eatanddrinkFacilities',
      'name' => array(
        'en' => 'Facilities',
        'es' => 'Instalaciones',
        'gl' => 'Instalacións'
      ),
      'editable' => 1,
      'nestable' => 1,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'childarea',
          'name' => array(
            'en' => 'Child area',
            'es' => 'Zona de niños',
            'gl' => 'Zona de crianzas'
          )
        ),
        array(
          'idName' => 'eatanddrinkparking',
          'name' => array(
            'en' => 'Parking',
            'es' => 'Aparcamiento',
            'gl' => 'Aparcamento'
          )
        )
      )
    ),

    'eatanddrinkBrand' => array(
      'idName' => 'eatanddrinkBrand',
      'name' => array(
        'en' => 'Brand',
        'es' => 'Cadena',
        'gl' => 'Cadea'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'cambalache',
          'name' => array(
            'en' => 'Cambalache',
            'es' => 'Cambalache',
            'gl' => 'Cambalache'
          )
        ),
        array(
          'idName' => 'manteleria',
          'name' => array(
            'en' => 'Mantelería',
            'es' => 'Mantelería',
            'gl' => 'Mantelería'
          )
        ),
        array(
          'idName' => 'casaponte',
          'name' => array(
            'en' => 'Casa Ponte',
            'es' => 'Casa Ponte',
            'gl' => 'Casa Ponte'
          )
        )
      )
    ),

    'eatanddrinkUsers' => array(
      'idName' => 'eatanddrinkUsers',
      'name' => array(
        'en' => 'Users',
        'es' => 'Usuarios',
        'gl' => 'Usuarios'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'family',
          'name' => array(
            'en' => 'Family',
            'es' => 'Familia',
            'gl' => 'Familia'
          )
        ),
        array(
          'idName' => 'youth',
          'name' => array(
            'en' => 'Youth',
            'es' => 'Jóvenes',
            'gl' => 'Xoves'
          )
        )
      )
    ),

    'eatanddrinkSpecialties' => array(
      'idName' => 'eatanddrinkSpecialties',
      'name' => array(
        'en' => 'Brand',
        'es' => 'Cadena',
        'gl' => 'Cadea'
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

  public function __construct() {}
}
