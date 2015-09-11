<?php

$geozzyTopicsInfo = array(
  'PaisaxesEspectaculares' => array(
    'name' => array(
      'es' => 'Paisajes espectaculares',
      'en' => 'Spectacular scenery',
      'gl' => 'Paisaxes espectaculares'
    ),
    'resourceTypes' => array(
      'rtypeRuta' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeRuta/classes/view/example::viewHtml',
          'update' => 'modules/rtypeRuta/classes/view/example::update',
          'create' => 'modules/rtypeRuta/classes/view/example::create'
        ),
        'weight' => 1
      ),
      'rtypeEspazoNatural' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeEspazoNatural/classes/view/example::viewHtml',
          'update' => 'modules/rtypeEspazoNatural/classes/view/example::update',
          'create' => 'modules/rtypeEspazoNatural/classes/view/example::create'
        ),
        'weight' => 3
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
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
      'rtypeRuta' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeRuta/classes/view/example::viewHtml',
          'update' => 'modules/rtypeRuta/classes/view/example::update',
          'create' => 'modules/rtypeRuta/classes/view/example::create'
        ),
        'weight' => 2
      ),
      'rtypeEspazoNatural' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeEspazoNatural/classes/view/example::viewHtml',
          'update' => 'modules/rtypeEspazoNatural/classes/view/example::update',
          'create' => 'modules/rtypeEspazoNatural/classes/view/example::create'
        ),
        'weight' => 1
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
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
      'rtypeRuta' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeRuta/classes/view/example::viewHtml',
          'update' => 'modules/rtypeRuta/classes/view/example::update',
          'create' => 'modules/rtypeRuta/classes/view/example::create'
        ),
        'weight' => 3
      ),
      'rtypeLugar' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeLugar/classes/view/example::viewHtml',
          'update' => 'modules/rtypeLugar/classes/view/example::update',
          'create' => 'modules/rtypeLugar/classes/view/example::create'
        ),
        'weight' => 1
      ),
      'rtypeEspazoNatural' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeEspazoNatural/classes/view/example::viewHtml',
          'update' => 'modules/rtypeEspazoNatural/classes/view/example::update',
          'create' => 'modules/rtypeEspazoNatural/classes/view/example::create'
        ),
        'weight' => 2
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
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
      'rtypeHotel' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeHotel/classes/view/example::viewHtml',
          'update' => 'modules/rtypeHotel/classes/view/example::update',
          'create' => 'modules/rtypeHotel/classes/view/example::create'
        ),
        'weight' => 2
      ),
      'rtypeRestaurant' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeRestaurant/classes/view/example::viewHtml',
          'update' => 'modules/rtypeRestaurant/classes/view/example::update',
          'create' => 'modules/rtypeRestaurant/classes/view/example::create'
        ),
        'weight' => 2
      ),
      'rtypeFestaPopular' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeFestaPopular/classes/view/example::viewHtml',
          'update' => 'modules/rtypeFestaPopular/classes/view/example::update',
          'create' => 'modules/rtypeFestaPopular/classes/view/example::create'
        ),
        'weight' => 2
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
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
      'rtypeHotel' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeHotel/classes/view/example::viewHtml',
          'update' => 'modules/rtypeHotel/classes/view/example::update',
          'create' => 'modules/rtypeHotel/classes/view/example::create'
        ),
        'weight' => 1
      ),
      'rtypeRestaurant' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeRestaurant/classes/view/example::viewHtml',
          'update' => 'modules/rtypeRestaurant/classes/view/example::update',
          'create' => 'modules/rtypeRestaurant/classes/view/example::create'
        ),
        'weight' => 1
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
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
      'rtypeFestaPopular' => array(
        'form' => array(
          'viewHtml' => 'modules/rtypeFestaPopular/classes/view/example::viewHtml',
          'update' => 'modules/rtypeFestaPopular/classes/view/example::update',
          'create' => 'modules/rtypeFestaPopular/classes/view/example::create'
        ),
        'weight' => 1
      )
    ),
    'table' => array(
      'actions' => array(
        'viewHtml' => 'auto',
        'action' => 'auto'
      ),
    ),
    'taxgroup' => 12,
    'icon' => '',
    'weight' => 1
  )
);
