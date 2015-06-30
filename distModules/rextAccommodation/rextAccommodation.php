<?php
Cogumelo::load( 'coreController/Module.php' );



class rextAccommodation extends Module
{
  public $name = 'rextAccommodation';
  public $version = '1.0';
  public $dependences = array();
  public $includesCommon = array();

  public $models = array(
    'AccommodationModel'
  );

  public $taxonomies = array(

    'accommodationType' => array(
      'idName' => 'accommodationType',
      'name' => array(
        'en' => 'Type',
        'es' => 'Tipo',
        'gl' => 'Tipo'
      ),
      'editable' => 1,
      'nestable' => 1,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'hotel',
          'name' => array(
            'en' => 'Hotel',
            'es' => 'Hotel',
            'gl' => 'Hotel'
          )
        ),
        array(
          'idName' => 'hostel',
          'name' => array(
            'en' => 'Hostel',
            'es' => 'Hostel',
            'gl' => 'Hostel'
          )
        ),
        array(
          'idName' => 'camping',
          'name' => array(
            'en' => 'Camping',
            'es' => 'Camping',
            'gl' => 'Camping'
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
      'nestable' => 1,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'telephone',
          'name' => array(
            'en' => 'Telephone',
            'es' => 'Teléfono',
            'gl' => 'Teléfono'
          )
        ),
        array(
          'idName' => 'roomservice',
          'name' => array(
            'en' => 'Room service',
            'es' => 'Servicio de habitaciones',
            'gl' => 'Servicio de habitacións'
          )
        )
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
      'nestable' => 1,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'bar',
          'name' => array(
            'en' => 'Bar',
            'es' => 'Bar',
            'gl' => 'Bar'
          )
        ),
        array(
          'idName' => 'parking',
          'name' => array(
            'en' => 'Parking',
            'es' => 'Aparcamiento',
            'gl' => 'Aparcamento'
          )
        )
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
      )
    )

  );


  public function __construct() {

  }

}
