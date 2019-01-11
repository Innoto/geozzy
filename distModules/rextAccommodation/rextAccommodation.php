<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAccommodation extends Module {

  public $name = 'rextAccommodation';
  public $version = 1.0;


  public $models = array(
    'AccommodationModel'
  );

  public $taxonomies = array(
    'accommodationType' => array(
      'idName' => 'accommodationType',
      'name' => array(
        'en' => 'Acommodation type',
        'es' => 'Tipo de alojamiento',
        'gl' => 'Tipo de aloxamento'
      ),
      'editable' => 1,
      'nestable' => 1,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'hoteles',
          'icon' => 'view/categoryIcons/hotel.svg',
          'name' => array(
            'en' => 'Hotels',
            'es' => 'Hoteles',
            'gl' => 'Hoteis'
          )
        ),
        array(
          'idName' => 'albergues',
          'icon' => 'view/categoryIcons/albergue.svg',
          'name' => array(
            'en' => 'Hostels',
            'es' => 'Albergues',
            'gl' => 'Albergues'
          )
        ),
        array(
          'idName' => 'camping',
          'icon' => 'view/categoryIcons/camping.svg',
          'name' => array(
            'en' => 'Bungalows-campings',
            'es' => 'Bungalós-campings',
            'gl' => 'Bungalós-campings'
          )
        ),
        array(
          'idName' => 'casasrurales',
          'icon' => 'view/categoryIcons/casarural.svg',
          'name' => array(
            'en' => 'Bed & Breakfasts',
            'es' => 'Casas rurales',
            'gl' => 'Casas rurais'
          )
        ),
        array(
          'idName' => 'paradores',
          'icon' => 'view/categoryIcons/parador.svg',
          'name' => array(
            'en' => 'Palace Hotels',
            'es' => 'Paradores',
            'gl' => 'Paradores'
          )
        ),
        array(
          'idName' => 'balnearios',
          'icon' => 'view/categoryIcons/balneario.svg',
          'name' => array(
            'en' => 'Spa',
            'es' => 'Balneario',
            'gl' => 'Balneario'
          )
        )
      )
    ),

    'accommodationCategory' => array(
      'idName' => 'accommodationCategory',
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
          'idName' => '1star',
          'name' => array(
            'en' => '1 Star',
            'es' => '1 Estrella',
            'gl' => '1 Estrela'
          )
        ),
        array(
          'idName' => '2stars',
          'name' => array(
            'en' => '2 Stars',
            'es' => '2 Estrellas',
            'gl' => '2 Estrelas'
          )
        ),
        array(
          'idName' => '3stars',
          'name' => array(
            'en' => '3 Stars',
            'es' => '3 Estrellas',
            'gl' => '3 Estrelas'
          )
        ),
        array(
          'idName' => '4stars',
          'name' => array(
            'en' => '4 Stars',
            'es' => '4 Estrellas',
            'gl' => '4 Estrelas'
          )
        ),
        array(
          'idName' => '5stars',
          'name' => array(
            'en' => '5 Stars',
            'es' => '5 Estrellas',
            'gl' => '5 Estrelas'
          )
        )
      )
    ),

    'accommodationServices' => array(
      'idName' => 'accommodationServices',
      'name' => array(
        'en' => 'Services',
        'es' => 'Servicios',
        'gl' => 'Servizos'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        /*array(
          'idName' => 'telefono',
          'name' => array(
            'en' => 'Telephone',
            'es' => 'Teléfono',
            'gl' => 'Teléfono'
          )
        ),
        array(
          'idName' => 'serviciodehabitacions',
          'name' => array(
            'en' => 'Room service',
            'es' => 'Servicio de habitaciones',
            'gl' => 'Servicio de habitacións'
          )
        ),
        array(
          'idName' => 'transportepublico',
          'name' => array(
            'en' => 'Public transportation available',
            'es' => 'Accesible transporte público',
            'gl' => 'Accesible transporte público'
          )
        ),
        array(
          'idName' => 'wifi',
          'icon' => 'view/categoryIcons/wifi.svg',
          'name' => array(
            'en' => 'WiFi',
            'es' => 'WiFi',
            'gl' => 'WiFi'
          )
        ),
        array(
          'idName' => 'tarxeta',
          'icon' => 'view/categoryIcons/tarxeta.svg',
          'name' => array(
            'en' => 'Credit card accepted',
            'es' => 'Tarjeta de crédito',
            'gl' => 'Tarxeta de crédito'
          )
        ),
        array(
          'idName' => 'almorzo',
          'icon' => 'view/categoryIcons/almorzo.svg',
          'name' => array(
            'en' => 'Breakfast',
            'es' => 'Desayuno',
            'gl' => 'Almorzo'
          )
        ),
        array(
          'idName' => 'comida',
          'icon' => 'view/categoryIcons/comida.svg',
          'name' => array(
            'en' => 'Lunch and dinner',
            'es' => 'Comida y cena',
            'gl' => 'Xantar e cea'
          )
        ),
        array(
          'idName' => 'cuna',
          'icon' => 'view/categoryIcons/cuna.svg',
          'name' => array(
            'en' => 'Baby Cot',
            'es' => 'Cuna',
            'gl' => 'Berce'
          )
        ),
        array(
          'idName' => 'accesible',
          'icon' => 'view/categoryIcons/accesible.svg',
          'name' => array(
            'en' => 'Wheelchair accessible',
            'es' => 'Accesible discapacitados',
            'gl' => 'Accesible discapacitados'
          )
        )*/
      )
    ),

    'accommodationFacilities' => array(
      'idName' => 'accommodationFacilities',
      'name' => array(
        'en' => 'Facilities',
        'es' => 'Instalaciones',
        'gl' => 'Instalacións'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        /*array(
          'idName' => 'bar',
          'name' => array(
            'en' => 'Bar',
            'es' => 'Bar',
            'gl' => 'Bar'
          )
        ),
        array(
          'idName' => 'parking',
          'icon' => 'view/categoryIcons/parking.svg',
          'name' => array(
            'en' => 'Parking',
            'es' => 'Aparcamiento',
            'gl' => 'Aparcamento'
          )
        ),
        array(
          'idName' => 'piscina',
          'icon' => 'view/categoryIcons/piscina.svg',
          'name' => array(
            'en' => 'Swimming pool',
            'es' => 'Piscina',
            'gl' => 'Piscina'
          )
        ),
        array(
          'idName' => 'cocina',
          'icon' => 'view/categoryIcons/cocina.svg',
          'name' => array(
            'en' => 'Common kitchen',
            'es' => 'Cocina común',
            'gl' => 'cociña común'
          )
        ),
        array(
          'idName' => 'tv',
          'icon' => 'view/categoryIcons/tv.svg',
          'name' => array(
            'en' => 'TV',
            'es' => 'TV',
            'gl' => 'TV'
          )
        ),
        array(
          'idName' => 'lavadora',
          'name' => array(
            'en' => 'Washing machine',
            'es' => 'Lavadora',
            'gl' => 'Lavadora'
          )
        )*/
      )
    ),

    'accommodationBrand' => array(
      'idName' => 'accommodationBrand',
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
          'idName' => 'nh',
          'name' => array(
            'en' => 'NH Hotels',
            'es' => 'NH Hoteles',
            'gl' => 'NH Hoteles'
          )
        ),
        array(
          'idName' => 'melia',
          'name' => array(
            'en' => 'Meliá',
            'es' => 'Meliá',
            'gl' => 'Meliá'
          )
        )
      )
    ),

    'accommodationUsers' => array(
      'idName' => 'accommodationUsers',
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
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAccommodationController.php',
    'model/AccommodationModel.php'
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
