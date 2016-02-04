<?php

global $GEOZZY_EXPLORERS;


$GEOZZY_EXPLORERS = array(
  'default' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/DefaultExplorerController.php',
    'controllerName' => 'DefaultExplorerController',
    'name' => 'Default',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array()
    )
  ),
  'paisaxes' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/PaisaxesExplorerController.php',
    'controllerName' => 'PaisaxesExplorerController',
    'name' => 'Paisaxes Espectaculares',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'rextAppEspazoNaturalType', 'rextAppZonaType' )
    )
  ),
  'xantares' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/XantaresExplorerController.php',
    'controllerName' => 'XantaresExplorerController',
    'name' => 'Sabrosos xantares',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'eatanddrinkSpecialities', 'rextAppZonaType' )
    )
  ),
  'todosSegredos' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/TodosSegredosExplorerController.php',
    'controllerName' => 'TodosSegredosExplorerController',
    'name' => 'Descúbreos todos xuntos',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'eatanddrinkSpecialities', 'rextAppZonaType' )
    )
  ),
  'aloxamentos' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/AloxamentosExplorerController.php',
    'controllerName' => 'AloxamentosExplorerController',
    'name' => 'Aloxamentos con encanto',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'accommodationType', 'accommodationCategory',
        'accommodationServices', 'accommodationFacilities', 'rextAppZonaType'
      )
    )
  ),
  'praias' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/PraiasExplorerController.php',
    'controllerName' => 'PraiasExplorerController',
    'name' => 'Praias de ensono',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array()
    )
  ),
  'rincons' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/RinconsExplorerController.php',
    'controllerName' => 'RinconsExplorerController',
    'name' => 'Rincóns con encanto',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'rextAppLugarType', 'rextAppZonaType' )
    )
  )
);
