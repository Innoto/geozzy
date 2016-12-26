<?php
global $geozzyTopicsInfo;
$geozzyTopicsInfo = array(
  'PaisaxesEspectaculares' => array(
    'name' => array(
      'es' => 'Paisajes espectaculares',
      'en' => 'Spectacular scenery',
      'gl' => 'Paisaxes espectaculares'
    ),
    'resourceTypes' => array(
      'rtypeAppRuta' => array(
        'weight' => 1
      ),
      'rtypeAppEspazoNatural' => array(
        'weight' => 3
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'PraiasDeEnsono' => array(
    'name' => array(
      'es' => 'Playas de ensueño',
      'en' => 'Dream beaches',
      'gl' => 'Praias de ensono'
    ),
    'resourceTypes' => array(
      'rtypeAppRuta' => array(
        'weight' => 2
      ),
      'rtypeAppEspazoNatural' => array(
        'weight' => 1
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'RecantosConEstilo' => array(
    'name' => array(
      'es' => 'Lugares con estilo',
      'en' => 'Stylish places',
      'gl' => 'Recantos con estilo'
    ),
    'resourceTypes' => array(
      'rtypeAppRuta' => array(
        'weight' => 3
      ),
      'rtypeAppLugar' => array(
        'weight' => 1
      ),
      'rtypeAppEspazoNatural' => array(
        'weight' => 2
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'AutenticaGastronomia' => array(
    'name' => array(
      'es' => 'Auténtica gastronomía',
      'en' => 'Authentic cuisine',
      'gl' => 'Auténtica gastronomía'
    ),
    'resourceTypes' => array(
      'rtypeAppRestaurant' => array(
        'weight' => 2
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'AloxamentoConEncanto' => array(
    'name' => array(
      'es' => 'Alojamiento con encanto',
      'en' => 'Charming accommodation',
      'gl' => 'Aloxamento con encanto'
    ),
    'resourceTypes' => array(
      'rtypeAppHotel' => array(
        'weight' => 1
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'FestaRachada' => array(
    'name' => array(
      'es' => 'Fiesta',
      'en' => 'Party',
      'gl' => 'Festa rachada'
    ),
    'resourceTypes' => array(
      'rtypeAppFesta' => array(
        'weight' => 1
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'probasTopic' => array(
    'name' => array(
      'es' => 'Pruebas',
      'en' => 'Tests',
      'gl' => 'Probas'
    ),
    'resourceTypes' => array(
      'rtypeAppRestaurant' => array(
        'weight' => 2
      )
    ),
    'icon' => '',
    'weight' => 1
  ),
  'participation' => array(
    'name' => array(
      'es' => 'Participación',
      'en' => 'Participation',
      'gl' => 'Participación'
    ),
    'resourceTypes' => array(
      'rtypeAppRestaurant' => array(
        'weight' => 2
      )
    ),
    'defaultTaxonomytermIdName' => 'pendiente',
    'taxonomies' => array(
      'idName'=> 'estados_participacion',
      'name' => array(
        'es' => 'Estado',
        'en' => 'State',
        'gl' => 'Estado'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName'=>'pendiente',
          'name' => array(
            'es' => 'Pendiente',
            'en' => 'Pending',
            'gl' => 'Pendente'
          )
        ),
        array(
          'idName'=>'aceptado',
          'name' => array(
            'es' => 'Aceptado',
            'en' => 'Accepted',
            'gl' => 'Aceptado'
          )
        ),
        array(
          'idName'=>'descartado',
          'name' => array(
            'es' => 'Descartado',
            'en' => 'Discarded',
            'gl' => 'Descartado'
          )
        )
      ),
    ),

    'icon' => '',
    'weight' => 1
  )
);





class geozzyTopicsTableExtras {
  public function participation( $urlParamsList,  $tabla ) {
    $tabla->setCol('EatAndDrinkModel.averagePrice', __('PRECIO') );

    return $tabla;
  }

  public function AutenticaGastronomia( $urlParamsList,  $tabla ) {

    //$tabla->setCol('id', 'REFERENCIA');
    $tabla->setColClasses('id', 'hidden-xs');
    $tabla->setColClasses('rTypeId', 'hidden-xs');
    $tabla->setCol('EatAndDrinkModel.averagePrice', __('PRECIO') );
    return $tabla;
  }

}
